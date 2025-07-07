<?php
require_once 'BaseController.php';
require_once 'models/EntrenamientoModel.php';
require_once 'models/UsuarioModel.php';
require_once 'models/EjercicioModel.php';
require_once 'models/TipoEjercicioModel.php';

class EntrenamientoController extends BaseController {
    private $entrenamientoModel;
    private $usuarioModel;
    private $ejercicioModel;
    private $tipoModel;
    
    public function __construct() {
        parent::__construct();
        $this->entrenamientoModel = new EntrenamientoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->ejercicioModel = new EjercicioModel();
        $this->tipoModel = new TipoEjercicioModel();
    }
    
    public function index() {
        $this->requireAuth();
        
        $usuario_id = $_SESSION['user_id'];
        $rol = $_SESSION['user_role'];
        
        if ($rol === 'admin') {
            $entrenamientos = $this->entrenamientoModel->getAllEntrenamientos();
        } else if ($rol === 'entrenador') {
            $entrenamientos = $this->entrenamientoModel->getEntrenamientosPorEntrenador($usuario_id);
        } else {
            $entrenamientos = $this->entrenamientoModel->getEntrenamientosPorUsuario($usuario_id);
        }
        
        $this->render('entrenamientos/index', [
            'entrenamientos' => $entrenamientos
        ]);
    }
    
    public function crear() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $tipo = (int)($_POST['tipo'] ?? 0);
            $bloques = $_POST['bloques'] ?? [];

            // Validaciones
            $errores = [];
            if (empty($nombre)) {
                $errores[] = 'El nombre es requerido';
            }
            if (empty($tipo)) {
                $errores[] = 'El tipo de entrenamiento es requerido';
            }
            if (empty($bloques)) {
                $errores[] = 'Debe agregar al menos un bloque con ejercicios';
            }

