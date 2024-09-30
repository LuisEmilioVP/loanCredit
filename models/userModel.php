<?php
//* - Includes
require_once "mainModel.php";

class userModel extends mainModel
{
  /** ---------- Modelo: Agregar Usuarios ---------- **/
  protected static function addUserModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("INSERT INTO usuario (usuario_dni, usuario_nombre, usuario_apellido, usuario_telefono, usuario_direccion, usuario_email, usuario_usuario, usuario_clave, usuario_estado, usuario_privilegio) VALUES (:dni, :nombre, :apellido, :telefono, :direccion, :email, :usuario, :clave, :estado, :privilegio)");

    $sql->bindParam(":dni", $data["dni"]);
    $sql->bindParam(":nombre", $data["nombre"]);
    $sql->bindParam(":apellido", $data["apellido"]);
    $sql->bindParam(":telefono", $data["telefono"]);
    $sql->bindParam(":direccion", $data["direccion"]);
    $sql->bindParam(":email", $data["email"]);
    $sql->bindParam(":usuario", $data["usuario"]);
    $sql->bindParam(":clave", $data["clave"]);
    $sql->bindParam(":estado", $data["estado"]);
    $sql->bindParam(":privilegio", $data["privilegio"]);

    $sql->execute();
    return $sql;
  }
  /** ---------- Modelo: Listar Usuarios ---------- **/
  /** ---------- Modelo: Actualizar Usuarios ---------- **/
  /** ---------- Modelo: Eliminar Usuarios ---------- **/
  /** ---------- Modelos: Buscar Usuarios ---------- **/
}
