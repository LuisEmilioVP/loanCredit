<?php
//* - Detectar la petición ajax o no
if ($petitionAjax) {
  require_once "../models/loginModel.php";
} else {
  require_once "./models/loginModel.php";
}

class loginController extends loginModel
{
  /** ---------- Controlador: Iniciar Sesion ---------- **/
  public function loginController()
  {
    $user = mainModel::cleanData($_POST['user_log']);
    $pass = mainModel::cleanData($_POST['pass_log']);

    //* - Verificar campos obligatorios
    if ($user == "" || $pass == "") {
      echo '
        <script>
          Swal.fire({
            title: "Ocurrio un error inesperado",
						text: "No has llenado todos los campos que son requeridos",
						type: "error",
						confirmButtonText: "Aceptar"
          });
        </script>
      ';
      exit();
    }

    //* - Verificación - Integridad de los datos
    if (mainModel::verifyData("[a-zA-Z0-9]{3,35}", $user)) {
      echo '
        <script>
          Swal.fire({
            title: "Ocurrio un error inesperado",
						text: "El Nombre de Usuario no coincide con el formato solicitado.",
						type: "error",
						confirmButtonText: "Aceptar"
          });
        </script>
      ';
      exit();
    }

    if (mainModel::verifyData("[a-zA-Z0-9$@.\-]{7,100}", $pass)) {
      echo '
        <script>
          Swal.fire({
            title: "Ocurrio un error inesperado",
						text: "La Contraseña no coincide con el formato solicitado.",
						type: "error",
						confirmButtonText: "Aceptar"
          });
        </script>
      ';
      exit();
    }

    $pass = mainModel::encryption($pass);

    $data_login = [
      "user" => $user,
      "pass" => $pass
    ];

    $login_sesion = loginModel::loginModel($data_login);

    if ($login_sesion->rowCount() == 1) {
      $row = $login_sesion->fetch();
      session_start(['name' => 'LoanC']);

      $_SESSION['id_spm'] = $row['usuario_id'];
      $_SESSION['name_spm'] = $row['usuario_nombre'];
      $_SESSION['lastname_spm'] = $row['usuario_apellido'];
      $_SESSION['user_spm'] = $row['usuario_usuario'];
      $_SESSION['role_spm'] = $row['usuario_privilegio'];
      $_SESSION['token_spm'] = md5(uniqid(mt_rand(), true));

      return header("Location: " . APP_SERVER . "home/");
    } else {
      echo '
				<script>
					Swal.fire({
						title: "Ocurrio un error inesperado",
						text: "El Usuario o Contraseña son incorrectos",
						type: "error",
						confirmButtonText: "Aceptar"
					});
				</script>
				';
      exit();
    }
  } //* - Fin Controlador: Iniciar Sesion

  /** ---------- Controlador: Forzar Cierre de Sesion ---------- **/
  public function logoutController()
  {
    session_unset();
    session_destroy();
    if (headers_sent()) {
      return "<script> window.location.href = '" . APP_SERVER . "login/' </script>";
    } else {
      return header("Location: " . APP_SERVER . "login/");
    }
  } //* - Fin Controlador: Forzar Cierre de Sesion

  /** ---------- Controlador: Cierre de Sesion ---------- **/
  public function closeSessionController()
  {
    session_start(['name' => 'LoanC']);
    $token = mainModel::decryption($_POST['token']);
    $user = mainModel::decryption($_POST['user']);

    if ($token == $_SESSION['token_spm'] && $user == $_SESSION['user_spm']) {
      session_unset();
      session_destroy();
      $alert = [
        "Alerts" => "redirect",
        "URL" => APP_SERVER . "login/",
      ];
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No se ha podido cerrar la sesión",
        "Tipe" => "error"
      ];
    }
    echo json_encode($alert);
  } //* - Fin Controlador: Cierre de Sesion
}
