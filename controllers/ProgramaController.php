<?php
require_once 'BaseController.php';
require_once 'models/ProgramacionModel.php';
require_once 'models/ProgramaPrecioModel.php';
require_once 'models/PagoModel.php';
require_once 'models/ProgramacionUsuarioModel.php';
require_once 'models/UsuarioModel.php';
require_once 'models/ProgramaCoachingModel.php';
require_once 'models/SuscripcionCoachingModel.php';
require_once 'models/AccesoBloqueModel.php';
require_once 'models/PagoSuscripcionModel.php';
require_once 'config/stripe.php';

class ProgramaController extends BaseController {
    private $programacionModel;
    private $programaPrecioModel;
    private $pagoModel;
    private $programacionUsuarioModel;
    private $usuarioModel;
    private $programaCoachingModel;
    private $suscripcionCoachingModel;
    private $accesoBloqueModel;
    private $pagoSuscripcionModel;
    
    public function __construct() {
        parent::__construct();
        $this->programacionModel = new ProgramacionModel();
        $this->programaPrecioModel = new ProgramaPrecioModel();
        $this->pagoModel = new PagoModel();
        $this->programacionUsuarioModel = new ProgramacionUsuarioModel();
        $this->usuarioModel = new UsuarioModel();
        $this->programaCoachingModel = new ProgramaCoachingModel();
        $this->suscripcionCoachingModel = new SuscripcionCoachingModel();
        $this->accesoBloqueModel = new AccesoBloqueModel();
        $this->pagoSuscripcionModel = new PagoSuscripcionModel();
    }
    
    /**
     * Muestra la tienda unificada de programas (entrenamiento + coaching)
     */
    public function tienda() {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== 'entrenado') {
            $this->redirect('/dashboard');
        }
        
        $usuario_id = $_SESSION['user_id'];
        
        // Obtener programas de entrenamiento
        $programasEntrenamiento = $this->programaPrecioModel->getProgramasConPrecios();
        foreach ($programasEntrenamiento as &$programa) {
            $programa['tipo'] = 'entrenamiento';
            $programa['ya_comprado'] = $this->pagoModel->usuarioYaPago($usuario_id, $programa['id']);
        }
        
        // Obtener programas de coaching
        $programasCoaching = $this->programaCoachingModel->getProgramasActivos();
        foreach ($programasCoaching as &$programa) {
            $programa['tipo'] = 'coaching';
            $programa['ya_comprado'] = $this->suscripcionCoachingModel->usuarioTieneSuscripcionActiva($usuario_id, $programa['id']);
        }
        
        // Combinar ambos tipos de programas
        $todosProgramas = array_merge($programasEntrenamiento, $programasCoaching);
        
        // Obtener categorías para filtros
        $categorias = $this->programaCoachingModel->getCategorias();
        
