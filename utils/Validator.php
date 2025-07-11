<?php

class Validator {
    
    private $errors = [];
    private $data = [];
    
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    /**
     * Valida un campo como requerido
     */
    public function required($field, $message = null) {
        if (empty($this->data[$field])) {
            $this->errors[$field] = $message ?: "El campo {$field} es obligatorio.";
        }
        return $this;
    }
    
    /**
     * Valida formato de email
     */
    public function email($field, $message = null) {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?: "El formato del email no es válido.";
        }
        return $this;
    }
    
    /**
     * Valida longitud mínima
     */
    public function minLength($field, $min, $message = null) {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field] = $message ?: "El campo {$field} debe tener al menos {$min} caracteres.";
        }
        return $this;
    }
    
    /**
     * Valida longitud máxima
     */
    public function maxLength($field, $max, $message = null) {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field] = $message ?: "El campo {$field} no puede tener más de {$max} caracteres.";
        }
        return $this;
    }
    
    /**
     * Valida que dos campos coincidan
     */
    public function match($field1, $field2, $message = null) {
        if (!empty($this->data[$field1]) && !empty($this->data[$field2])) {
            if ($this->data[$field1] !== $this->data[$field2]) {
                $this->errors[$field2] = $message ?: "Los campos {$field1} y {$field2} deben coincidir.";
            }
        }
        return $this;
    }
    
    /**
     * Valida que sea un número
     */
    public function numeric($field, $message = null) {
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message ?: "El campo {$field} debe ser un número.";
        }
        return $this;
    }
    
    /**
     * Valida que sea un número entero
     */
    public function integer($field, $message = null) {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
            $this->errors[$field] = $message ?: "El campo {$field} debe ser un número entero.";
        }
        return $this;
    }
    
    /**
     * Valida rango de valores
     */
    public function range($field, $min, $max, $message = null) {
        if (!empty($this->data[$field])) {
            $value = (float)$this->data[$field];
            if ($value < $min || $value > $max) {
                $this->errors[$field] = $message ?: "El campo {$field} debe estar entre {$min} y {$max}.";
            }
        }
        return $this;
    }
    
    /**
     * Valida formato de teléfono
     */
    public function phone($field, $message = null) {
        if (!empty($this->data[$field]) && !preg_match('/^[0-9]{9}$/', $this->data[$field])) {
            $this->errors[$field] = $message ?: "El número de teléfono debe tener 9 dígitos.";
        }
        return $this;
    }
    
    /**
     * Valida que el valor esté en una lista
     */
    public function in($field, $values, $message = null) {
        if (!empty($this->data[$field]) && !in_array($this->data[$field], $values)) {
            $this->errors[$field] = $message ?: "El campo {$field} debe ser uno de los valores permitidos.";
        }
        return $this;
    }
    
    /**
     * Valida formato de fecha
     */
    public function date($field, $format = 'Y-m-d', $message = null) {
        if (!empty($this->data[$field])) {
            $date = DateTime::createFromFormat($format, $this->data[$field]);
            if (!$date || $date->format($format) !== $this->data[$field]) {
                $this->errors[$field] = $message ?: "El campo {$field} debe ser una fecha válida.";
            }
        }
        return $this;
    }
    
    /**
     * Valida URL
     */
    public function url($field, $message = null) {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
            $this->errors[$field] = $message ?: "El campo {$field} debe ser una URL válida.";
        }
        return $this;
    }
    
    /**
     * Validación personalizada
     */
    public function custom($field, $callback, $message = null) {
        if (!empty($this->data[$field]) && !call_user_func($callback, $this->data[$field])) {
            $this->errors[$field] = $message ?: "El campo {$field} no es válido.";
        }
        return $this;
    }
    
    /**
     * Verifica si hay errores
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
    
    /**
     * Obtiene todos los errores
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Obtiene el primer error
     */
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
    
    /**
     * Obtiene los errores como string
     */
    public function getErrorsAsString($separator = '<br>') {
        return implode($separator, $this->errors);
    }
    
    /**
     * Limpia los datos de entrada
     */
    public function sanitize() {
        $sanitized = [];
        foreach ($this->data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }
    
    /**
     * Validaciones específicas para diferentes entidades
     */
    
    /**
     * Valida datos de usuario
     */
    public static function validateUser($data) {
        $validator = new self($data);
        
        return $validator
            ->required('nombre', 'El nombre es obligatorio')
            ->required('apellido', 'El apellido es obligatorio')
            ->required('email', 'El email es obligatorio')
            ->email('email', 'El formato del email no es válido')
            ->required('rol', 'El rol es obligatorio')
            ->in('rol', ['admin', 'entrenador', 'entrenado'], 'El rol debe ser admin, entrenador o entrenado')
            ->phone('telefono', 'El teléfono debe tener 9 dígitos')
            ->maxLength('nombre', 100, 'El nombre no puede tener más de 100 caracteres')
            ->maxLength('apellido', 100, 'El apellido no puede tener más de 100 caracteres');
    }
    
    /**
     * Valida datos de usuario con contraseña
     */
    public static function validateUserWithPassword($data) {
        $validator = self::validateUser($data);
        
        return $validator
            ->required('password', 'La contraseña es obligatoria')
            ->minLength('password', 8, 'La contraseña debe tener al menos 8 caracteres')
            ->match('password', 'confirm_password', 'Las contraseñas no coinciden');
    }
    
    /**
     * Valida datos de entrenamiento
     */
    public static function validateEntrenamiento($data) {
        $validator = new self($data);
        
        return $validator
            ->required('nombre', 'El nombre del entrenamiento es obligatorio')
            ->maxLength('nombre', 200, 'El nombre no puede tener más de 200 caracteres')
            ->maxLength('descripcion', 1000, 'La descripción no puede tener más de 1000 caracteres');
    }
    
    /**
     * Valida datos de ejercicio
     */
    public static function validateEjercicio($data) {
        $validator = new self($data);
        
        return $validator
            ->required('nombre', 'El nombre del ejercicio es obligatorio')
            ->required('tipo_id', 'El tipo de ejercicio es obligatorio')
            ->integer('tipo_id', 'El tipo de ejercicio debe ser un número')
            ->maxLength('nombre', 200, 'El nombre no puede tener más de 200 caracteres')
            ->maxLength('descripcion', 1000, 'La descripción no puede tener más de 1000 caracteres')
            ->url('video_url', 'La URL del video no es válida');
    }
    
    /**
     * Valida datos de programación
     */
    public static function validateProgramacion($data) {
        $validator = new self($data);
        
        return $validator
            ->required('nombre', 'El nombre de la programación es obligatorio')
            ->required('duracion_semanas', 'La duración en semanas es obligatoria')
            ->required('entrenamientos_por_semana', 'Los entrenamientos por semana son obligatorios')
            ->integer('duracion_semanas', 'La duración debe ser un número entero')
            ->integer('entrenamientos_por_semana', 'Los entrenamientos por semana deben ser un número entero')
            ->range('duracion_semanas', 1, 52, 'La duración debe estar entre 1 y 52 semanas')
            ->range('entrenamientos_por_semana', 1, 7, 'Los entrenamientos por semana deben estar entre 1 y 7')
            ->maxLength('nombre', 200, 'El nombre no puede tener más de 200 caracteres')
            ->maxLength('descripcion', 1000, 'La descripción no puede tener más de 1000 caracteres');
    }
    
    /**
     * Valida datos de valoración
     */
    public static function validateValoracion($data) {
        $validator = new self($data);
        
        return $validator
            ->required('calidad', 'La calidad es obligatoria')
            ->required('esfuerzo', 'El esfuerzo es obligatorio')
            ->required('complejidad', 'La complejidad es obligatoria')
            ->required('duracion', 'La duración es obligatoria')
            ->integer('calidad', 'La calidad debe ser un número')
            ->integer('esfuerzo', 'El esfuerzo debe ser un número')
            ->integer('complejidad', 'La complejidad debe ser un número')
            ->integer('duracion', 'La duración debe ser un número')
            ->range('calidad', 1, 5, 'La calidad debe estar entre 1 y 5')
            ->range('esfuerzo', 1, 5, 'El esfuerzo debe estar entre 1 y 5')
            ->range('complejidad', 1, 5, 'La complejidad debe estar entre 1 y 5')
            ->range('duracion', 1, 5, 'La duración debe estar entre 1 y 5')
            ->maxLength('comentarios', 500, 'Los comentarios no pueden tener más de 500 caracteres');
    }
    
    /**
     * Valida datos de login
     */
    public static function validateLogin($data) {
        $validator = new self($data);
        
        return $validator
            ->required('email', 'El email es obligatorio')
            ->required('password', 'La contraseña es obligatoria')
            ->email('email', 'El formato del email no es válido');
    }
    
    /**
     * Valida datos de registro
     */
    public static function validateRegistro($data) {
        $validator = new self($data);
        
        return $validator
            ->required('nombre', 'El nombre es obligatorio')
            ->required('apellidos', 'Los apellidos son obligatorios')
            ->required('email', 'El email es obligatorio')
            ->required('telefono', 'El teléfono es obligatorio')
            ->required('password', 'La contraseña es obligatoria')
            ->required('confirm_password', 'La confirmación de contraseña es obligatoria')
            ->email('email', 'El formato del email no es válido')
            ->phone('telefono', 'El número de teléfono debe tener 9 dígitos')
            ->minLength('password', 8, 'La contraseña debe tener al menos 8 caracteres')
            ->match('password', 'confirm_password', 'Las contraseñas no coinciden')
            ->maxLength('nombre', 100, 'El nombre no puede tener más de 100 caracteres')
            ->maxLength('apellidos', 100, 'Los apellidos no pueden tener más de 100 caracteres');
    }
    
    /**
     * Valida unicidad de email
     */
    public static function validateUniqueEmail($email, $excludeId = null) {
        require_once 'models/UsuarioModel.php';
        $usuarioModel = new UsuarioModel();
        
        $existingUser = $usuarioModel->findByEmail($email);
        
        if ($existingUser && (!$excludeId || $existingUser['id'] != $excludeId)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Valida contraseña segura
     */
    public static function validateSecurePassword($password) {
        // Al menos 8 caracteres, una mayúscula, una minúscula, un número
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/', $password);
    }
}