<?php
$petitionAjax = true;

//* - Includes
require_once "../config/app.php";

if (isset($_POST['token']) || isset($_POST['user'])) {
  //* - Cerrar Sesion
  require_once "../controllers/loginController.php";
  $ins_login = new loginController();

  echo $ins_login->closeSessionController();
} else {
  session_start(['name' => 'LoanC']);
  session_unset();
  session_destroy();
  header('Location: ' . APP_SERVER . 'login/');
  exit();
}
