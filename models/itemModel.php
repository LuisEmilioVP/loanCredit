<?php
//* - Includes
require_once "mainModel.php";

class itemModel extends mainModel
{
  /** ---------- Modelo: Agregar Item ---------- **/
  protected function addItemModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("INSERT INTO item (item_codigo, item_nombre, item_stock, item_estado, item_detalle) VALUES (:codigo, :nombre, :stock, :estado, :detalle)");

    $sql->bindParam(":codigo", $data["codigo"]);
    $sql->bindParam(":nombre", $data["nombre"]);
    $sql->bindParam(":stock", $data["stock"]);
    $sql->bindParam(":estado", $data["estado"]);
    $sql->bindParam(":detalle", $data["detalle"]);

    $sql->execute();
    return $sql;
  }
  /** ---------- Modelo: Actualizar Item ---------- **/
  /** ---------- Modelo: Eliminar Item ---------- **/
}