        $this->render('programas/tienda', [
            'programas' => $todosProgramas,
            'categorias' => $categorias
        ]);
    }
    
    /**
     * Muestra el formulario de compra de un programa
     */
    public function comprar($programa_id) {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== 'entrenado') {
            $this->redirect('/dashboard');
        }
        
        $usuario_id = $_SESSION['user_id'];
        
        // Determinar si es programa de entrenamiento o coaching
        $programaEntrenamiento = $this->programacionModel->findById($programa_id);
        $programaCoaching = $this->programaCoachingModel->findById($programa_id);
        
        if ($programaEntrenamiento) {
            // Es un programa de entrenamiento
            $precio = $this->programaPrecioModel->getPrecioPorPrograma($programa_id);
            
            if (!$precio) {
                $_SESSION['error'] = "Programa no encontrado o no disponible para compra.";
                $this->redirect('/programas/tienda');
            }
            
            if ($this->pagoModel->usuarioYaPago($usuario_id, $programa_id)) {
                $_SESSION['error'] = "Ya has comprado este programa.";
                $this->redirect('/programas/tienda');
            }
            
            $this->render('programas/comprar', [
                'programa' => $programaEntrenamiento,
                'precio' => $precio,
                'tipo' => 'entrenamiento'
            ]);
            
        } elseif ($programaCoaching) {
            // Es un programa de coaching
            if ($this->suscripcionCoachingModel->usuarioTieneSuscripcionActiva($usuario_id, $programa_id)) {
                $_SESSION['error'] = "Ya tienes una suscripción activa a este programa.";
                $this->redirect('/programas/tienda');
            }
            
            $this->render('programas/comprar', [
                'programa' => $programaCoaching,
                'tipo' => 'coaching'
            ]);
            
        } else {
            $_SESSION['error'] = "Programa no encontrado.";
            $this->redirect('/programas/tienda');
        }
    }
    
    /**
     * Crea un Payment Intent de Stripe
     */
    public function crearPaymentIntent() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $programa_id = $input['programa_id'] ?? null;
        $tipo = $input['tipo'] ?? null;
        
        if (!$programa_id || !$tipo) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }
        
        try {
            if ($tipo === 'entrenamiento') {
                $programa = $this->programacionModel->findById($programa_id);
                $precio = $this->programaPrecioModel->getPrecioPorPrograma($programa_id);
                $monto = $precio['precio'] * 100; // Stripe usa centavos
                $descripcion = "Compra de programa: " . $programa['nombre'];
            } else {
                $programa = $this->programaCoachingModel->findById($programa_id);
                $monto = $programa['precio_mensual'] * 100; // Stripe usa centavos
                $descripcion = "Suscripción mensual: " . $programa['nombre'];
            }
            
            if (!$programa) {
                http_response_code(404);
                echo json_encode(['error' => 'Programa no encontrado']);
                return;
            }
            
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $monto,
                'currency' => 'eur',
                'description' => $descripcion,
                'metadata' => [
                    'programa_id' => $programa_id,
                    'tipo' => $tipo,
                    'usuario_id' => $_SESSION['user_id']
                ]
            ]);
            
            echo json_encode([
                'client_secret' => $paymentIntent->client_secret
            ]);
            
        } catch (Exception $e) {
            error_log("Error al crear Payment Intent: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al procesar el pago']);
        }
    }
    
    /**
     * Procesa el pago completado
     */
    public function procesarPago() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $payment_intent_id = $input['payment_intent_id'] ?? null;
        $programa_id = $input['programa_id'] ?? null;
        $tipo = $input['tipo'] ?? null;
        
        if (!$payment_intent_id || !$programa_id || !$tipo) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }
        
        try {
            $usuario_id = $_SESSION['user_id'];
            
            if ($tipo === 'entrenamiento') {
                // Procesar pago de programa de entrenamiento
                $programa = $this->programacionModel->findById($programa_id);
                $precio = $this->programaPrecioModel->getPrecioPorPrograma($programa_id);
                
                $pago_id = $this->pagoModel->crearPago([
                    'usuario_id' => $usuario_id,
                    'programacion_id' => $programa_id,
                    'stripe_payment_intent_id' => $payment_intent_id,
                    'monto' => $precio['precio'],
                    'moneda' => $precio['moneda'],
                    'estado' => 'completado',
                    'fecha_pago' => date('Y-m-d H:i:s')
                ]);
                
                // Asignar programa al usuario
                $this->programacionUsuarioModel->asignarPrograma($usuario_id, $programa_id);
                
            } else {
                // Procesar suscripción de coaching
                $programa = $this->programaCoachingModel->findById($programa_id);
                
                $suscripcion_id = $this->suscripcionCoachingModel->crearSuscripcion([
                    'usuario_id' => $usuario_id,
                    'programa_coaching_id' => $programa_id,
                    'stripe_subscription_id' => $payment_intent_id,
                    'estado' => 'activa',
                    'fecha_inicio' => date('Y-m-d'),
                    'mes_actual' => 1,
                    'ultimo_pago' => date('Y-m-d H:i:s'),
                    'proximo_pago' => date('Y-m-d H:i:s', strtotime('+1 month'))
                ]);
                
                // Crear acceso al primer bloque
                $primer_bloque = $this->accesoBloqueModel->getPrimerBloque($programa_id);
                if ($primer_bloque) {
                    $this->accesoBloqueModel->crearAcceso([
                        'usuario_id' => $usuario_id,
                        'bloque_id' => $primer_bloque['id'],
                        'suscripcion_id' => $suscripcion_id,
                        'desbloqueado' => 1,
                        'fecha_desbloqueo' => date('Y-m-d H:i:s')
                    ]);
                }
                
                // Registrar el pago
                $this->pagoSuscripcionModel->crearPago([
                    'suscripcion_id' => $suscripcion_id,
                    'stripe_payment_intent_id' => $payment_intent_id,
                    'mes' => 1,
                    'monto' => $programa['precio_mensual'],
                    'moneda' => $programa['moneda'],
                    'estado' => 'completado',
                    'fecha_pago' => date('Y-m-d H:i:s')
                ]);
            }
            
            echo json_encode(['success' => true]);
            
        } catch (Exception $e) {
            error_log("Error al procesar pago: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al procesar el pago']);
        }
    }
    
    /**
     * Muestra las compras del usuario
     */
    public function misCompras() {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== 'entrenado') {
            $this->redirect('/dashboard');
        }
        
        $usuario_id = $_SESSION['user_id'];
        
        // Obtener compras de programas de entrenamiento
        $comprasEntrenamiento = $this->pagoModel->getComprasPorUsuario($usuario_id);
        
        // Obtener suscripciones de coaching
        $suscripcionesCoaching = $this->suscripcionCoachingModel->getSuscripcionesPorUsuario($usuario_id);
        
        $this->render('programas/mis-compras', [
            'compras_entrenamiento' => $comprasEntrenamiento,
            'suscripciones_coaching' => $suscripcionesCoaching
        ]);
    }
    
    /**
     * Muestra un programa específico
     */
    public function verPrograma($programa_id) {
        $this->requireAuth();
        
        $usuario_id = $_SESSION['user_id'];
        
        // Determinar tipo de programa
        $programaEntrenamiento = $this->programacionModel->findById($programa_id);
        $programaCoaching = $this->programaCoachingModel->findById($programa_id);
        
        if ($programaEntrenamiento) {
            // Verificar acceso al programa de entrenamiento
            if (!$this->pagoModel->usuarioYaPago($usuario_id, $programa_id)) {
                $_SESSION['error'] = "No tienes acceso a este programa.";
                $this->redirect('/programas/tienda');
            }
            
            $this->render('programas/ver-programa', [
                'programa' => $programaEntrenamiento,
                'tipo' => 'entrenamiento'
            ]);
            
        } elseif ($programaCoaching) {
            // Verificar acceso al programa de coaching
            if (!$this->suscripcionCoachingModel->usuarioTieneSuscripcionActiva($usuario_id, $programa_id)) {
                $_SESSION['error'] = "No tienes acceso a este programa.";
                $this->redirect('/programas/tienda');
            }
            
            $programa = $this->programaCoachingModel->getProgramaConBloques($programa_id);
            $suscripcion = $this->suscripcionCoachingModel->getSuscripcionActiva($usuario_id, $programa_id);
            $accesos = $this->accesoBloqueModel->getAccesosPorSuscripcion($suscripcion['id']);
            
            $this->render('programas/ver-programa', [
                'programa' => $programa,
                'suscripcion' => $suscripcion,
                'accesos' => $accesos,
                'tipo' => 'coaching'
            ]);
            
        } else {
            $_SESSION['error'] = "Programa no encontrado.";
            $this->redirect('/programas/tienda');
        }
    }
    
    /**
     * Muestra un bloque específico de coaching
     */
    public function verBloque($bloque_id) {
        $this->requireAuth();
        
        $usuario_id = $_SESSION['user_id'];
        
        $bloque = $this->accesoBloqueModel->getBloqueConAcceso($bloque_id, $usuario_id);
        
        if (!$bloque || !$bloque['acceso']) {
            $_SESSION['error'] = "No tienes acceso a este bloque.";
            $this->redirect('/programas/mis-compras');
        }
        
        $this->render('programas/ver-bloque', [
            'bloque' => $bloque
        ]);
    }
    
    /**
     * Marca un bloque como completado
     */
    public function completarBloque() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $bloque_id = $input['bloque_id'] ?? null;
        
        if (!$bloque_id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de bloque requerido']);
            return;
        }
        
        try {
            $usuario_id = $_SESSION['user_id'];
            $resultado = $this->accesoBloqueModel->marcarCompletado($bloque_id, $usuario_id);
            
            if ($resultado) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'No se pudo marcar como completado']);
            }
            
        } catch (Exception $e) {
            error_log("Error al completar bloque: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al procesar la solicitud']);
        }
    }
    
    /**
     * Gestiona los precios de programas (solo admin)
     */
    public function gestionarPrecios() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $programas = $this->programacionModel->getAllProgramaciones();
        $precios = $this->programaPrecioModel->getAllPrecios();
        
        $this->render('programas/gestionar-precios', [
            'programas' => $programas,
            'precios' => $precios
        ]);
    }
    
    /**
     * Guarda un precio de programa
     */
    public function guardarPrecio() {
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/programas/gestionar-precios');
        }
        
        $data = [
            'programacion_id' => $_POST['programacion_id'],
            'precio' => (float)$_POST['precio'],
            'moneda' => $_POST['moneda'],
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];
        
        if ($this->programaPrecioModel->guardarPrecio($data)) {
            $_SESSION['success'] = "Precio guardado correctamente.";
        } else {
            $_SESSION['error'] = "Error al guardar el precio.";
        }
        
        $this->redirect('/programas/gestionar-precios');
    }
    
    /**
     * Muestra estadísticas de ventas
     */
    public function estadisticasVentas() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $estadisticas = $this->pagoModel->getEstadisticasVentas();
        
        $this->render('programas/estadisticas-ventas', [
            'estadisticas' => $estadisticas
        ]);
    }
    
    /**
     * Webhook de Stripe
     */
    public function webhookStripe() {
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $endpoint_secret = $_ENV['STRIPE_WEBHOOK_SECRET'];
        
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (Exception $e) {
            http_response_code(400);
            exit();
        }
        
        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $programa_id = $paymentIntent->metadata->programa_id;
            $tipo = $paymentIntent->metadata->tipo;
            $usuario_id = $paymentIntent->metadata->usuario_id;
            
            if ($tipo === 'entrenamiento') {
                // Actualizar estado del pago
                $this->pagoModel->actualizarEstadoPago($paymentIntent->id, 'completado');
                
                // Asignar programa al usuario
                $this->programacionUsuarioModel->asignarPrograma($usuario_id, $programa_id);
                
            } else {
                // Procesar suscripción de coaching
                $programa = $this->programaCoachingModel->findById($programa_id);
                
                $suscripcion_id = $this->suscripcionCoachingModel->crearSuscripcion([
                    'usuario_id' => $usuario_id,
                    'programa_coaching_id' => $programa_id,
                    'stripe_subscription_id' => $paymentIntent->id,
                    'estado' => 'activa',
                    'fecha_inicio' => date('Y-m-d'),
                    'mes_actual' => 1,
                    'ultimo_pago' => date('Y-m-d H:i:s'),
                    'proximo_pago' => date('Y-m-d H:i:s', strtotime('+1 month'))
                ]);
                
                // Crear acceso al primer bloque
                $primer_bloque = $this->accesoBloqueModel->getPrimerBloque($programa_id);
                if ($primer_bloque) {
                    $this->accesoBloqueModel->crearAcceso([
                        'usuario_id' => $usuario_id,
                        'bloque_id' => $primer_bloque['id'],
                        'suscripcion_id' => $suscripcion_id,
                        'desbloqueado' => 1,
                        'fecha_desbloqueo' => date('Y-m-d H:i:s')
                    ]);
                }
                
                // Registrar el pago
                $this->pagoSuscripcionModel->crearPago([
                    'suscripcion_id' => $suscripcion_id,
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'mes' => 1,
                    'monto' => $programa['precio_mensual'],
                    'moneda' => $programa['moneda'],
                    'estado' => 'completado',
                    'fecha_pago' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        http_response_code(200);
    }
    
    protected function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "Acceso denegado. Se requieren permisos de administrador.";
            $this->redirect('/dashboard');
        }
    }
} 