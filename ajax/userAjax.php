<?php

$petitionAjax = true;

//* - Includes
require_once "../config/app.php";

//** Detectar si se envían datos desde un formulario para la ejecución de los controladores */

if (isset($_POST['usuario_dni_reg']) || isset($_POST['usuario_id_del']) || isset($_POST['usuario_id_up'])) { //*Sí, se envía desde un formulario
  require_once "../controllers/userController.php";
  $ins_user = new userController();

  //* - Agregar un nuevo usuario
  if (isset($_POST['usuario_dni_reg']) && isset($_POST['usuario_nombre_reg'])) {
    echo $ins_user->addUserController();
  }

  //* - Eliminar un usuario existente
  if (isset($_POST['usuario_id_del'])) {
    echo $ins_user->deleteUserController();
  }

  //* - Actualizar un usuario existente
  if (isset($_POST['usuario_id_up'])) {
    echo $ins_user->updateUserController();
  }
} else { //* Si se accede al archivo Ajax desde el navegador
  session_start(['name' => 'LoanC']);
  session_unset();
  session_destroy();
  header('Location: ' . APP_SERVER . 'login/');
  exit();
}
