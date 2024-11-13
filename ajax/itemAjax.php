<?php

$petitionAjax = true;

//* - Includes
require_once "../config/app.php";

if (isset($_POST['item_codigo_reg'])) {
  require_once "../controllers/itemController.php";
  $ins_item = new itemController();

  //* - Agregar un nuevo Item
  if (isset($_POST['item_codigo_reg']) && isset($_POST['item_nombre_reg'])) {
    echo $ins_item->addItemController();
  }

  //* - Actualizar un nueva Item
} else {
  session_start(['name' => 'LoanC']);
  session_unset();
  session_destroy();
  header('Location: ' . APP_SERVER . 'login/');
  exit();
}
