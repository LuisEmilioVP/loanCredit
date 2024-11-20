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

  /** ---------- Modelo: Eliminar Item ---------- **/
  protected function deleteItemModel($id)
  {
    $sql = mainModel::connectionDb()->prepare("DELETE FROM item WHERE item_id = :id");

    $sql->bindParam(":id", $id);

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Seleccionar Datos de Item ---------- **/
  protected function selectItemModel($type, $id)
  {
    if ($type == "Unique") {
      $sql = mainModel::connectionDb()->prepare("SELECT * FROM item WHERE item_id = :id");
      $sql->bindParam(":id", $id);
    } else if ($type == "Count") {
      $sql = mainModel::connectionDb()->prepare("SELECT item_id FROM item");
    }

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Actualizar Item ---------- **/
  protected function updateItemModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("UPDATE item SET item_codigo=:codigo, item_nombre=:nombre, item_stock=:stock, item_estado=:estado, item_detalle=:detalle WHERE item_id=:id");

    $sql->bindParam(":codigo", $data["codigo"]);
    $sql->bindParam(":nombre", $data["nombre"]);
    $sql->bindParam(":stock", $data["stock"]);
    $sql->bindParam(":estado", $data["estado"]);
    $sql->bindParam(":detalle", $data["detalle"]);
    $sql->bindParam(":id", $data["id"]);

    $sql->execute();
    return $sql;
  }
}
