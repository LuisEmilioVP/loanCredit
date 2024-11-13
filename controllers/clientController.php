<?php
//* - Detectar la petición ajax o no
if ($petitionAjax) {
  require_once "../models/clientModel.php";
} else {
  require_once "./models/clientModel.php";
}

class clientController extends clientModel
{
  /** ---------- Controlador: Agregar Clientes ---------- **/
  public function addClientController()
  {
    //* - Recibir datos del formu y limpiarlos
    $dni = mainModel::cleanData($_POST['cliente_dni_reg']);
    $nombre = mainModel::cleanData($_POST['cliente_nombre_reg']);
    $apellido = mainModel::cleanData($_POST['cliente_apellido_reg']);
    $telefono = mainModel::cleanData($_POST['cliente_telefono_reg']);
    $direccion = mainModel::cleanData($_POST['cliente_direccion_reg']);

    //* - Verificar campos obligatorios
    if ($dni == "" || $nombre == "" || $apellido == "") {
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
    if (mainModel::verifyData("[0-9\-]{10,20}", $dni)) {
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

    //* - Verificar el cliente en la DB
    $check_dni = mainModel::simpleQuery("SELECT cliente_id FROM cliente WHERE cliente_dni = '$dni'");

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

    $check_client = mainModel::simpleQuery("SELECT cliente_id FROM cliente WHERE cliente_nombre = '$nombre' OR cliente_apellido = '$apellido'");

    if ($check_client->rowCount() > 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El cliente ya existe en el sistema",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Almacenamiento de los datos
    $data_client = [
      "dni" => $dni,
      "nombre" => $nombre,
      "apellido" => $apellido,
      "telefono" => $telefono,
      "direccion" => $direccion,
    ];

    $addClient = clientModel::addClientModel($data_client);

    if ($addClient->rowCount() == 1) {
      $alert = [
        "Alerts" => "clean",
        "Title" => "Cliente Registrado!",
        "Text" => "Los datos del cliente han sido registrados con éxito.",
        "Tipe" => "success"
      ];
      echo json_encode($alert);
      exit();
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "Los datos no se han podido registrar, intente nuevamente.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
    }
  } //* - Fin Controlador: Agregar Clientes

  /** ---------- Controlador: Paginar Clientes ---------- **/
  public function paginationClientsController($page, $register, $rol, $url, $searchs)
  {
    $page = mainModel::cleanData($page);
    $register = mainModel::cleanData($register);
    $rol = mainModel::cleanData($rol);

    $url = mainModel::cleanData($url);
    $url = APP_SERVER . $url . "/";

    $searchs = mainModel::cleanData($searchs);
    $table = "";

    $page = (isset($page) && $page > 0) ? (int) $page : 1;
    $inict = ($page > 0) ? (($page * $register) - $register) : 0;

    if (isset($searchs) && $searchs != "") {
      $queryClients = "SELECT SQL_CALC_FOUND_ROWS * FROM cliente WHERE cliente_dni LIKE '%$searchs%' OR cliente_nombre LIKE '%$searchs%' OR cliente_apellido LIKE '%$searchs%' OR cliente_telefono LIKE '%$searchs%' ORDER BY cliente_nombre ASC LIMIT $inict,$register";
    } else {
      $queryClients = "SELECT SQL_CALC_FOUND_ROWS * FROM cliente ORDER BY cliente_nombre ASC LIMIT $inict,$register";
    }

    $connect = mainModel::connectionDb();

    $result = $connect->query($queryClients);
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
              <th>APELLIDO</th>
              <th>TELEFONO</th>
              <th>DIRECCIÓN</th>';
    if ($rol == 1 || $rol == 2) {
      $table .= '<th>ACTUALIZAR</th>';
    }

    if ($rol == 1) {
      $table .= '<th>ELIMINAR</th>';
    }
    $table .= '</tr>
					</thead>
					<tbody>';

    if ($total >= 1 && $page <= $numPages) {
      $container = $inict + 1;
      $reg_init = $inict + 1;

      foreach ($result as $rows) {
        $table .= '
					<tr class="text-center" >
						<td>' . $container . '</td>
            <td>' . $rows['cliente_dni'] . '</td>
            <td>' . $rows['cliente_nombre'] . '</td>
            <td>' . $rows['cliente_apellido']  . '</td>
						<td>' . $rows['cliente_telefono'] . '</td>
						<td>
              <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover"
              title="' . $rows['cliente_nombre'] . ' ' . $rows['cliente_apellido'] . '" data-content="' . $rows['cliente_direccion'] . '">
              <i class="fas fa-info-circle"></i>
              </button>
            </td>';

        if ($rol == 1 || $rol == 2) {
          $table .= '
          <td>
							<a href="' . APP_SERVER . 'client-update/' . mainModel::encryption($rows['cliente_id']) . '/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>
							</a>
					</td>';
        }

        if ($rol == 1) {
          $table .= '
          	<td>
							<form class="FromAjax" action="' . APP_SERVER . 'ajax/clientAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="cliente_id_del" value="' . mainModel::encryption($rows['cliente_id']) . '">
								<button type="submit" class="btn btn-warning">
										<i class="far fa-trash-alt"></i>
								</button>
							</form>
						</td>
          ';
        }
        $table .= '</tr>';

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
      $table .= '<p class="text-right">Mostrando clientes ' . $reg_init . ' al ' . $reg_end . ' de un total de ' . $total . '</p>';

      $table .= mainModel::pagination($page, $numPages, $url, 7);
    }

    return $table;
  } //* - Fin Controlador: Paginar Clientes

  /** ---------- Controlador: Eliminar Clientes ---------- **/
  public function deleteClientController()
  {
    //* - recibir ID del cliente
    $id = mainModel::decryption($_POST['cliente_id_del']);
    $id = mainModel::cleanData($id);

    //* - Verificar el cliente en la DB
    $check_client = mainModel::simpleQuery("SELECT cliente_id FROM cliente WHERE cliente_id = '$id'");

    if ($check_client->rowCount() <= 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El cliente que intenta eliminar no existe en el sistema.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificar los prestamos del cliente
    $check_loan = mainModel::simpleQuery("SELECT cliente_id FROM prestamo WHERE cliente_id = '$id' LIMIT 1");

    if ($check_loan->rowCount() < 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No podemos eliminar este cliente debido a que tiene préstamos asociados, recomendamos deshabilitar el cliente si ya no será utilizado.",
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

    //* - Eliminar cliente
    $delete_client = clientModel::deleteClientModel($id);

    if ($delete_client->rowCount() == 1) {
      $alert = [
        "Alerts" => "reload",
        "Title" => "Cleinte eliminado!",
        "Text" => "El cliente ha sido eliminado del sistema exitosamente.",
        "Tipe" => "success"
      ];
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos podido eliminar el cliente, por favor intente nuevamente.",
        "Tipe" => "error"
      ];
    }
    echo json_encode($alert);
  } //* - Fin controlador Eliminar Clientes

  /** ---------- Controlador: Seleccionar Datos de Clientes ---------- **/
  public function selectClientController($type, $id)
  {
    $type = mainModel::cleanData($type);

    $id = mainModel::decryption($id);
    $id = mainModel::cleanData($id);

    return clientModel::selectClientModel($type, $id);
  } //* - Fin controlador Seleccionar Datos de Clientes

  /** ---------- Controlador: Actualizar Clientes ---------- **/
  public function updateClientController()
  {
    //* - Recibir id del cliente
    $id = mainModel::decryption($_POST['cliente_id_up']);
    $id = mainModel::cleanData($id);

    //* - Comprobar el cliente en la DB
    $check_client = mainModel::simpleQuery("SELECT * FROM cliente WHERE cliente_id = '$id'");

    if ($check_client->rowCount() <= 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos encontrado el cliente en el sistema.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    } else {
      $content = $check_client->fetch();
    }

    //* - Recibir datos del formu y limpiarlos
    $dni = mainModel::cleanData($_POST['cliente_dni_up']);
    $nombre = mainModel::cleanData($_POST['cliente_nombre_up']);
    $apellido = mainModel::cleanData($_POST['cliente_apellido_up']);
    $telefono = mainModel::cleanData($_POST['cliente_telefono_up']);
    $direccion = mainModel::cleanData($_POST['cliente_direccion_up']);

    //* - Comprobar campos obligatorios
    if ($dni == "" || $nombre == "" || $apellido == "") {
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

    //* - Verificación de atributos unicos
    if ($dni != $content['cliente_dni']) {
      $check_dni = mainModel::simpleQuery("SELECT cliente_dni FROM cliente WHERE cliente_dni = '$dni'");
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
    }

    //* - Verificar privilegios para actualizar
    session_start(['name' => 'LoanC']);
    if ($_SESSION['role_spm'] < 1 || $_SESSION['role_spm'] > 2) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No tienes los permisos necesarios para realizar esta operacion.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Obtener datos para el enviado
    $data_client_up = [
      "dni" => $dni,
      "nombre" => $nombre,
      "apellido" => $apellido,
      "telefono" => $telefono,
      "direccion" => $direccion,
      "id" => $id
    ];

    if (clientModel::updateClientModel($data_client_up)) {
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
  } //* - Fin de controlador actualizar Clientes
}
