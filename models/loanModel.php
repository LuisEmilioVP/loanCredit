<?php
//* - Includes
require_once "mainModel.php";

class loanModel extends mainModel
{
  /** ---------- Modelo: Agregar Prestamos ---------- **/
  protected static function addLoanModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("INSERT INTO prestamo (prestamo_codigo, prestamo_fecha_inicio, prestamo_hora_inicio, prestamo_fecha_final, prestamo_hora_final, prestamo_cantidad, prestamo_total, prestamo_pagado, prestamo_estado, prestamo_observacion, usuario_id, cliente_id) VALUES (:codigo, :fecha_inicio, :hora_inicio, :fecha_final, :hora_final, :cantidad, :total, :pagado, :estado, :observacion, :usuario, :cliente)");

    $sql->bindParam(":codigo", $data["codigo"]);
    $sql->bindParam(":fecha_inicio", $data["fecha_inicio"]);
    $sql->bindParam(":hora_inicio", $data["hora_inicio"]);
    $sql->bindParam(":fecha_final", $data["fecha_final"]);
    $sql->bindParam(":hora_final", $data["hora_final"]);
    $sql->bindParam(":cantidad", $data["cantidad"]);
    $sql->bindParam(":total", $data["total"]);
    $sql->bindParam(":pagado", $data["pagado"]);
    $sql->bindParam(":estado", $data["estado"]);
    $sql->bindParam(":observacion", $data["observacion"]);
    $sql->bindParam(":usuario", $data["usuario"]);
    $sql->bindParam(":cliente", $data["cliente"]);

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Agregar Detalle Prestamos ---------- **/
  protected static function addLoanDetailsModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("INSERT INTO detalle (detalle_cantidad, detalle_formato, detalle_tiempo, detalle_costo_tiempo, detalle_descripcion, prestamo_codigo, item_id) VALUES (:cantidad, :formato, :tiempo, :costo_tiempo, :descripcion, :prestamo_codigo, :item)");

    $sql->bindParam(":cantidad", $data["cantidad"]);
    $sql->bindParam(":formato", $data["formato"]);
    $sql->bindParam(":tiempo", $data["tiempo"]);
    $sql->bindParam(":costo_tiempo", $data["costo_tiempo"]);
    $sql->bindParam(":descripcion", $data["descripcion"]);
    $sql->bindParam(":prestamo_codigo", $data["prestamo_codigo"]);
    $sql->bindParam(":item", $data["item"]);

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Agregar Pago de Prestamos ---------- **/
  protected static function addLoanPaymentModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("INSERT INTO pago (pago_total, pago_fecha, prestamo_codigo) VALUES (:total, :fecha, :prestamo_codigo)");

    $sql->bindParam(":total", $data["total"]);
    $sql->bindParam(":fecha", $data["fecha"]);
    $sql->bindParam(":prestamo_codigo", $data["prestamo_codigo"]);

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Eliminar Prestamo ---------- **/
  protected static function deleteLoanModel($code, $type)
  {
    if ($type == "_prestamo") {
      $sql = mainModel::connectionDb()->prepare("DELETE FROM prestamo WHERE prestamo_codigo = :code");
    } else if ($type == "_detalle") {
      $sql = mainModel::connectionDb()->prepare("DELETE FROM detalle WHERE prestamo_codigo = :code");
    } else if ($type == "_pago") {
      $sql = mainModel::connectionDb()->prepare("DELETE FROM pago WHERE prestamo_codigo = :code");
    }

    $sql->bindParam(":code", $code);

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Seleccionar Prestamo ---------- **/
  protected static function selectLoanModel($type, $id)
  {
    if ($type == "Unique") {
      $sql = mainModel::connectionDb()->prepare("SELECT * FROM prestamo WHERE prestamo_codigo = :id");

      $sql->bindParam(":id", $id);
    } else if ($type == "Count_Reserve") {
      $sql = mainModel::connectionDb()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado = 'Reservación'");
    } else if ($type == "Count_Loan") {
      $sql = mainModel::connectionDb()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado = 'Prestamo'");
    } else if ($type == "Count_Finished") {
      $sql = mainModel::connectionDb()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado = 'Finalizado'");
    } else if ($type == "Count") {
      $sql = mainModel::connectionDb()->prepare("SELECT prestamo_id FROM prestamo");
    } else if ($type == "Details") {
      $sql = mainModel::connectionDb()->prepare("SELECT * FROM detalle WHERE prestamo_codigo = :code");

      $sql->bindParam(":code", $id);
    } else if ($type == "Payment") {
      $sql = mainModel::connectionDb()->prepare("SELECT * FROM pago WHERE prestamo_codigo = :code");

      $sql->bindParam(":code", $id);
    }

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Actualizar Prestamo - Estado ---------- **/
  protected static function updateLoanStateModel($data)
  {
    if ($data["type"] == "Pago") {
      $sql = mainModel::connectionDb()->prepare("UPDATE prestamo SET prestamo_pagado=:monto WHERE prestamo_codigo=:code");

      $sql->bindParam(":monto", $data["monto"]);
    } else if ($data["type"] == "Prestado") {
      $sql = mainModel::connectionDb()->prepare("UPDATE prestamo SET prestamo_estado=:estado, prestamo_observacion=:observacion WHERE prestamo_codigo=:code");

      $sql->bindParam(":estado", $data["estado"]);
      $sql->bindParam(":observacion", $data["observacion"]);
    }
    $sql->bindParam(":code", $data["code"]);
    $sql->execute();
    return $sql;
  }
}
