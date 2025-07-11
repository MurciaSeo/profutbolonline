<?php

class Router {
    private $routes = [];
    private $middlewares = [];
    private $currentRoute = null;
    private $baseUrl = '';
    
    public function __construct($baseUrl = '') {
        $this->baseUrl = rtrim($baseUrl, '/');
    }
    
    /**
     * Registra una ruta GET
     */
    public function get($path, $handler, $middlewares = []) {
        $this->addRoute('GET', $path, $handler, $middlewares);
        return $this;
    }
    
    /**
     * Registra una ruta POST
     */
    public function post($path, $handler, $middlewares = []) {
        $this->addRoute('POST', $path, $handler, $middlewares);
        return $this;
    }
    
    /**
     * Registra una ruta PUT
     */
    public function put($path, $handler, $middlewares = []) {
        $this->addRoute('PUT', $path, $handler, $middlewares);
        return $this;
    }
    
    /**
     * Registra una ruta DELETE
     */
    public function delete($path, $handler, $middlewares = []) {
        $this->addRoute('DELETE', $path, $handler, $middlewares);
        return $this;
    }
    
    /**
     * Registra una ruta para cualquier método HTTP
     */
    public function any($path, $handler, $middlewares = []) {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'];
        foreach ($methods as $method) {
            $this->addRoute($method, $path, $handler, $middlewares);
        }
        return $this;
    }
    
    /**
     * Agrupa rutas con prefijo y middlewares comunes
     */
    public function group($prefix, $middlewares, $callback) {
        $originalPrefix = $this->baseUrl;
        $originalMiddlewares = $this->middlewares;
        
        $this->baseUrl = $originalPrefix . '/' . ltrim($prefix, '/');
        $this->middlewares = array_merge($originalMiddlewares, (array)$middlewares);
        
        call_user_func($callback, $this);
        
        $this->baseUrl = $originalPrefix;
        $this->middlewares = $originalMiddlewares;
        
        return $this;
    }
    
    /**
     * Agrega middleware global
     */
    public function middleware($middleware) {
        $this->middlewares[] = $middleware;
        return $this;
    }
    
    /**
     * Procesa la solicitud actual
     */
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getCurrentUri();
        
