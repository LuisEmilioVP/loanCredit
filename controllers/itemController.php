<?php
//* - Detectar la petición ajax o no
if ($petitionAjax) {
  require_once "../models/itemModel.php";
} else {
  require_once "./models/itemModel.php";
}

class itemController extends itemModel
{
  /** ---------- Controlador: Agregar Items ---------- **/
  public function addItemController()
  {
    //* - Recibir datos del formu y limpiarlos
    $code = mainModel::cleanData($_POST['item_codigo_reg']);
    $nombre = mainModel::cleanData($_POST['item_nombre_reg']);
    $stock = mainModel::cleanData($_POST['item_stock_reg']);
    $stado = mainModel::cleanData($_POST['item_estado_reg']);
    $detalle = mainModel::cleanData($_POST['item_detalle_reg']);

    //* - Verificar campos obligatorios
    if ($code == "" || $nombre == "" || $stock == "" || $stado == "") {
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
    if (mainModel::verifyData("[a-zA-Z0-9\-]{1,45}", $code)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Código del Item no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}", $nombre)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Nombre de la Empresa no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[0-9]{1,9}", $stock)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Stock no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if ($detalle != "") {
      if (mainModel::verifyData("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\(\).,#\- ]{1,190}", $detalle)) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "El Detalle no coincide con el formato solicitado",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    if ($stado != "Habilitado" && $stado != "Deshabilitado") {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Estado del Item no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificar el codigo del item en la DB
    $check_item_code = mainModel::simpleQuery("SELECT item_codigo FROM item WHERE item_codigo = '$code'");

    if ($check_item_code->rowCount() > 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Código del Item ya se encuentra registrado en el sistema",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificar el nombre del item en la DB
    $check_item_name = mainModel::simpleQuery("SELECT item_nombre FROM item WHERE item_nombre = '$nombre'");

    if ($check_item_name->rowCount() > 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Nombre del Item ya se encuentra registrado en el sistema",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    $data_item = [
      "codigo" => $code,
      "nombre" => $nombre,
      "stock" => $stock,
      "estado" => $stado,
      "detalle" => $detalle
    ];

    $add_item = itemModel::addItemModel($data_item);

    if ($add_item->rowCount() == 1) {
      $alert = [
        "Alerts" => "clean",
        "Title" => "Registro exitoso",
        "Text" => "Los datos del Item se ha registrado exitosamente.",
        "Tipe" => "success"
      ];
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No se pudo registrar el Item. Por favor intenta de nuevo.",
        "Tipe" => "error"
      ];
    }
    echo json_encode($alert);
  } //* - Fin del controlador de agregar Items

  /** ---------- Controlador: Paginar Clientes ---------- **/
  public function paginationItemsController($page, $register, $rol, $url, $searchs)
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
      $queryItems = "SELECT SQL_CALC_FOUND_ROWS * FROM item WHERE item_codigo LIKE '%$searchs%' OR item_nombre LIKE '%$searchs%' ORDER BY item_nombre ASC LIMIT $inict,$register";
    } else {
      $queryItems = "SELECT SQL_CALC_FOUND_ROWS * FROM item ORDER BY item_nombre ASC LIMIT $inict,$register";
    }

    $connect = mainModel::connectionDb();

    $result = $connect->query($queryItems);
    $result = $result->fetchAll();

    $total = $connect->query("SELECT FOUND_ROWS()");
    $total = (int) $total->fetchColumn();

    $numPages = ceil($total / $register);

    $table .= '<div class="table-responsive">
				<table class="table table-dark table-sm">
					<thead>
						<tr class="text-center roboto-medium">
              <th>#</th>
              <th>CÓDIGO</th>
              <th>NOMBRE</th>
              <th>STOCK</th>
              <th>ESTADO</th>
              <th>DETALLE</th>';
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
            <td>' . $rows['item_codigo'] . '</td>
            <td>' . $rows['item_nombre'] . '</td>
            <td>' . $rows['item_stock']  . '</td>
						<td>' . $rows['item_estado'] . '</td>
						<td>
              <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover"
              title="' . $rows['item_nombre'] . '" data-content="' . $rows['item_detalle'] . '">
              <i class="fas fa-info-circle"></i>
              </button>
            </td>';

        if ($rol == 1 || $rol == 2) {
          $table .= '
          <td>
							<a href="' . APP_SERVER . 'item-update/' . mainModel::encryption($rows['item_id']) . '/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>
							</a>
					</td>';
        }

        if ($rol == 1) {
          $table .= '
          	<td>
							<form class="FromAjax" action="' . APP_SERVER . 'ajax/itemAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="item_id_del" value="' . mainModel::encryption($rows['item_id']) . '">
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
        $table .= '<tr class="text-center" ><td colspan="8">
					<a href="' . $url . '" class="btn btn-raised btn-primary btn-sm">Haga clic aca para recargar el listado</a>
					</td></tr>';
      } else {
        $table .= '<tr class="text-center" ><td colspan="8">No hay registros en el sistema</td></tr>';
      }
    }

