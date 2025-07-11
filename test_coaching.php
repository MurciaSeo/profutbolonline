<?php
session_start();
require_once 'config/database.php';
require_once 'models/ProgramaCoachingModel.php';

// Habilitar errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test del Sistema de Coaching</h1>";

try {
    // Probar conexión a la base de datos
    $db = Database::getInstance();
    $connection = $db->getConnection();
    echo "<p>✅ Conexión a la base de datos: OK</p>";
    
    // Probar modelo de programas
    $programaModel = new ProgramaCoachingModel();
    $programas = $programaModel->getProgramasActivos();
    
    echo "<p>✅ Modelo ProgramaCoachingModel: OK</p>";
    echo "<p>Programas encontrados: " . count($programas) . "</p>";
    
    if (count($programas) > 0) {
        echo "<h2>Programas disponibles:</h2>";
        echo "<ul>";
        foreach ($programas as $programa) {
            echo "<li>{$programa['nombre']} - {$programa['precio_mensual']}€/mes</li>";
        }
        echo "</ul>";
    }
    
    // Probar categorías
    $categorias = $programaModel->getCategorias();
    echo "<p>Categorías disponibles: " . implode(', ', $categorias) . "</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Archivo: " . $e->getFile() . "</p>";
    echo "<p>Línea: " . $e->getLine() . "</p>";
}
?> 