        // Buscar ruta coincidente
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchRoute($route['path'], $uri)) {
                $this->currentRoute = $route;
                return $this->executeRoute($route, $uri);
            }
        }
        
        // Ruta no encontrada
        $this->handleNotFound();
    }
    
    /**
     * Agrega una ruta al registro
     */
    private function addRoute($method, $path, $handler, $middlewares = []) {
        $fullPath = $this->baseUrl . '/' . ltrim($path, '/');
        $fullPath = rtrim($fullPath, '/') ?: '/';
        
        $this->routes[] = [
            'method' => $method,
            'path' => $fullPath,
            'handler' => $handler,
            'middlewares' => array_merge($this->middlewares, (array)$middlewares)
        ];
    }
    
    /**
     * Obtiene la URI actual limpia
     */
    private function getCurrentUri() {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remover query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        return rtrim($uri, '/') ?: '/';
    }
    
    /**
     * Verifica si una ruta coincide con la URI
     */
    private function matchRoute($routePath, $uri) {
        // Convertir parámetros dinámicos a regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        
        return preg_match($pattern, $uri);
    }
    
    /**
     * Extrae parámetros de la URI
     */
    private function extractParams($routePath, $uri) {
        $params = [];
        
        // Obtener nombres de parámetros
        preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);
        
        // Obtener valores de parámetros
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Remover match completo
            
            foreach ($paramNames[1] as $index => $name) {
                if (isset($matches[$index])) {
                    $params[$name] = $matches[$index];
                }
            }
        }
        
        return $params;
    }
    
    /**
     * Ejecuta una ruta con sus middlewares
     */
    private function executeRoute($route, $uri) {
        $params = $this->extractParams($route['path'], $uri);
        
        try {
            // Ejecutar middlewares
            foreach ($route['middlewares'] as $middleware) {
                $this->executeMiddleware($middleware, $params);
            }
            
            // Ejecutar handler
            return $this->executeHandler($route['handler'], $params);
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Ejecuta un middleware
     */
    private function executeMiddleware($middleware, $params) {
        if (is_string($middleware)) {
            // Middleware como string de clase
            if (class_exists($middleware)) {
                $instance = new $middleware();
                if (method_exists($instance, 'handle')) {
                    $instance->handle($params);
                }
            }
        } elseif (is_callable($middleware)) {
            // Middleware como función
            call_user_func($middleware, $params);
        }
    }
    
    /**
     * Ejecuta el handler de la ruta
     */
    private function executeHandler($handler, $params) {
        if (is_string($handler)) {
            // Handler como string "Controller@method"
            if (strpos($handler, '@') !== false) {
                list($controller, $method) = explode('@', $handler);
                
                if (class_exists($controller)) {
                    $instance = new $controller();
                    if (method_exists($instance, $method)) {
                        return call_user_func_array([$instance, $method], $params);
                    }
                }
            }
        } elseif (is_callable($handler)) {
            // Handler como función
            return call_user_func_array($handler, $params);
        } elseif (is_array($handler) && count($handler) === 2) {
            // Handler como array [Controller, method]
            list($controller, $method) = $handler;
            
            if (is_object($controller) || class_exists($controller)) {
                $instance = is_object($controller) ? $controller : new $controller();
                if (method_exists($instance, $method)) {
                    return call_user_func_array([$instance, $method], $params);
                }
            }
        }
        
        throw new Exception("Handler no válido para la ruta");
    }
    
    /**
     * Maneja rutas no encontradas
     */
    private function handleNotFound() {
        http_response_code(404);
        
        if (file_exists('views/errors/404.php')) {
            include 'views/errors/404.php';
        } else {
            echo "<h1>404 - Página no encontrada</h1>";
        }
    }
    
    /**
     * Maneja excepciones
     */
    private function handleException($exception) {
        http_response_code(500);
        
        if (file_exists('views/errors/500.php')) {
            include 'views/errors/500.php';
        } else {
            echo "<h1>500 - Error interno del servidor</h1>";
            if (defined('DEBUG') && DEBUG) {
                echo "<pre>" . $exception->getMessage() . "</pre>";
                echo "<pre>" . $exception->getTraceAsString() . "</pre>";
            }
        }
    }
    
    /**
     * Genera URL para una ruta nombrada
     */
    public function url($name, $params = []) {
        // Implementar sistema de rutas nombradas si es necesario
        return $this->baseUrl . '/' . ltrim($name, '/');
    }
    
    /**
     * Redirecciona a una URL
     */
    public function redirect($url, $code = 302) {
        http_response_code($code);
        header("Location: $url");
        exit;
    }
    
    /**
     * Obtiene la ruta actual
     */
    public function getCurrentRoute() {
        return $this->currentRoute;
    }
    
    /**
     * Verifica si la ruta actual coincide con un patrón
     */
    public function is($pattern) {
        if (!$this->currentRoute) {
            return false;
        }
        
        $currentPath = $this->currentRoute['path'];
        return fnmatch($pattern, $currentPath);
    }
    
    /**
     * Obtiene el método HTTP actual
     */
    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Verifica si es una petición AJAX
     */
    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Obtiene todos los parámetros de la petición
     */
    public function getParams() {
        $params = [];
        
        // Parámetros GET
        $params = array_merge($params, $_GET);
        
        // Parámetros POST
        if ($this->getMethod() === 'POST') {
            $params = array_merge($params, $_POST);
        }
        
        // Parámetros JSON para APIs
        if (strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
            $json = json_decode(file_get_contents('php://input'), true);
            if ($json) {
                $params = array_merge($params, $json);
            }
        }
        
        return $params;
    }
    
    /**
     * Obtiene un parámetro específico
     */
    public function getParam($key, $default = null) {
        $params = $this->getParams();
        return $params[$key] ?? $default;
    }
    
    /**
     * Respuesta JSON
     */
    public function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Respuesta de vista
     */
    public function view($view, $data = []) {
        extract($data);
        
        $viewPath = "views/$view.php";
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception("Vista no encontrada: $view");
        }
    }
}

// Funciones helper globales
if (!function_exists('route')) {
    function route($name, $params = []) {
        global $router;
        return $router ? $router->url($name, $params) : $name;
    }
}

if (!function_exists('redirect')) {
    function redirect($url, $code = 302) {
        global $router;
        if ($router) {
            $router->redirect($url, $code);
        } else {
            header("Location: $url");
            exit;
        }
    }
}

if (!function_exists('request')) {
    function request($key = null, $default = null) {
        global $router;
        if ($router) {
            return $key ? $router->getParam($key, $default) : $router->getParams();
        }
        return $key ? ($_REQUEST[$key] ?? $default) : $_REQUEST;
    }
}

if (!function_exists('json_response')) {
    function json_response($data, $code = 200) {
        global $router;
        if ($router) {
            $router->json($data, $code);
        } else {
            http_response_code($code);
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
    }
}