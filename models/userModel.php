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

  /** ---------- Modelo: Eliminar Usuarios ---------- **/
  protected static function deleteUserModel($id)
  {
    $sql = mainModel::connectionDb()->prepare("DELETE FROM usuario WHERE usuario_id = :id");

    $sql->bindParam(":id", $id);
    $sql->execute();

    return $sql;
  }

  /** ---------- Modelo: Seleccionar Datos de Usuarios ---------- **/
  protected static function selectUserModel($type, $id)
  {
    if ($type == "Unique") {
      $sql = mainModel::connectionDb()->prepare("SELECT * FROM usuario WHERE usuario_id = :id");
      $sql->bindParam(":id", $id);
    } elseif ($type == "Count") {
      $sql = mainModel::connectionDb()->prepare("SELECT usuario_id FROM usuario WHERE usuario_id!='1'");
    }

    $sql->execute();
    return $sql;
  }

  /** ---------- Modelo: Actualizar Usuarios ---------- **/
  protected static function updateUserModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("UPDATE usuario SET usuario_dni=:dni,usuario_nombre=:nombre,usuario_apellido=:apellido,usuario_telefono=:telefono,usuario_direccion=:direccion,usuario_email=:email,usuario_usuario=:usuario,usuario_clave=:clave,usuario_estado=:estado,usuario_privilegio=:privilegio WHERE usuario_id=:id");

    $sql->bindParam(":dni", $data['dni']);
    $sql->bindParam(":nombre", $data['nombre']);
    $sql->bindParam(":apellido", $data['apellido']);
    $sql->bindParam(":telefono", $data['telefono']);
    $sql->bindParam(":direccion", $data['direccion']);
    $sql->bindParam(":email", $data['email']);
    $sql->bindParam(":usuario", $data['usuario']);
    $sql->bindParam(":clave", $data['clave']);
    $sql->bindParam(":estado", $data['estado']);
    $sql->bindParam(":privilegio", $data['privilegio']);
    $sql->bindParam(":id", $data['id']);

    $sql->execute();
    return $sql;
  }
}
