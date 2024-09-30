<?php
//* - Detectar la petición ajax o no
if ($petitionAjax) {
  require_once "../models/userModel.php";
} else {
  require_once "../models/userModel.php";
}

class userController extends userModel
{
  /** ---------- Controlador: Agregar Usuarios ---------- **/
  public function addUserController()
  {
    //* - Recibir datos del formu y limpiarlos
    $dni = mainModel::cleanData($_POST['usuario_dni_reg']);
    $nombre = mainModel::cleanData($_POST['usuario_nombre_reg']);
    $apellido = mainModel::cleanData($_POST['usuario_apellido_reg']);
    $telefono = mainModel::cleanData($_POST['usuario_telefono_reg']);
    $direccion = mainModel::cleanData($_POST['usuario_direccion_reg']);
    $usuario = mainModel::cleanData($_POST['usuario_usuario_reg']);
    $email = mainModel::cleanData($_POST['usuario_email_reg']);
    $clave1 = mainModel::cleanData($_POST['usuario_clave_1_reg']);
    $clave2 = mainModel::cleanData($_POST['usuario_clave_2_reg']);
    $privilegio = mainModel::cleanData($_POST['usuario_privilegio_reg']);

    //* - Verificar campos obligatorios
    if (
      $dni == "" || $nombre == "" || $apellido == "" || $usuario == "" || $clave1 == "" || $clave2 == ""
    ) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No han llenado todos los campos que son obligatorios",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificación - Integridad de los datos
    if (mainModel::verifyData("[0-9\-]{13,20}", $dni)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El DNI no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,35}", $nombre)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Nombre no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $apellido)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Apellido no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if ($telefono != "") {
      if (mainModel::verifyData("[0-9()+\-]{14,20}", $telefono)) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "El Teléfono no coincide con el formato solicitado",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    if ($direccion != "") {
      if (mainModel::verifyData("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{6,190}", $direccion)) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "La Dirección no coincide con el formato solicitado",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    if (mainModel::verifyData("[a-zA-Z0-9]{3,35}", $usuario)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Nombre de Usuario no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if ($email != "") {
      if (mainModel::verifyData("[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}", $email)) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "El Email no coincide con el formato solicitado",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    if (mainModel::verifyData("[a-zA-Z0-9$@.-]{7,100}", $clave1) || mainModel::verifyData("[a-zA-Z0-9$@.-]{7,100}", $clave2)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "Las contraseñas no coinciden con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificación - Existencia de los datos Unicos en la BD
    $check_dni = mainModel::simpleQuery("SELECT usuario_dni FROM usuario WHERE usuario_dni = '$dni'");

    if ($check_dni->rowCount() > 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El DNI ingresado ya se encuentra registrado en el sistema.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    $check_user = mainModel::simpleQuery("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");

    if ($check_user->rowCount() > 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Nombre de Usuario ingresado ya se encuentra registrado en el sistema.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if ($email != "") {

      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $check_email = mainModel::simpleQuery("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");

        if ($check_email->rowCount() > 0) {
          $alert = [
            "Alerts" => "simple",
            "Title" => "Ocurrió un error inesperado",
            "Text" => "El Email ingresado ya se encuentra registrado en el sistema.",
            "Tipe" => "error"
          ];
          echo json_encode($alert);
          exit();
        }
      } else {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "Ha ingresado un correo no válido.",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    //* - Manejo de la Clave
    if ($clave1 != $clave2) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "Las claves ingresadas no coinciden.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    } else {
      $claveNew = mainModel::encryption($clave1);
    }

    //* - Manejo de privilegios
    if ($privilegio < 1 || $privilegio > 3) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El privilegio seleccionado no es válido.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Almacenamiento de los datos
    $data = [
      "dni" => $dni,
      "nombre" => $nombre,
      "apellido" => $apellido,
      "telefono" => $telefono,
      "direccion" => $direccion,
      "email" => $email,
      "usuario" => $usuario,
      "clave" => $claveNew,
      "estado" => "Activa",
      "privilegio" => $privilegio,
    ];

    $addUser = userModel::addUserModel($data);

    if ($addUser->rowCount() == 1) {
      $alert = [
        "Alerts" => "clean",
        "Title" => "¡Usuario Registrado!",
        "Text" => "Los datos del usuario han sido registrados con éxito.",
        "Tipe" => "success"
      ];
      echo json_encode($alert);
      exit();
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "Los datos no se han podido registrar.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
    }
  } //* - Fin Controlador: Agregar Usuarios
  /** ---------- Controlador: Listar Usuarios ---------- **/
  /** ---------- Controlador: Actualizar Usuarios ---------- **/
  /** ---------- Controlador: Eliminar Usuarios ---------- **/
  /** ---------- Controlador: Buscar Usuarios ---------- **/
}
