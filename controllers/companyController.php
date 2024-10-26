<?php
//* - Detectar la petición ajax o no
if ($petitionAjax) {
  require_once "../models/companyModel.php";
} else {
  require_once "./models/companyModel.php";
}

class companyController extends companyModel
{
  /** ---------- Controlador: Seleccionar Datos de Empresa ---------- **/
  public function selectCompanyController()
  {
    return companyModel::selectCompanyModel();
  } //* - Fin controlador seleccionar datos de empresa

  /** ---------- Controlador: Agregar Empresa ---------- **/
  public function addCompanyController()
  {
    //* - Recibir datos del formu y limpiarlos
    $nombre = mainModel::cleanData($_POST['empresa_nombre_reg']);
    $email = mainModel::cleanData($_POST['empresa_email_reg']);
    $telefono = mainModel::cleanData($_POST['empresa_telefono_reg']);
    $direccion = mainModel::cleanData($_POST['empresa_direccion_reg']);

    //* - Verificar campos obligatorios
    if ($nombre == "" || $email == "" || $telefono == "" || $direccion == "") {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No han llenado todos los campos que son requeridos",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificación - Integridad de los datos
    if (mainModel::verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,70}", $nombre)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Nombre de la Empresa no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[0-9\(\)\+ ]{14,20}", $telefono)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Teléfono no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\(\).,#\- ]{6,190}", $direccion)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "La Dirección no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "Ha ingresado un correo no válido.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificar el empresa en la DB
    $check_company = mainModel::simpleQuery("SELECT empresa_id FROM empresa");

    if ($check_company->rowCount() >= 1) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "La Empresa ya existe en el sistema. No puedes registrar una nueva Empresa.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Cargar datos de la empresa
    $data_company = [
      "nombre" => $nombre,
      "email" => $email,
      "telefono" => $telefono,
      "direccion" => $direccion,
    ];

    //* - Agregar empresa
    $add_company = companyModel::addCompanyModel($data_company);

    if ($add_company->rowCount() == 1) {
      $alert = [
        "Alerts" => "reload",
        "Title" => "Registro exitoso",
        "Text" => "La Empresa se ha registrado exitosamente.",
        "Tipe" => "success"
      ];
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No se pudo registrar la Empresa. Por favor intenta de nuevo.",
        "Tipe" => "error"
      ];
    }
    echo json_encode($alert);
  } //* - Fin Controlador: Agregar Empresa

  /** ---------- Controlador: Actualizar Empresa ---------- **/
  public function updateCompanyController()
  {
    //* - Recibir id del cliente
    $id = mainModel::cleanData($_POST['empresa_id_up']);
    $nombre = mainModel::cleanData($_POST['empresa_nombre_up']);
    $email = mainModel::cleanData($_POST['empresa_email_up']);
    $telefono = mainModel::cleanData($_POST['empresa_telefono_up']);
    $direccion = mainModel::cleanData($_POST['empresa_direccion_up']);

    //* - Comprobar campos obligatorios
    if ($nombre == "" || $email == "" || $telefono == "" || $direccion == "") {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No has llenado todos los campos que son obligatorios.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificación - Integridad de los Datos
    if (mainModel::verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,70}", $nombre)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Nombre de la Empresa no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[0-9\(\)\+ ]{14,20}", $telefono)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Teléfono no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\(\).,#\- ]{6,190}", $direccion)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "La Dirección no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "Ha ingresado un correo no válido.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Comprobar permisos
    session_start(['name' => 'LoanC']);
  }
}
