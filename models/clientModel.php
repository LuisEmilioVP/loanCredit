<?php
//* - Includes
require_once "mainModel.php";

class clientModel extends mainModel
{
  /** ---------- Modelo: Agregar Cliente ---------- **/
  protected static function addClientModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("INSERT INTO cliente (cliente_dni, cliente_nombre, cliente_apellido, cliente_telefono, cliente_direccion) VALUES (:dni, :nombre, :apellido, :telefono, :direccion)");

    $sql->bindParam(":dni", $data["dni"]);
    $sql->bindParam(":nombre", $data["nombre"]);
    $sql->bindParam(":apellido", $data["apellido"]);
    $sql->bindParam(":telefono", $data["telefono"]);
    $sql->bindParam(":direccion", $data["direccion"]);

    $sql->execute();
    return $sql;
  }
  /** ---------- Modelo: Eliminar Cliente ---------- **/
  protected static function deleteClientModel($id)
  {
    $sql = mainModel::connectionDb()->prepare("DELETE FROM cliente WHERE cliente_id = :id");

    $sql->bindParam(":id", $id);

    $sql->execute();
    return $sql;
  }

  /** ---------- modelos: Seleccionar Cliente ---------- **/
  protected static function selectClientModel($type, $id)
  {
    if ($type == "Unique") {
      $sql = mainModel::connectionDb()->prepare("SELECT * FROM cliente WHERE cliente_id = :id");
      $sql->bindParam(":id", $id);
    } else if ($type == "Count") {
      $sql = mainModel::connectionDb()->prepare("SELECT cliente_id FROM cliente");
    }

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Actualizar Cliente ---------- **/
  protected static function updateClientModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("UPDATE cliente SET cliente_dni=:din, cliente_nombre=:nombre, cliente_apellido=:apellido, cliente_telefono=:telefono, cliente_direccion=:direccion WHERE cliente_id=:id");

    $sql->bindParam(":din", $data["dni"]);
    $sql->bindParam(":nombre", $data["nombre"]);
    $sql->bindParam(":apellido", $data["apellido"]);
    $sql->bindParam(":telefono", $data["telefono"]);
    $sql->bindParam(":direccion", $data["direccion"]);
    $sql->bindParam(":id", $data["id"]);

    $sql->execute();
    return $sql;
  }
}
