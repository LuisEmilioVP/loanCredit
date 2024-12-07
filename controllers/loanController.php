<?php
//* - Detectar la petición ajax o no
if ($petitionAjax) {
  require_once "../models/loanModel.php";
} else {
  require_once "./models/loanModel.php";
}

class loanController extends loanModel
{
  /** ---------- Controlador: Buscar Cliente - Prestamos ---------- **/
  public function searchLoanClientController()
  {
    $client = mainModel::cleanData($_POST['seaech_client']);

    //* - Validar coampos obligatorios
    if ($client == "") {
      return '
        <div class="alert alert-warning" role="alert">
          <p class="text-center mb-0">
            <i class="fas fa-exclamation-triangle fa-2x"></i><br />
            Debes ingresar algunos de los datos requeridos para la búsqueda.
          </p>
        </div>
      ';
      exit();
    }

    //* - Seleccuionar el cliente de la db
    $client_data = mainModel::simpleQuery("SELECT * FROM cliente WHERE cliente_dni LIKE '%$client%' OR cliente_nombre LIKE '%$client%' OR cliente_apellido LIKE '%$client%' ORDER BY cliente_nombre ASC");

    // echo 'Data del cliente: ' . $client_data->rowCount();

    if ($client_data->rowCount() >= 1) {
      $client_data = $client_data->fetchAll();

      $table = '<div class="table-responsive"> <table class="table table-hover table-bordered table-sm"> <tbody>';

      foreach ($client_data as $rows) {
        $table .= '
        <tr class="text-center">
           <td>' . $rows['cliente_dni'] . ' - ' . $rows['cliente_nombre'] . ' ' . $rows['cliente_apellido'] . '</td>

            <td>
              <button type="button" class="btn btn-primary" onclick="add_client(' . $rows['cliente_id'] . ')">
                <i class="fas fa-user-plus"></i>
              </button>
            </td>
          </tr>
          ';
      }
      $table .= '</tbody></table></div>';

      return $table;
    } else {
      return '
        <div class="alert alert-warning" role="alert">
          <p class="text-center mb-0">
            <i class="fas fa-exclamation-triangle fa-2x"></i><br />
            No hemos encontrado ningún cliente en el sistema que coincida con
            <strong>“' . $client . '”</strong>`
          </p>
        </div>
      ';
      exit();
    }
  } //*- Fin del controlador

  /** ---------- Controlador: Agregar Cliente - Prestamos ---------- **/
  public function addLoanClientController()
  {
    $id = mainModel::cleanData($_POST['add_client_id']);

    //* - Validar que el id exista
    $check_client = mainModel::simpleQuery("SELECT * FROM cliente WHERE cliente_id = '$id'");

    if ($check_client->rowCount() <= 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos encontrado ninguno cliente en la base de datos",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    } else {
      $columns = $check_client->fetch();
    }

    //*- Iniciar sesion para el controlador y cargar los datos
    session_start(['name' => 'LoanC']);

    if (empty($_SESSION['client_data'])) {
      $_SESSION['client_data'] = [
        "id" => $columns['cliente_id'],
        "dni" => $columns['cliente_dni'],
        "nombre" => $columns['cliente_nombre'],
        "apellido" => $columns['cliente_apellido'],
        "telefono" => $columns['cliente_telefono'],
        "direccion" => $columns['cliente_direccion']
      ];

      $alert = [
        "Alerts" => "reload",
        "Title" => "Cliente Agregado!",
        "Text" => "El cliente a sido agregado al prestamo o reservación.",
        "Tipe" => "success"
      ];
      echo json_encode($alert);
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos podido agregar el cliente a este prestamo o reservación.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
    }
  } //*- Fin del controlador

  /** ---------- Controlador: Eliminar Cliente - Prestamos ---------- **/
  public function deleteLoanClientController()
  {
    //*- Iniciar sesion para el controlador
    session_start(['name' => 'LoanC']);
    //* - Eliminar los datos de la sesion
    unset($_SESSION['client_data']);

    if (empty($_SESSION['client_data'])) {
      $alert = [
        "Alerts" => "reload",
        "Title" => "Cliente Removido!",
        "Text" => "Los datos del cliente a sido removido con exito.",
        "Tipe" => "success"
      ];
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos podido remover los datos del cliente.",
        "Tipe" => "error"
      ];
    }
    echo json_encode($alert);
  } //*- Fin del controlador

  /** ---------- Controlador: Buscar Items - Prestamos ---------- **/
  public function searchLoanItemController()
  {
    $item = mainModel::cleanData($_POST['seaech_item']);

    //* - Validar coampos obligatorios
    if ($item == "") {
      return '
        <div class="alert alert-warning" role="alert">
          <p class="text-center mb-0">
            <i class="fas fa-exclamation-triangle fa-2x"></i><br />
            Debes ingresar algunos de los datos requeridos para la búsqueda.
          </p>
        </div>
      ';
      exit();
    }

    //* - Seleccuionar el item de la db
    $item_data = mainModel::simpleQuery("SELECT * FROM item WHERE item_codigo LIKE '%$item%' OR item_nombre LIKE '%$item%'ORDER BY item_nombre ASC");

    // echo 'Data del item: ' . $item_data->rowCount();
    if ($item_data->rowCount() >= 1) {
      $item_data = $item_data->fetchAll();

      $table = '<div class="table-responsive"> <table class="table table-hover table-bordered table-sm"> <tbody>';

      foreach ($item_data as $rows) {
        $table .= '
        <tr class="text-center">
           <td>' . $rows['item_codigo'] . ' - ' . $rows['item_nombre'] . '</td>
            <td>
              <button type="button" class="btn btn-primary" onclick="add_item(' . $rows['item_id'] . ')">
                <i class="fas fa-box-open"></i>
              </button>
            </td>
          </tr>
          ';
      }
      $table .= '</tbody></table></div>';

      return $table;
    } else {
      return '
       <div class="alert alert-warning" role="alert">
          <p class="text-center mb-0">
            <i class="fas fa-exclamation-triangle fa-2x"></i><br />
            No hemos encontrado ningún item en el sistema que coincida con
            <strong>' . $item . '</strong>
          </p>
        </div>
      ';
      exit();
    }
  } //*- Fin del controlador

  /** ---------- Controlador: Agregar Items - Prestamos ---------- **/
  public function  addLoanItemController()
  {
    $id = mainModel::cleanData($_POST['add_item_id']);

    //* - Validar que el id exista
    $check_ietm = mainModel::simpleQuery("SELECT * FROM item WHERE item_id = '$id' AND item_estado = 'Habilitado'");

    if ($check_ietm->rowCount() <= 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos encontrado ninguno item en la base de datos",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    } else {
      $columns = $check_ietm->fetch();
    }

    //* - Recibir datos del formu y limpiarlos
    $formato = mainModel::cleanData($_POST['detalle_formato']);
    $cantidad = mainModel::cleanData($_POST['detalle_cantidad']);
    $tiempo = mainModel::cleanData($_POST['detalle_tiempo']);
    $costo = mainModel::cleanData($_POST['detalle_costo_tiempo']);

    //* - Verificar campos obligatorios
    if ($cantidad == "" || $tiempo == "" || $costo == "") {
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
    if (mainModel::verifyData("[0-9]{1,7}", $cantidad)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "La cantidad no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[0-9]{1,7}", $tiempo)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El tiempo no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[0-9.]{1,15}", $costo)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El costo no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if ($formato != 'Horas' & $formato != 'Dias' & $formato != 'Evento' & $formato != 'Mes') {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El formato del prestamo no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //*- Iniciar sesion para el controlador y cargar los datos
    session_start(['name' => 'LoanC']);

    if (empty($_SESSION['item_data'][$id])) {
      $costo = number_format($costo, 2, ".", " ");

      $_SESSION['item_data'][$id] = [
        "id" => $columns['item_id'],
        "codigo" => $columns['item_codigo'],
        "nombre" => $columns['item_nombre'],
        "detalle" => $columns['item_detalle'],
        "formato" => $formato,
        "cantidad" => $cantidad,
        "tiempo" => $tiempo,
        "costo" => $costo
      ];

      $alert = [
        "Alerts" => "reload",
        "Title" => "Item Agregado!",
        "Text" => "El item se ha agregado al prestamo con exito",
        "Tipe" => "success"
      ];
      echo json_encode($alert);
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El item ya se encuentra seleccionado en la lista de prestamos",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }
  } //*- Fin del controlador

  /** ---------- Controlador: Eliminar Items - Prestamos ---------- **/

  public function deleteLoanItemController()
  {
    $id = mainModel::cleanData($_POST['delete_item_id']);

    //*- Iniciar sesion para el controlador
    session_start(['name' => 'LoanC']);
    //* - Eliminar los datos de la sesion
    unset($_SESSION['item_data'][$id]);

    if (empty($_SESSION['item_data'][$id])) {
      $alert = [
        "Alerts" => "reload",
        "Title" => "Item Removido!",
        "Text" => "El item a sido removido con exito.",
        "Tipe" => "success"
      ];
    } else {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos podido remover el item.",
        "Tipe" => "error"
      ];
    }
    echo json_encode($alert);
  } //*- Fin del controlador

  /** ---------- Controlador: Seleccionar Prestamo ---------- **/

  public function selectLoanController($type, $id)
  {
    $type = mainModel::cleanData($type);

    $id = mainModel::decryption($id);
    $id = mainModel::cleanData($id);

    return loanModel::selectLoanModel($type, $id);
  } //*- Fin del controlador

  /** ---------- Controlador: Agregar Prestamo ---------- **/
  public function addLoanController()
  {
    //*- Iniciar sesion para el controlador
    session_start(['name' => 'LoanC']);

    //*- Comprobar Cliete seleccionado
    if (empty($_SESSION['client_data'])) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No has seleccionado ningun cliente para realizar el prestamo",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //*- Comprobar Items seleccionados
    if ($_SESSION['total_item'] == 0) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No has seleccionado ningun item para realizar el prestamo",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Recibir datos del formu y limpiarlos
    $fecha_inicio = mainModel::cleanData($_POST['prestamo_fecha_inicio_reg']);
    $hora_inicio = mainModel::cleanData($_POST['prestamo_hora_inicio_reg']);
    $fecha_final = mainModel::cleanData($_POST['prestamo_fecha_final_reg']);
    $hora_final = mainModel::cleanData($_POST['prestamo_hora_final_reg']);
    $estado = mainModel::cleanData($_POST['prestamo_estado_reg']);
    $total_pagado = mainModel::cleanData($_POST['prestamo_pagado_reg']);
    $observacion = mainModel::cleanData($_POST['prestamo_observacion_reg']);

    //* - Verificar campos obligatorios
    if ($fecha_final == "" || $hora_final == "" || $total_pagado == "") {
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
    if (mainModel::verifyDate($fecha_inicio)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "La fecha de inicio no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])", $hora_inicio)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "La hora de inicio no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyDate($fecha_final)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "La fecha final no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])", $hora_final)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "La hora final no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if (mainModel::verifyData("[0-9.]{1,10}", $total_pagado)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El total depositado no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    if ($observacion != "") {
      if (mainModel::verifyData("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#\(\) ]{1,400}", $observacion)) {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "La observación no coincide con el formato solicitado",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    //*- Validar el estado del prestamo
    if ($estado != "Reservacion" && $estado != "Prestamo" && $estado != "Finalizado") {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "El estado del prestamo no coincide con el formato solicitado",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //*- Comprobar fechas
    if (strtotime($fecha_final) < strtotime($fecha_inicio)) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "La fecha de entrega no puede ser menor que la fecha de inicio del prestamo",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //* - Formatear el total pagado
    $total_prestamo = number_format($_SESSION['total_loan'], 2, ".", "");
    $total_pagado = number_format($total_pagado, 2, ".", "");

    //* - Formatear las fechas
    $fecha_inicio = date("Y-m-d", strtotime($fecha_inicio));
    $fecha_final = date("Y-m-d", strtotime($fecha_final));
    $hora_inicio = date("H:i a", strtotime($hora_inicio));
    $hora_final = date("H:i a", strtotime($hora_final));

    //*- Generar codigo unico del prestamo
    $correlativo = mainModel::simpleQuery("SELECT prestamo_id FROM prestamo");

    $correlativo = ($correlativo->rowCount()) + 1;

    $code = mainModel::randomCode("CP", 7, $correlativo);

    //*- Guardar el prestamo
    $data = [
      "codigo" => $code,
      "fecha_inicio" => $fecha_inicio,
      "hora_inicio" => $hora_inicio,
      "fecha_final" => $fecha_final,
      "hora_final" => $hora_final,
      "cantidad" => $_SESSION['total_item'],
      "total" => $total_prestamo,
      "pagado" => $total_pagado,
      "estado" => $estado,
      "observacion" => $observacion,
      "usuario" => $_SESSION['id_spm'],
      "cliente" => $_SESSION['client_data']['id']
    ];

    $add_loan = loanModel::addLoanModel($data);

    if ($add_loan->rowCount() != 1) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos podido agregar el prestamo 001, por favor intente nuevamente",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }

    //*- Agregar el pago del prestamo
    if ($total_pagado > 0) {
      $data_payment = [
        "total" => $total_pagado,
        "fecha" => $fecha_inicio,
        "prestamo_codigo" => $code
      ];

      $add_payment = loanModel::addLoanPaymentModel($data_payment);

      if ($add_payment->rowCount() != 1) {
        loanModel::deleteLoanModel($code, "_prestamo");

        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "No hemos podido agregar el pago 002 del prestamo, por favor intente nuevamente",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }
    }

    //*- Agregar el detalle del prestamo
    $detail_error = 0;

    foreach ($_SESSION['item_data'] as $items) {
      $costo = number_format($items['costo'], 2, ".", "");
      $descripcion = $items['codigo'] . "  " . $items['nombre'];

      $data_detail = [
        "cantidad" => $items['cantidad'],
        "formato" => $items['formato'],
        "tiempo" => $items['tiempo'],
        "costo_tiempo" => $costo,
        "descripcion" => $descripcion,
        "prestamo_codigo" => $code,
        "item" => $items['id']
      ];

      $add_detail = loanModel::addLoanDetailsModel($data_detail);

      if ($add_detail->rowCount() != 1) {
        $detail_error = 1;
        break;
      }
    }

    if ($detail_error == 0) {
      unset($_SESSION['client_data']);
      unset($_SESSION['item_data']);
      $alert = [
        "Alerts" => "reload",
        "Title" => "Prestamo Registrado!",
        "Text" => "Los datos del prestamo han sido guardados con exito en el sistema",
        "Tipe" => "success"
      ];
    } else {
      loanModel::deleteLoanModel($code, "_detalle");
      loanModel::deleteLoanModel($code, "_pago");
      loanModel::deleteLoanModel($code, "_prestamo");

      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No hemos podido agregar el pago 003 del prestamo, por favor intente nuevamente",
        "Tipe" => "error"
      ];
      exit();
    }
    echo json_encode($alert);
  } //*- Fin del controlador
}
