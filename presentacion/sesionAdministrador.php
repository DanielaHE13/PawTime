<?php
if($_SESSION["rol"] != "administrador"){
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
?>
<body>
<?php 
include ("presentacion/encabezado.php");
include ("presentacion/menuAdministrador.php");
?>





</body>
