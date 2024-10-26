<?php

$petitionAjax = true;

//* - Includes
require_once "../config/app.php";

if (isset($_POST['empresa_nombre_reg']) || isset($_POST['empresa_id_up'])) {
  require_once "../controllers/companyController.php";
  $ins_company = new companyController();

  //* - Agregar una nueva empresa
  if (isset($_POST['empresa_nombre_reg']) || isset($_POST['empresa_email_reg'])) {
    echo $ins_company->addCompanyController();
  }

  //* - Actualizar una nueva empresa
  if (isset($_POST['empresa_id_up'])) {
    echo $ins_company->updateCompanyController();
  }
} else {
  session_start(['name' => 'LoanC']);
  session_unset();
  session_destroy();
  header('Location: ' . APP_SERVER . 'login/');
  exit();
}

//* - https://www.youtube.com/watch?v=OcJkvzIa9gw
