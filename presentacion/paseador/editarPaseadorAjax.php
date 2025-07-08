<?php
// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión solo si no está ya iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivos necesarios
try {
    $root_path = dirname(dirname(dirname(__FILE__))); // Subir 3 niveles desde paseador/
    require_once($root_path . "/logica/Paseador.php");
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al cargar clases necesarias: ' . $e->getMessage()]);
    exit;
}

// Verificar que la sesión esté activa y el rol sea correcto
if(!isset($_SESSION["rol"]) || $_SESSION["rol"] != "administrador"){
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado - Rol: ' . ($_SESSION["rol"] ?? 'No definido')]);
    exit;
}

if(isset($_POST["crear"])){
    try {
        // Log de datos recibidos para depuración
        error_log("Datos POST recibidos: " . print_r($_POST, true));
        
        // Validar que todos los campos requeridos estén presentes
        $required_fields = ['idP', 'nombre', 'apellido', 'telefono', 'correo', 'tarifa', 'estado'];
        $missing_fields = [];
        
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                $missing_fields[] = $field;
            }
        }
        
        if(!empty($missing_fields)) {
            throw new Exception('Faltan campos requeridos: ' . implode(', ', $missing_fields));
        }
        
        $idPaseador = trim($_POST["idP"]);
        $nombre = trim($_POST["nombre"]);
        $apellido = trim($_POST["apellido"]);
        $telefono = trim($_POST["telefono"]);
        $correo = trim($_POST["correo"]);
        $tarifa = trim($_POST["tarifa"]);
        $estado = intval($_POST["estado"]);
        
        // Log de datos procesados
        error_log("Datos procesados - ID: $idPaseador, Nombre: $nombre, Apellido: $apellido, Telefono: $telefono, Correo: $correo, Tarifa: $tarifa, Estado: $estado");
        
        // Validaciones adicionales
        if(!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El correo electrónico no tiene un formato válido');
        }
        
        if(!is_numeric($tarifa) || floatval($tarifa) < 0) {
            throw new Exception('La tarifa debe ser un número válido mayor o igual a 0');
        }
        
        if(!in_array($estado, [0, 1])) {
            throw new Exception('El estado debe ser 0 (inhabilitado) o 1 (habilitado)');
        }
        
        // Intentar crear y actualizar el paseador
        error_log("Creando objeto Paseador...");
        $paseador = new Paseador($idPaseador, $nombre, $apellido, $telefono, $correo, "", "", $tarifa, $estado);
        
        error_log("Ejecutando editarPerfil...");
        $paseador->editarPerfil();
        
        error_log("Actualización exitosa");
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Información del paseador actualizada exitosamente']);
    } catch (Exception $e) {
        error_log("Error en editarPaseadorAjax: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la información del paseador: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>
