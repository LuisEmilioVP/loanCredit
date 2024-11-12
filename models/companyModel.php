<?php
//* - Includes
require_once "mainModel.php";

class companyModel extends mainModel
{
  /** ---------- Modelo: Seleccionar Datos de Empresa ---------- **/
  protected static function selectCompanyModel()
  {
    $sql = mainModel::connectionDb()->prepare("SELECT * FROM empresa");

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Agregar Empresa ---------- **/
  protected function addCompanyModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("INSERT INTO empresa (empresa_nombre, empresa_email, empresa_telefono, empresa_direccion) VALUES (:nombre, :email, :telefono, :direccion)");

    $sql->bindParam(":nombre", $data["nombre"]);
    $sql->bindParam(":email", $data["email"]);
    $sql->bindParam(":telefono", $data["telefono"]);
    $sql->bindParam(":direccion", $data["direccion"]);

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Actualizar Empresa ---------- **/
  protected function updateCompanyModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("UPDATE empresa SET empresa_nombre=:nombre, empresa_email=:email, empresa_telefono=:telefono, empresa_direccion=:direccion WHERE empresa_id=:id");

    $sql->bindParam(":nombre", $data["nombre"]);
    $sql->bindParam(":email", $data["email"]);
    $sql->bindParam(":telefono", $data["telefono"]);
    $sql->bindParam(":direccion", $data["direccion"]);
    $sql->bindParam(":id", $data["id"]);

    $sql->execute();
    return $sql;
  }
}
