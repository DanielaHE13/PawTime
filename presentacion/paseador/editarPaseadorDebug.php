<?php
// Iniciar sesión solo si no está ya iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Desactivar mostrar errores para evitar que interfieran con JSON
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Headers para JSON
header('Content-Type: application/json');

// Log de debugging
error_log("=== INICIO DEBUG EDITAR PASEADOR ===");
error_log("SESSION: " . print_r($_SESSION, true));
error_log("POST: " . print_r($_POST, true));

try {
    // Verificar autorización
    if(!isset($_SESSION["rol"]) || $_SESSION["rol"] != "administrador"){
        error_log("Error de autorización. Rol: " . ($_SESSION["rol"] ?? 'No definido'));
        echo json_encode(['success' => false, 'message' => 'No autorizado - Rol: ' . ($_SESSION["rol"] ?? 'No definido')]);
        exit;
    }

    // Verificar que se enviaron datos del formulario
    if(!isset($_POST["idP"]) || empty($_POST["idP"])){
        error_log("Error: No se envió idP");
        echo json_encode(['success' => false, 'message' => 'ID del paseador no enviado']);
        exit;
    }

    // Incluir archivos necesarios
    error_log("Incluyendo archivos...");
    
    // Cambiar a ruta absoluta desde la raíz del proyecto
    $root_path = dirname(dirname(dirname(__FILE__))); // Subir 3 niveles desde paseador/
    require_once($root_path . "/logica/Paseador.php");
    
    error_log("Archivos incluidos correctamente");

    // Obtener datos del formulario
    $idPaseador = $_POST["idP"] ?? '';
    $nombre = $_POST["nombre"] ?? '';
    $apellido = $_POST["apellido"] ?? '';
    $telefono = $_POST["telefono"] ?? '';
    $correo = $_POST["correo"] ?? '';
    $tarifa = $_POST["tarifa"] ?? '';
    $estado = $_POST["estado"] ?? '';

    error_log("Datos obtenidos - ID: $idPaseador, Nombre: $nombre, Apellido: $apellido, Telefono: $telefono, Correo: $correo, Tarifa: $tarifa, Estado: $estado");

    // Validar datos básicos
    if(empty($idPaseador) || empty($nombre) || empty($apellido) || empty($telefono) || empty($correo) || empty($tarifa) || $estado === ''){
        error_log("Error: Datos incompletos");
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos. Faltan: ' . 
            (empty($idPaseador) ? 'ID ' : '') .
            (empty($nombre) ? 'Nombre ' : '') .
            (empty($apellido) ? 'Apellido ' : '') .
            (empty($telefono) ? 'Telefono ' : '') .
            (empty($correo) ? 'Correo ' : '') .
            (empty($tarifa) ? 'Tarifa ' : '') .
            ($estado === '' ? 'Estado ' : '')
        ]);
        exit;
    }

    // Crear objeto paseador
    error_log("Creando objeto Paseador...");
    $paseador = new Paseador($idPaseador, $nombre, $apellido, $telefono, $correo, "", "", $tarifa, $estado);
    error_log("Objeto Paseador creado");

    // Actualizar perfil
    error_log("Ejecutando editarPerfil...");
    $paseador->editarPerfil();
    error_log("editarPerfil ejecutado correctamente");

    echo json_encode(['success' => true, 'message' => 'Información del paseador actualizada exitosamente']);
    error_log("Respuesta exitosa enviada");

} catch(Exception $e) {
    error_log("Excepción capturada: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage() . ' (Línea: ' . $e->getLine() . ')']);
} catch(Error $e) {
    error_log("Error fatal capturado: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    echo json_encode(['success' => false, 'message' => 'Error fatal: ' . $e->getMessage() . ' (Línea: ' . $e->getLine() . ')']);
}

error_log("=== FIN DEBUG EDITAR PASEADOR ===");
?>
