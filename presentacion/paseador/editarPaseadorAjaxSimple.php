<?php
// Iniciar sesión solo si no está ya iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Desactivar mostrar errores para evitar que interfieran con JSON
error_reporting(0);
ini_set('display_errors', 0);

// Headers para JSON
header('Content-Type: application/json');

// Verificar autorización
if(!isset($_SESSION["rol"]) || $_SESSION["rol"] != "administrador"){
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Verificar que se enviaron datos del formulario (al menos el ID del paseador)
if(!isset($_POST["idP"]) || empty($_POST["idP"])){
    echo json_encode(['success' => false, 'message' => 'Datos del formulario incompletos']);
    exit;
}

try {
    // Incluir archivos necesarios - usar ruta absoluta
    $root_path = dirname(dirname(dirname(__FILE__))); // Subir 3 niveles desde paseador/
    require_once($root_path . "/logica/Paseador.php");
    
    // Obtener datos del formulario
    $idPaseador = $_POST["idP"] ?? '';
    $nombre = $_POST["nombre"] ?? '';
    $apellido = $_POST["apellido"] ?? '';
    $telefono = $_POST["telefono"] ?? '';
    $correo = $_POST["correo"] ?? '';
    $tarifa = $_POST["tarifa"] ?? '';
    $estado = $_POST["estado"] ?? '';
    
    // Validar datos básicos
    if(empty($idPaseador) || empty($nombre) || empty($apellido) || empty($telefono) || empty($correo) || empty($tarifa) || $estado === ''){
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
        exit;
    }
    
    // Crear objeto paseador y actualizar
    $paseador = new Paseador($idPaseador, $nombre, $apellido, $telefono, $correo, "", "", $tarifa, $estado);
    $paseador->editarPerfil();
    
    echo json_encode(['success' => true, 'message' => 'Información del paseador actualizada exitosamente']);
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