            // Validar cada bloque y sus ejercicios
            foreach ($bloques as $index => $bloque) {
                $index = (int)$index;
                if (empty($bloque['nombre'])) {
                    $errores[] = "El bloque #" . ($index + 1) . " debe tener un nombre";
                }
                if (empty($bloque['ejercicios'])) {
                    $errores[] = "El bloque #" . ($index + 1) . " debe tener al menos un ejercicio";
                } else {
                    foreach ($bloque['ejercicios'] as $ejIndex => $ejercicio) {
                        $ejIndex = (int)$ejIndex;
                        if (empty($ejercicio['ejercicio_id'])) {
                            $errores[] = "El ejercicio #" . ($ejIndex + 1) . " del bloque #" . ($index + 1) . " debe tener un ejercicio seleccionado";
                        }
                    }
                }
            }

            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['form_data'] = [
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'tipo' => $tipo,
                    'bloques' => $bloques
                ];
                $this->redirect('/entrenamientos/crear');
                return;
            }

            try {
                // Preparar los datos para el modelo
                $data = [
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'tipo' => $tipo,
                    'bloques' => $bloques
                ];

                // Crear el entrenamiento usando el modelo
                if ($this->entrenamientoModel->crearConBloques($data)) {
                    $_SESSION['success'] = 'Entrenamiento creado exitosamente';
                    $this->redirect('/entrenamientos');
                } else {
                    throw new Exception('Error al crear el entrenamiento');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                $_SESSION['form_data'] = [
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'tipo' => $tipo,
                    'bloques' => $bloques
                ];
                $this->redirect('/entrenamientos/crear');
            }
        } else {
            $ejercicios = $this->ejercicioModel->findAll();
            $tipos_ejercicios = $this->tipoModel->getAll();
            $this->render('entrenamientos/crear', [
                'ejercicios' => $ejercicios,
                'tipos_ejercicios' => $tipos_ejercicios
            ]);
        }
    }
    
    public function ver($id) {
        $this->requireAuth();
        
        $entrenamiento = $this->entrenamientoModel->obtenerConDetalles($id);
        
        if (!$entrenamiento) {
            $_SESSION['error'] = 'Entrenamiento no encontrado';
            $this->redirect('/dashboard');
            return;
        }

        // Verificar si el usuario tiene acceso al entrenamiento
        if ($_SESSION['user_role'] === 'entrenado') {
            // Los entrenados solo pueden ver entrenamientos que les han sido asignados
            $tiene_acceso = $this->entrenamientoModel->verificarAccesoUsuario($id, $_SESSION['user_id']);
            if (!$tiene_acceso) {
                $_SESSION['error'] = 'No tienes acceso a este entrenamiento. Contacta a tu entrenador para que te asigne este entrenamiento.';
                $this->redirect('/dashboard');
                return;
            }
        } elseif ($_SESSION['user_role'] === 'entrenador') {
            // Los entrenadores pueden ver todos los entrenamientos
            // No se requiere verificación adicional
        } elseif ($_SESSION['user_role'] === 'admin') {
            // Los administradores pueden ver todos los entrenamientos
            // No se requiere verificación adicional
        } else {
            // Rol no reconocido
            $_SESSION['error'] = 'No tienes permisos para acceder a entrenamientos';
            $this->redirect('/dashboard');
            return;
        }
        
        // Obtener información de la sesión del usuario para este entrenamiento
        $sql = "SELECT s.fecha, s.fecha_completado, s.completado, s.valorado 
                FROM sesiones s 
                WHERE s.entrenamiento_id = ? AND s.usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $_SESSION['user_id']]);
        $sesion = $stmt->fetch(PDO::FETCH_ASSOC);
    
        
        $data = [
            'entrenamiento' => $entrenamiento,
            'sesion' => $sesion
        ];
        
        // Si es entrenado, obtener datos de valoración
        if ($_SESSION['user_role'] === 'entrenado' && $sesion) {
            $sql = "SELECT v.*, s.valorado 
                    FROM sesiones s 
                    LEFT JOIN valoraciones_entrenamientos v ON s.entrenamiento_id = v.entrenamiento_id 
                        AND s.usuario_id = v.usuario_id 
                        AND s.id = v.sesion_id
                    WHERE s.entrenamiento_id = ? AND s.usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id, $_SESSION['user_id']]);
            $valoracion = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($valoracion) {
                $data['valoracion'] = $valoracion;
            }
        }
        
        $this->render('entrenamientos/ver', $data);
    }
    
    public function editar($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Preparar los datos del entrenamiento
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'] ?? '',
                'tipo' => (int)($_POST['tipo'] ?? 0),
                'bloques' => []
            ];
            
            // Procesar los bloques
            if (isset($_POST['bloques']) && is_array($_POST['bloques'])) {
                foreach ($_POST['bloques'] as $index => $bloque) {
                    if (!empty($bloque['nombre'])) {
                        $data['bloques'][] = [
                            'nombre' => $bloque['nombre'],
                            'descripcion' => $bloque['descripcion'] ?? '',
                            'serie' => !empty($bloque['serie']) ? (int)$bloque['serie'] : 1,
                            'ejercicios' => []
                        ];
                        
                        // Procesar los ejercicios del bloque
                        if (isset($bloque['ejercicios']) && is_array($bloque['ejercicios'])) {
                            foreach ($bloque['ejercicios'] as $ejIndex => $ejercicio) {
                                if (!empty($ejercicio['ejercicio_id'])) {
                                    $data['bloques'][$index]['ejercicios'][] = [
                                        'ejercicio_id' => $ejercicio['ejercicio_id'],
                                        'tipo_configuracion' => $ejercicio['tipo_configuracion'] ?? 'repeticiones',
                                        'tiempo' => !empty($ejercicio['tiempo']) ? (int)$ejercicio['tiempo'] : null,
                                        'repeticiones' => !empty($ejercicio['repeticiones']) ? (int)$ejercicio['repeticiones'] : null,
                                        'tiempo_descanso' => !empty($ejercicio['tiempo_descanso']) ? (int)$ejercicio['tiempo_descanso'] : null,
                                        'peso_kg' => !empty($ejercicio['peso_kg']) ? (float)$ejercicio['peso_kg'] : null,
                                        'repeticiones_por_hacer' => !empty($ejercicio['repeticiones_por_hacer']) ? (int)$ejercicio['repeticiones_por_hacer'] : null
                                    ];
                                }
                            }
                        }
                    }
                }
            }
            
            // Actualizar el entrenamiento
            if ($this->entrenamientoModel->actualizarConBloques($id, $data)) {
                $_SESSION['success'] = 'Entrenamiento actualizado exitosamente';
                $this->redirect('/entrenamientos');
            } else {
                $_SESSION['error'] = 'Error al actualizar el entrenamiento';
                $this->redirect('/entrenamientos/editar/' . $id);
            }
        } else {
            $entrenamiento = $this->entrenamientoModel->findById($id);
            if (!$entrenamiento) {
                $_SESSION['error'] = 'Entrenamiento no encontrado';
                $this->redirect('/entrenamientos');
                return;
            }
            
            $ejercicios = $this->ejercicioModel->findAll();
            $tipos_ejercicios = $this->tipoModel->getAll();
            $this->render('entrenamientos/editar', [
                'entrenamiento' => $entrenamiento,
                'ejercicios' => $ejercicios,
                'tipos_ejercicios' => $tipos_ejercicios
            ]);
        }
    }
    
    public function eliminar($id) {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== 'entrenador' && $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "No tienes permisos para eliminar entrenamientos";
            $this->redirect('/dashboard');
        }
        
        $entrenamiento = $this->entrenamientoModel->findById($id);
        
        if (!$entrenamiento) {
            $_SESSION['error'] = "El entrenamiento no existe";
            $this->redirect('/entrenamientos');
        }
        
        if ($this->entrenamientoModel->delete($id)) {
            $_SESSION['success'] = "Entrenamiento eliminado correctamente";
            $this->redirect('/entrenamientos');
        } else {
            $_SESSION['error'] = "Error al eliminar el entrenamiento";
            $this->redirect('/entrenamientos');
        }
    }

    /**
     * Marca un entrenamiento como completado
     */
    public function completar($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['user_id'];
        
        // Buscar la sesión del usuario para este entrenamiento
        $sql = "SELECT s.id as sesion_id, s.completado, e.nombre 
                FROM sesiones s 
                JOIN entrenamientos e ON s.entrenamiento_id = e.id 
                WHERE s.entrenamiento_id = ? AND s.usuario_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $usuario_id]);
        $sesion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sesion) {
            $_SESSION['error'] = "No tienes acceso a este entrenamiento o no está asignado";
            header('Location: /entrenamientos');
            exit;
        }

        if ($sesion['completado']) {
            $_SESSION['error'] = "Este entrenamiento ya ha sido completado";
            header('Location: /entrenamientos');
            exit;
        }

        // Marcar la sesión como completada
        $sql = "UPDATE sesiones SET completado = TRUE, fecha_completado = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute([$sesion['sesion_id']])) {
            $_SESSION['success'] = "¡Entrenamiento completado con éxito!";
            // Redirigir a la valoración
            header('Location: /entrenamientos/valorar/' . $id);
            exit;
        } else {
            $_SESSION['error'] = "Error al marcar el entrenamiento como completado";
            header('Location: /entrenamientos/ver/' . $id);
            exit;
        }
    }

    /**
     * Muestra el formulario de valoración
     */
    public function valorar($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['user_id'];
        
        // Buscar la sesión del usuario para este entrenamiento
        $sql = "SELECT s.id as sesion_id, s.completado, e.nombre, e.id as entrenamiento_id
                FROM sesiones s 
                JOIN entrenamientos e ON s.entrenamiento_id = e.id 
                WHERE s.entrenamiento_id = ? AND s.usuario_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $usuario_id]);
        $sesion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sesion) {
            $_SESSION['error'] = "No tienes acceso a este entrenamiento";
            header('Location: /entrenamientos');
            exit;
        }

        if (!$sesion['completado']) {
            $_SESSION['error'] = "Debes completar el entrenamiento antes de valorarlo";
            header('Location: /entrenamientos/ver/' . $id);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar que se hayan enviado todas las valoraciones requeridas
            if (!isset($_POST['valoracion']['esfuerzo']) || !isset($_POST['valoracion']['calidad']) || !isset($_POST['valoracion']['videos'])) {
                $_SESSION['error'] = "Debes completar todas las valoraciones";
                $this->render('entrenamientos/valorar', [
                    'entrenamiento' => ['id' => $sesion['entrenamiento_id'], 'nombre' => $sesion['nombre']]
                ]);
                return;
            }

            $valoracion = [
                'esfuerzo' => (int)$_POST['valoracion']['esfuerzo'],
                'calidad' => (int)$_POST['valoracion']['calidad'],
                'videos' => (int)$_POST['valoracion']['videos'],
                'comentarios' => $_POST['valoracion']['comentarios'] ?? ''
            ];

            // Insertar la valoración en la tabla valoraciones_entrenamientos
            $sql = "INSERT INTO valoraciones_entrenamientos 
                    (entrenamiento_id, usuario_id, dia_id, sesion_id, calidad, esfuerzo, complejidad, duracion, comentarios) 
                    VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            if ($stmt->execute([
                $sesion['entrenamiento_id'],
                $usuario_id,
                $sesion['sesion_id'],
                $valoracion['calidad'],
                $valoracion['esfuerzo'], 
                $valoracion['videos'], // usando videos como complejidad
                $valoracion['esfuerzo'], // usando esfuerzo como duración
                $valoracion['comentarios']
            ])) {
                // Marcar la sesión como valorada
                $sql = "UPDATE sesiones SET valorado = 1 WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$sesion['sesion_id']]);
                
                $_SESSION['success'] = "¡Gracias por tu valoración!";
                header('Location: /entrenamientos');
                exit;
            } else {
                $_SESSION['error'] = "Error al guardar la valoración";
                $this->render('entrenamientos/valorar', [
                    'entrenamiento' => ['id' => $sesion['entrenamiento_id'], 'nombre' => $sesion['nombre']]
                ]);
                return;
            }
        }

        $this->render('entrenamientos/valorar', [
            'entrenamiento' => ['id' => $sesion['entrenamiento_id'], 'nombre' => $sesion['nombre']]
        ]);
    }

    public function descargarPDF($id) {
        if (!$this->isAuthenticated()) {
            $_SESSION['error'] = "Debe iniciar sesión para acceder a esta sección.";
            $this->redirect('/auth/login');
            return;
        }

        $entrenamiento = $this->entrenamientoModel->obtenerConDetalles($id);
        
        if (!$entrenamiento) {
            $_SESSION['error'] = "Entrenamiento no encontrado.";
            $this->redirect('/entrenamientos');
            return;
        }

        // Verificar permisos según el rol
        if ($_SESSION['user_role'] === 'entrenado') {
            // Los entrenados solo pueden descargar entrenamientos que les han sido asignados
            $tiene_acceso = $this->entrenamientoModel->verificarAccesoUsuario($id, $_SESSION['user_id']);
            if (!$tiene_acceso) {
                $_SESSION['error'] = "No tienes acceso a este entrenamiento. Contacta a tu entrenador para que te asigne este entrenamiento.";
                $this->redirect('/entrenamientos');
                return;
            }
        } elseif ($_SESSION['user_role'] === 'entrenador') {
            // Los entrenadores pueden descargar todos los entrenamientos
            // No se requiere verificación adicional
        } elseif ($_SESSION['user_role'] === 'admin') {
            // Los administradores pueden descargar todos los entrenamientos
            // No se requiere verificación adicional
        } else {
            // Rol no reconocido
            $_SESSION['error'] = "No tienes permisos para acceder a entrenamientos";
            $this->redirect('/entrenamientos');
            return;
        }

        // Crear PDF
        require_once 'libs/tcpdf/tcpdf.php';
        
        // Crear nuevo documento PDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Configurar documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sistema de Entrenamiento');
        $pdf->SetTitle('Entrenamiento: ' . $entrenamiento['nombre']);
        
        // Configurar márgenes
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Configurar fuente
        $pdf->SetFont('helvetica', '', 11);
        
        // Agregar página
        $pdf->AddPage();
        
        // Estilos CSS mejorados
        $css = '
            <style>
                body {
                    font-family: helvetica;
                    font-size: 14pt;
                    color: #222;
                    margin: 0;
                    padding: 20px;
                }
                .logo {
                    text-align: center;
                    margin-bottom: 25px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    background-color: #1e40af;
                    padding: 25px;
                    border-radius: 10px;
                }
                .title {
                    color: #ffffff;
                    font-size: 28pt;
                    font-weight: bold;
                    margin-bottom: 8px;
                }
                .subtitle {
                    color: #93c5fd;
                    font-size: 18pt;
                    margin-bottom: 12px;
                }
                .date {
                    color: #cbd5e1;
                    font-size: 12pt;
                    margin-bottom: 12px;
                }
                .description {
                    margin-bottom: 20px;
                    font-size: 14pt;
                    background-color: #1e40af;
                    padding: 18px;
                    border-radius: 8px;
                    color: #ffffff;
                }
                .block {
                    margin-bottom: 30px;
                    padding: 20px 18px 18px 18px;
                    background: #1e40af;
                    border-radius: 8px;
                    border: 1px solid #3b82f6;
                }
                .block-title {
                    font-size: 17pt;
                    color: #ffffff;
                    font-weight: bold;
                    margin-bottom: 8px;
                }
                .block-series {
                    font-size: 13pt;
                    color: #93c5fd;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                .block-description {
                    font-size: 13pt;
                    color: #cbd5e1;
                    margin-bottom: 12px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 12px 0 0 0;
                }
                th {
                    background-color: #3b82f6;
                    color: #ffffff;
                    font-weight: bold;
                    font-size: 13pt;
                    padding: 10px 6px;
                    border-bottom: 2px solid #60a5fa;
                    text-align: left;
                }
                td {
                    padding: 10px 6px;
                    font-size: 13pt;
                    border-bottom: 1px solid #3b82f6;
                    background-color: #1e40af;
                    color: #ffffff;
                }
                .footer {
                    color: #93c5fd;
                    font-size: 12pt;
                    text-align: center;
                    margin-top: 35px;
                    background-color: #1e40af;
                    padding: 18px;
                    border-radius: 8px;
                }
            </style>
        ';
        
        // Contenido HTML
        $html = $css;
        
        // Logo
        $html .= '<div class="logo"><img src="https://profutbolonline.com/img/logo.png" height="60"></div>';
        
        // Encabezado
        $html .= '
            <div class="header">
                <h1 class="title">' . htmlspecialchars($entrenamiento['nombre']) . '</h1>
                <h2 class="subtitle">Plan de Entrenamiento</h2>
                <div class="date">Generado el ' . date('d/m/Y H:i') . '</div>
            </div>
        ';
        
        // Descripción
        if (!empty($entrenamiento['descripcion'])) {
            $html .= '<div class="description">' . nl2br(htmlspecialchars($entrenamiento['descripcion'])) . '</div>';
        }
        
        // Contenido de bloques
        foreach ($entrenamiento['bloques'] as $bloque) {
            $html .= '<div class="block">';
            $html .= '<div class="block-title">Bloque ' . $bloque['orden'] . ': ' . htmlspecialchars($bloque['nombre']) . '</div>';
            $html .= '<div class="block-series">Series: ' . (isset($bloque['serie']) ? $bloque['serie'] : 1) . '</div>';
            if (!empty($bloque['descripcion'])) {
                $html .= '<div class="block-description">' . nl2br(htmlspecialchars($bloque['descripcion'])) . '</div>';
            }
            if (!empty($bloque['ejercicios'])) {
                // Ordenar ejercicios por orden
                usort($bloque['ejercicios'], function($a, $b) {
                    return $a['orden'] - $b['orden'];
                });
                $html .= '<table>
                    <tr>
                        <th width="40%">Ejercicio</th>
                        <th width="15%">Repeticiones</th>
                        <th width="15%">Tiempo</th>
                        <th width="15%">Descanso</th>
                    </tr>';
                foreach ($bloque['ejercicios'] as $ejercicio) {
                    $html .= '<tr>
                        <td>' . htmlspecialchars($ejercicio['nombre']) . '</td>
                        <td>' . ($ejercicio['repeticiones'] ?? '-') . '</td>
                        <td>' . ($ejercicio['tiempo'] ? $ejercicio['tiempo'] . ' seg' : '-') . '</td>
                        <td>' . ($ejercicio['tiempo_descanso'] ? $ejercicio['tiempo_descanso'] . ' seg' : '-') . '</td>
                    </tr>';
                }
                $html .= '</table>';
            }
            $html .= '</div>';
        }
        
        // Pie de página
        $html .= '
            <div class="footer">
                Sistema de Entrenamiento Online - Impulso
            </div>
        ';
        
        // Escribir contenido
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Generar PDF
        $pdf->Output('entrenamiento_' . $id . '.pdf', 'D');
    }

    public function asignar($usuario_id) {
        $this->requireAuth();
        
        // Verificar que el usuario tiene permiso para asignar entrenamientos
        if ($_SESSION['user_role'] === 'entrenador') {
            // Si es entrenador, solo puede asignar a sus entrenados
            $entrenados = $this->usuarioModel->getEntrenadosPorEntrenador($_SESSION['user_id']);
            $puede_asignar = false;
            foreach ($entrenados as $entrenado) {
                if ($entrenado['id'] == $usuario_id) {
                    $puede_asignar = true;
                    break;
                }
            }
            if (!$puede_asignar) {
                $this->redirect('/dashboard');
            }
        } elseif ($_SESSION['user_role'] !== 'admin') {
            // Si no es admin ni entrenador, redirigir al dashboard
            $this->redirect('/dashboard');
        }
        
        // Obtener el usuario
        $usuario = $this->usuarioModel->findById($usuario_id);
        if (!$usuario) {
            $this->redirect('/dashboard');
        }
        
        // Obtener todos los entrenamientos disponibles
        $entrenamientos = $this->entrenamientoModel->findAll();
        
        // Obtener los entrenamientos ya asignados al usuario
        $entrenamientos_asignados = $this->entrenamientoModel->getEntrenamientosPorUsuario($usuario_id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrenamiento_id = $_POST['entrenamiento_id'];
            $fecha = $_POST['fecha'];
            
            if ($this->entrenamientoModel->asignarEntrenamiento($entrenamiento_id, $usuario_id, $fecha)) {
                $_SESSION['success'] = "Entrenamiento asignado correctamente.";
                $this->redirect('/usuarios/perfil/' . $usuario_id);
            } else {
                $_SESSION['error'] = "Error al asignar el entrenamiento.";
            }
        }
        
        $this->render('entrenamientos/asignar', [
            'usuario' => $usuario,
            'entrenamientos' => $entrenamientos,
            'entrenamientos_asignados' => $entrenamientos_asignados
        ]);
    }

    public function asignarRapido() {
        $this->requireAuth();
        
        // Verificar que el usuario tiene permiso para asignar entrenamientos
        if ($_SESSION['user_role'] === 'entrenador') {
            // Si es entrenador, solo puede asignar a sus entrenados
            $usuarios = $this->usuarioModel->getEntrenadosPorEntrenador($_SESSION['user_id']);
        } elseif ($_SESSION['user_role'] === 'admin') {
            // Si es admin, puede asignar a todos los entrenados
            $usuarios = $this->usuarioModel->getUsuariosPorRol('entrenado');
        } else {
            // Si no es entrenador ni admin, redirigir al dashboard
            $_SESSION['error'] = 'No tienes permisos para asignar entrenamientos';
            $this->redirect('/dashboard');
            return;
        }
        
        // Obtener todos los entrenamientos disponibles
        $entrenamientos = $this->entrenamientoModel->findAll();
        
        // Obtener asignaciones recientes
        $asignaciones_recientes = $this->entrenamientoModel->getAsignacionesRecientes(10);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_POST['usuario_id'] ?? null;
            $entrenamiento_id = $_POST['entrenamiento_id'] ?? null;
            $fecha = $_POST['fecha'] ?? null;
            
            if (!$usuario_id || !$entrenamiento_id || !$fecha) {
                $_SESSION['error'] = 'Todos los campos son obligatorios';
            } else {
                // Verificar que el usuario existe y es entrenado
                $usuario = $this->usuarioModel->findById($usuario_id);
                if (!$usuario || $usuario['rol'] !== 'entrenado') {
                    $_SESSION['error'] = 'Usuario no válido';
                } else {
                    // Verificar que el entrenamiento existe
                    $entrenamiento = $this->entrenamientoModel->findById($entrenamiento_id);
                    if (!$entrenamiento) {
                        $_SESSION['error'] = 'Entrenamiento no válido';
                    } else {
                        // Verificar que no esté ya asignado
                        $ya_asignado = $this->entrenamientoModel->verificarAccesoUsuario($entrenamiento_id, $usuario_id);
                        if ($ya_asignado) {
                            $_SESSION['error'] = 'Este entrenamiento ya está asignado al usuario';
                        } else {
                            // Asignar el entrenamiento
                            if ($this->entrenamientoModel->asignarEntrenamiento($entrenamiento_id, $usuario_id, $fecha)) {
                                $_SESSION['success'] = 'Entrenamiento asignado correctamente';
                                $this->redirect('/entrenamientos/asignar-rapido');
                                return;
                            } else {
                                $_SESSION['error'] = 'Error al asignar el entrenamiento';
                            }
                        }
                    }
                }
            }
        }
        
        $this->render('entrenamientos/asignar_rapido', [
            'usuarios' => $usuarios,
            'entrenamientos' => $entrenamientos,
            'asignaciones_recientes' => $asignaciones_recientes
        ]);
    }
} 