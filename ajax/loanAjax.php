<?php

$petitionAjax = true;

//* - Includes
require_once "../config/app.php";

//** Detectar si se envían datos desde un formulario para la ejecución de los controladores */

if (isset($_POST['seaech_client']) || isset($_POST['add_client_id']) || isset($_POST['delete_client_id']) || isset($_POST['seaech_item']) || isset($_POST['add_item_id']) || isset($_POST['delete_item_id']) || isset($_POST['prestamo_fecha_inicio_reg'])) {
  require_once "../controllers/loanController.php";
  $ins_loan = new loanController();

  //*- prestamo_fecha_inicio_reg

  //* - Buscar un cliente
  if (isset($_POST['seaech_client'])) {
    echo $ins_loan->searchLoanClientController();
  }

  //* - Agregar un cliente al prestamo
  if (isset($_POST['add_client_id'])) {
    echo $ins_loan->addLoanClientController();
  }

  //* - Eliminar un cliente del prestamo
  if (isset($_POST['delete_client_id'])) {
    echo $ins_loan->deleteLoanClientController();
  }

  //* - Buscar un cliente
  if (isset($_POST['seaech_item'])) {
    echo $ins_loan->searchLoanItemController();
  }

  //* - Agregar un item al prestamo
  if (isset($_POST['add_item_id'])) {
    echo $ins_loan->addLoanItemController();
  }

  //* - Eliminar un item del prestamo
  if (isset($_POST['delete_item_id'])) {
    echo $ins_loan->deleteLoanItemController();
  }

  //* - Agregar un prestamops
  if (isset($_POST['prestamo_fecha_inicio_reg'])) {
    echo $ins_loan->addLoanController();
  }
} else {
  session_start(['name' => 'LoanC']);
  session_unset();
  session_destroy();
  header('Location: ' . APP_SERVER . 'login/');
  exit();
}
