<style>
body {
  font-family: 'Mukta', sans-serif;
  height: 100vh;
  min-height: 550px;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  position: relative;
  overflow-y: hidden;
}

a {
  text-decoration: none;
  color: #2c7715;
}

.login-reg-panel {
  position: relative;
  top: 50%;
  transform: translateY(-50%);
  text-align: center;
  width: 70%;
  margin: auto;
  height: 400px;
  background-color: rgba(236, 48, 20, 0.9);
}

.white-panel {
  background-color: #fff;
  height: 500px;
  position: absolute;
  top: -50px;
  width: 50%;
  right: calc(50% - 50px);
  transition: 0.3s ease-in-out;
  z-index: 0;
  box-shadow: 0 0 15px 9px #00000096;
}

.right-log {
  right: 50px !important;
}

.login-info-box,
.register-info-box {
  width: 30%;
  padding: 0 50px;
  top: 20%;
  position: absolute;
  text-align: left;
}

.login-info-box {
  left: 0;
}

.register-info-box {
  right: 0;
}

.login-reg-panel input[type="radio"] {
  display: none;
}

.login-reg-panel #label-login,
.login-reg-panel #label-register {
  border: 1px solid #9E9E9E;
  padding: 5px 5px;
  width: 150px;
  display: block;
  text-align: center;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  font-size: 18px;
}

.login-show,
.register-show {
  z-index: 1;
  display: none;
  opacity: 0;
  transition: 0.3s ease-in-out;
  color: #242424;
  text-align: left;
  padding: 50px;
}

.show-log-panel {
  display: block;
  opacity: 0.9;
}

</style>
<?php
if(isset($_GET["sesion"])){
    if($_GET["sesion"] == "false"){
        session_destroy();
    }
}
$error=false;
if(isset($_POST["autenticar"])){
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $admin = new Administrador("", "", "", "", $correo, $clave);
    if($admin -> autenticar()){
        $_SESSION["id"] = $admin -> getId();
        $_SESSION["rol"] = "administrador";
        header("Location: ?pid=" . base64_encode("presentacion/sesionAdministrador.php"));
    }else {
        $propietario = new Propietario("", "", "", "", $correo, $clave);
        if($propietario -> autenticar()){
            $_SESSION["id"] = $propietario -> getId();
            $_SESSION["rol"] = "propietario";
            header("Location: ?pid=" . base64_encode("presentacion/sesionPropietario.php"));
        }else {
        $propietario = new Paseador("", "", "", "", $correo, $clave);
        if($propietario -> autenticar()){
            $_SESSION["id"] = $propietario -> getId();
            $_SESSION["rol"] = "propietario";
            header("Location: ?pid=" . base64_encode("presentacion/sesionPropietario.php"));
        }else{
            $error=true;
        }
    }
}
}
?>
<body>
	<div class="login-reg-panel">
		<div class="login-info-box">
          <h3>¿Ya tienes una cuenta?</h3>
          <p>Inicia sesión para agendar paseos, gestionar tu perfil o continuar con tus servicios.</p>
          <label id="label-register" for="log-reg-show">Iniciar sesión</label>
          <input type="radio" name="active-log-panel" id="log-reg-show" checked="checked">
        </div>							
		<div class="register-info-box">
          <h3>¿Aún no tienes cuenta?</h3>
          <p>Regístrate para encontrar paseadores confiables para tu perrito o para ofrecer tus servicios si eres paseador.</p>
          <label id="label-login" for="log-login-show">Registrarse</label>
          <input type="radio" name="active-log-panel" id="log-login-show">
        </div>							
		<div class="white-panel">
			<div class="login-show">
              <div class="text-center">
                <h2 class="mt-1 mb-4 text-danger fw-bold">Iniciar sesión</h2>
              </div>
            
              <form action="?pid=<?php echo base64_encode('presentacion/autenticar.php') ?>" method="post">
                 <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" id="correo" name="correo" class="form-control" required />
                  </div>
            
                  <div class="mb-3">
                    <label for="clave" class="form-label">Contraseña</label>
                    <input type="password" id="clave" name="clave" class="form-control" required />
                  </div>
            
                <div class="text-center mb-4">
                  <button type="submit" class="btn btn-danger shadow px-4 py-2" name="autenticar">Iniciar sesión</button>
                  <div class="mt-3">
                    <a class="text-muted text-decoration-none" href="?">Regresar</a>
                  </div>
                </div>
              </form>
            
              <?php
                if ($error){
                  echo "<div class='alert alert-danger mt-3' role='alert'>Credenciales incorrectas</div>";
                }
              ?>
            </div>
			<div class="register-show">
				<h2>REGISTER</h2>
				<input type="text" placeholder="Email">
				<input type="password" placeholder="Password">
				<input type="password" placeholder="Confirm Password">
				<input type="button" value="Register">
			</div>
		</div>
	</div>
</body>
<script>
$(document).ready(function(){
    $('.login-info-box').fadeOut();
    $('.login-show').addClass('show-log-panel');
});


$('.login-reg-panel input[type="radio"]').on('change', function() {
    if($('#log-login-show').is(':checked')) {
        $('.register-info-box').fadeOut(); 
        $('.login-info-box').fadeIn();
        
        $('.white-panel').addClass('right-log');
        $('.register-show').addClass('show-log-panel');
        $('.login-show').removeClass('show-log-panel');
        
    }
    else if($('#log-reg-show').is(':checked')) {
        $('.register-info-box').fadeIn();
        $('.login-info-box').fadeOut();
        
        $('.white-panel').removeClass('right-log');
        
        $('.login-show').addClass('show-log-panel');
        $('.register-show').removeClass('show-log-panel');
    }
});
</script>
</html>