    $table .= '</tbody></table></div>';

    if ($total >= 1 && $page <= $numPages) {
      $table .= '<p class="text-right">Mostrando items ' . $reg_init . ' al ' . $reg_end . ' de un total de ' . $total . '</p>';

      $table .= mainModel::pagination($page, $numPages, $url, 7);
    }

    return $table;
  } //* - Fin Controlador: Paginar Items

  /** ---------- Controlador: Eliminar Items ---------- **/
  public function deleteItemController()
  {
    //* - recibir ID del cliente
    $id = mainModel::decryption($_POST['item_id_del']);
    $id = mainModel::cleanData($id);

    //* - Verificar el cliente en la DB
    $check_item = mainModel::simpleQuery("SELECT item_id FROM item WHERE item_id = '$id'");

    if ($check_item->rowCount() <= 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El item que intenta eliminar no existe en el sistema.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificar los detalles del item
    $check_detail = mainModel::simpleQuery("SELECT item_id FROM detalle WHERE item_id = '$id' LIMIT 1");

    if ($check_detail->rowCount() < 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No podemos eliminar este item debido a que tiene detalles asociados. Recomendamos deshabilitar el item si ya no sera utilizado.",
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

    //* - Eliminar item
    $delete_item = itemModel::deleteItemModel($id);

    if ($delete_item->rowCount() == 1) {
      $alert = [
        "Alerts" => "reload",
        "Title" => "Item eliminado!",
        "Text" => "El item ha sido eliminado del sistema exitosamente.",
        "Tipe" => "success"
      ];
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos podido eliminar el item, por favor intente nuevamente.",
        "Tipe" => "error"
      ];
    }
    echo json_encode($alert);
  } //* - Fin controlador Eliminar Items

  /** ---------- Controlador: Seleccionar Datos de Items ---------- **/
  public function selectItemController($type, $id)
  {
    $type = mainModel::cleanData($type);

    $id = mainModel::decryption($id);
    $id = mainModel::cleanData($id);

    return itemModel::selectItemModel($type, $id);
  } //* - Fin controlador Seleccionar Items

  /** ---------- Controlador: Actualizar Items ---------- **/
  public function updateItemController()
  {
    //* - Recibir id del cliente
    $id = mainModel::decryption($_POST['item_id_up']);
    $id = mainModel::cleanData($id);

    //* - Comprobar el item en la DB
    $check_item = mainModel::simpleQuery("SELECT * FROM item WHERE item_id = '$id'");

    if ($check_item->rowCount() <= 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos encontrado el item en el sistema.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    } else {
      $content = $check_item->fetch();
    }

    //* - Recibir datos del formu y limpiarlos
    $code = mainModel::cleanData($_POST['item_codigo_up']);
    $nombre = mainModel::cleanData($_POST['item_nombre_up']);
    $stock = mainModel::cleanData($_POST['item_stock_up']);
    $estado = mainModel::cleanData($_POST['item_estado_up']);
    $detalle = mainModel::cleanData($_POST['item_detalle_up']);

    //* - Comprobar campos obligatorios
    if ($code == "" || $nombre == "" || $stock == "" || $estado == "") {
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
    if (mainModel::verifyData("[a-zA-Z0-9\-]{1,45}", $code)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Código del Item no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}", $nombre)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Nombre de la Empresa no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[0-9]{1,9}", $stock)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Stock no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if ($detalle != "") {
      if (mainModel::verifyData("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\(\).,#\- ]{1,190}", $detalle)) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "El Detalle no coincide con el formato solicitado",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    if ($estado != "Habilitado" && $estado != "Deshabilitado") {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El Estado del Item no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Verificación de atributos unicos
    if ($code != $content['item_codigo']) {
      $check_item_code = mainModel::simpleQuery("SELECT item_codigo FROM item WHERE item_codigo = '$code'");
      if ($check_item_code->rowCount() > 0) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "El Codigo ingresado ya se encuentra registrado en el sistema.",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    //* - Verificación de atributos unicos
    if ($nombre != $content['item_nombre']) {
      $check_item_nombre = mainModel::simpleQuery("SELECT item_nombre FROM item WHERE item_nombre = '$nombre'");
      if ($check_item_nombre->rowCount() > 0) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "El Nombre ingresado ya se encuentra registrado en el sistema.",
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
    $data_iten_up = [
      "codigo" => $code,
      "nombre" => $nombre,
      "stock" => $stock,
      "estado" => $estado,
      "detalle" => $detalle,
      "id" => $id
    ];

    if (itemModel::updateItemModel($data_iten_up)) {
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
  } //* - Fin de controlador actualizar items
}
