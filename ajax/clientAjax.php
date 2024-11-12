<?php

$petitionAjax = true;

//* - Includes
require_once "../config/app.php";

//** Detectar si se envían datos desde un formulario para la ejecución de los controladores */

if (isset($_POST['cliente_dni_reg']) || isset($_POST['cliente_id_del']) || isset($_POST['cliente_id_up'])) {
  require_once "../controllers/clientController.php";
  $ins_client = new clientController();

  //* - Agregar un nuevo cliente
  if (isset($_POST['cliente_dni_reg']) && isset($_POST['cliente_nombre_reg'])) {
    echo $ins_client->addClientController();
  }

  //* - Eliminar un cliente existente
  if (isset($_POST['cliente_id_del'])) {
    echo $ins_client->deleteClientController();
  }

  //* - Actualizar un cliente existente
  if (isset($_POST['cliente_id_up'])) {
    echo $ins_client->updateClientController();
  }
} else {
  session_start(['name' => 'LoanC']);
  session_unset();
  session_destroy();
  header('Location: ' . APP_SERVER . 'login/');
  exit();
}
