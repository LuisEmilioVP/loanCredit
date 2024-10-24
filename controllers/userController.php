<?php
//* - Detectar la petición ajax o no
if ($petitionAjax) {
  require_once "../models/userModel.php";
} else {
  require_once "./models/userModel.php";
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
    if ($dni == "" || $nombre == "" || $apellido == "" || $usuario == "" || $clave1 == "" || $clave2 == "") {
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

    if (mainModel::verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,35}", $apellido)) {
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
      if (mainModel::verifyData("[0-9()+ ]{14,20}", $telefono)) {
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

  /** ---------- Controlador: Paginar Usuarios ---------- **/
  public function paginationUsersController($page, $register, $rol, $id, $url, $searchs)
  {
    $page = mainModel::cleanData($page);
    $register = mainModel::cleanData($register);
    $rol = mainModel::cleanData($rol);
    $id = mainModel::cleanData($id);

    $url = mainModel::cleanData($url);
    $url = APP_SERVER . $url . "/";

    $searchs = mainModel::cleanData($searchs);
    $table = "";

    $page = (isset($page) && $page > 0) ? (int) $page : 1;
    $inict = ($page > 0) ? (($page * $register) - $register) : 0;

    if (isset($searchs) && $searchs != "") {
      $queryUsers = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE ((usuario_id!='$id' AND usuario_id!='1') AND (usuario_dni LIKE '%$searchs%' OR usuario_nombre LIKE '%$searchs%' OR usuario_apellido LIKE '%$searchs%' OR usuario_telefono LIKE '%$searchs%' OR usuario_email LIKE '%$searchs%' OR usuario_usuario LIKE '%$searchs%')) ORDER BY usuario_nombre ASC LIMIT $inict,$register";
    } else {
      $queryUsers = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE usuario_id!='$id' AND usuario_id!='1' ORDER BY usuario_nombre ASC LIMIT $inict,$register";
    }

    $connect = mainModel::connectionDb();

    $result = $connect->query($queryUsers);
    $result = $result->fetchAll();

    $total = $connect->query("SELECT FOUND_ROWS()");
    $total = (int) $total->fetchColumn();

    $numPages = ceil($total / $register);

    $table .= '<div class="table-responsive">
				<table class="table table-dark table-sm">
					<thead>
						<tr class="text-center roboto-medium">
							<th>#</th>
							<th>DNI</th>
							<th>NOMBRE</th>
							<th>TELÉFONO</th>
							<th>USUARIO</th>
							<th>EMAIL</th>
							<th>ACTUALIZAR</th>
							<th>ELIMINAR</th>
						</tr>
					</thead>
					<tbody>';

    if ($total >= 1 && $page <= $numPages) {
      $container = $inict + 1;
      $reg_init = $inict + 1;

      foreach ($result as $rows) {
        $table .= '
					<tr class="text-center" >
						<td>' . $container . '</td>
            <td>' . $rows['usuario_dni'] . '</td>
            <td>' . $rows['usuario_nombre'] . ' ' . $rows['usuario_apellido'] . '</td>
						<td>' . $rows['usuario_telefono'] . '</td>
						<td>' . $rows['usuario_usuario'] . '</td>
						<td>' . $rows['usuario_email'] . '</td>
						<td>
							<a href="' . APP_SERVER . 'user-update/' . mainModel::encryption($rows['usuario_id']) . '/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>
							</a>
						</td>
						<td>
							<form class="FromAjax" action="' . APP_SERVER . 'ajax/userAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="usuario_id_del" value="' . mainModel::encryption($rows['usuario_id']) . '">
								<button type="submit" class="btn btn-warning">
										<i class="far fa-trash-alt"></i>
								</button>
							</form>
						</td>
					</tr>';
        $container++;
      }
      $reg_end = $container - 1;
    } else {
      if ($total >= 1) {
        $table .= '<tr class="text-center" ><td colspan="9">
					<a href="' . $url . '" class="btn btn-raised btn-primary btn-sm">Haga clic aca para recargar el listado</a>
					</td></tr>';
      } else {
        $table .= '<tr class="text-center" ><td colspan="9">No hay registros en el sistema</td></tr>';
      }
    }

    $table .= '</tbody></table></div>';

    if ($total >= 1 && $page <= $numPages) {
      $table .= '<p class="text-right">Mostrando usuario ' . $reg_init . ' al ' . $reg_end . ' de un total de ' . $total . '</p>';

      $table .= mainModel::pagination($page, $numPages, $url, 7);
    }

    return $table;
  } //* - Fin controlador listar usuarios

  /** ---------- Controlador: Eliminar Usuarios ---------- **/
  public function deleteUserController()
  {
    //* - recibir ID del usuario
    $id = mainModel::decryption($_POST['usuario_id_del']);
    $id = mainModel::cleanData($id);

    //* - Verificar el usuario principal
    if ($id == 1) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No podemos eliminar el usuario principal del sistema.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificar el usuario en la DB
    $check_user = mainModel::simpleQuery("SELECT usuario_id FROM usuario WHERE usuario_id = '$id'");

    if ($check_user->rowCount() <= 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El usuario que intenta eliminar no existe en el sistema.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificar los prestamos del usuario
    $check_loan = mainModel::simpleQuery("SELECT usuario_id FROM prestamo WHERE usuario_id = '$id' LIMIT 1");

    if ($check_loan->rowCount() < 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No podemos eliminar este usuario debido a que tiene préstamos asociados, recomendamos deshabilitar el usuario si ya no será utilizado.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificar privilegio del Usuario
    session_start(['name' => 'LoanC']);
    if ($_SESSION['role_spm'] != 1) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No tienes los permisos necesarios para realizar esta operacion.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Eliminar usuario
    $delete_user = userModel::deleteUserModel($id);

    if ($delete_user->rowCount() == 1) {
      $alert = [
        "Alerts" => "reload",
        "Title" => "¡Usuario eliminado!",
        "Text" => "El usuario ha sido eliminado del sistema exitosamente.",
        "Tipe" => "success"
      ];
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos podido eliminar el usuario, por favor intente nuevamente.",
        "Tipe" => "error"
      ];
    }
    echo json_encode($alert);
  } //* - Fin controlador Eliminar Usuarios

  /** ---------- Controlador: Seleccionar Datos de Usuarios ---------- **/
  public function selectUserController($type, $id)
  {
    $type = mainModel::cleanData($type);

    $id = mainModel::decryption($id);
    $id = mainModel::cleanData($id);

    return userModel::selectUserModel($type, $id);
  } //* - Fin controlador Seleccionar Datos de Usuarios

  /** ---------- Controlador: Actualizar Usuarios ---------- **/
  public function updateUserController()
  {
    //* - Recibir id del usuario
    $id = mainModel::decryption($_POST['usuario_id_up']);
    $id = mainModel::cleanData($id);

    //* - Comprobar el usuario en la DB
    $check_user = mainModel::simpleQuery("SELECT * FROM usuario WHERE usuario_id='$id'");

    if ($check_user->rowCount() <= 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos encontrado el usuario en el sistema.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    } else {
      $content = $check_user->fetch();
    }

    //* - Recibir datos del formu y limpiarlos
    $dni = mainModel::cleanData($_POST['usuario_dni_up']);
    $nombre = mainModel::cleanData($_POST['usuario_nombre_up']);
    $apellido = mainModel::cleanData($_POST['usuario_apellido_up']);
    $telefono = mainModel::cleanData($_POST['usuario_telefono_up']);
    $direccion = mainModel::cleanData($_POST['usuario_direccion_up']);

    $usuario = mainModel::cleanData($_POST['usuario_usuario_up']);
    $email = mainModel::cleanData($_POST['usuario_email_up']);

    if (isset($_POST['usuario_estado_up'])) {
      $estado = mainModel::cleanData($_POST['usuario_estado_up']);
    } else {
      $estado = $content['usuario_estado'];
    }

    if (isset($_POST['usuario_privilegio_up'])) {
      $privilegio = mainModel::cleanData($_POST['usuario_privilegio_up']);
    } else {
      $privilegio = $content['usuario_privilegio'];
    }

    $admin_user = mainModel::cleanData($_POST['usuario_admin']);
    $admin_clave = mainModel::cleanData($_POST['clave_admin']);
    $type_seccion = mainModel::cleanData($_POST['tipo_cuenta']);

    //* - Comprobar campos obligatorios
    if (
      $dni == "" || $nombre == "" || $apellido == "" || $usuario == "" || $admin_user == "" || $admin_clave == ""
    ) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No has llenado todos los campos que son obligatorios.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificación - Integridad de los datos
    if (mainModel::verifyData("[0-9\-]{10,20}", $dni)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El DNI no coincide con el formato solicitado.",
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

    if (mainModel::verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,35}", $apellido)) {
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
    }

    if ($direccion != "") {
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

    if (mainModel::verifyData("[a-zA-Z0-9]{3,35}", $admin_user)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "Tu Nombre de Usuario no coincide con el formato solicitado.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[a-zA-Z0-9$@.\-]{7,100}", $admin_clave)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "Tu Clave no coincide con el formato solicitado.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    $admin_clave = mainModel::encryption($admin_clave);

    if ($privilegio < 1 || $privilegio > 3) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El privilegio no corresponde a un valor valido.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if ($estado != "Activa" && $estado != "Deshabilitada") {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El estado de la cuenta no coincide con el formato solicitado.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificación de atributos unicos
    if ($dni != $content['usuario_dni']) {
      $check_user = mainModel::simpleQuery("SELECT usuario_dni FROM usuario WHERE usuario_dni='$dni'");

      if ($check_user->rowCount() > 0) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "El DNI ingresado ya se encuentra registrado en el sistema.",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    if ($usuario != $content['usuario_usuario']) {
      $check_user = mainModel::simpleQuery("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");

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
    }

    //* - Validar email
    if ($email != $content['usuario_email'] && $email != "") {
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $check_email = mainModel::simpleQuery("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
        if ($check_email->rowCount() > 0) {
          $alert = [
            "Alerts" => "simple",
            "Title" => "Ocurrió un error inesperado",
            "Text" => "El nuevo email ingresado ya se encuentra registrado en el sistema.",
            "Tipe" => "error"
          ];
          echo json_encode($alert);
          exit();
        }
      } else {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "El nuevo email ingresado ya se encuentra registrado en el sistema.",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    //* - Verificar claves
    if ($_POST['usuario_clave_nueva_1'] != "" || $_POST['usuario_clave_nueva_2'] != "") {
      if ($_POST['usuario_clave_nueva_1'] != $_POST['usuario_clave_nueva_2']) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "Las nuevas claves ingresadas no coinciden",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      } else {
        if (mainModel::verifyData("[a-zA-Z0-9$@.\-]{7,100}", $_POST['usuario_clave_nueva_1']) || mainModel::verifyData("[a-zA-Z0-9$@.\-]{7,100}", $_POST['usuario_clave_nueva_2'])) {
          $alert = [
            "Alerts" => "simple",
            "Title" => "Ocurrió un error inesperado",
            "Text" => "Las nuevas claves no coinciden con el formato solicitado",
            "Tipe" => "error"
          ];
          echo json_encode($alert);
          exit();
        }
        $clave = mainModel::encryption($_POST['usuario_clave_nueva_1']);
      }
    } else {
      $clave = $content['usuario_clave'];
    }

    //* - Verificar credenciales para actualizar datos
    if ($type_seccion == "Propia") {
      $check_seccion = mainModel::simpleQuery("SELECT usuario_id FROM usuario WHERE usuario_usuario='$admin_user' AND usuario_clave='$admin_clave' AND usuario_id='$id'");
    } else {
      session_start(['name' => 'LoanC']);
      if ($_SESSION['role_spm'] != 1) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "No tienes los permisos necesarios para realizar esta operacion.",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
      $check_seccion = mainModel::simpleQuery("SELECT usuario_id FROM usuario WHERE usuario_usuario='$admin_user' AND usuario_clave='$admin_clave'");
    }

    if ($check_seccion->rowCount() <= 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "Nombre y clave de administrador no validos.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Obtener datos para el enviado
    $data_user_up = [
      "dni" => $dni,
      "nombre" => $nombre,
      "apellido" => $apellido,
      "telefono" => $telefono,
      "direccion" => $direccion,
      "email" => $email,
      "usuario" => $usuario,
      "clave" => $clave,
      "estado" => $estado,
      "privilegio" => $privilegio,
      "id" => $id,
    ];

    if (userModel::updateUserModel($data_user_up)) {
      $alert = [
        "Alerts" => "reload",
        "Title" => "Datos actualizados",
        "Text" => "Los datos han sido actualizados con exito.",
        "Tipe" => "success"
      ];
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos podido actualizar los datos, por favor intente nuevamente.",
        "Tipe" => "error"
      ];
    }
    echo json_encode($alert);
  } //* - Fin de controlador actualizar datos
}
