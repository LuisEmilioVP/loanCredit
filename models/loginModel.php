<?php

require_once "mainModel.php";

class loginModel extends mainModel
{
  /** ---------- Modelo: Iniciar Sesion ---------- **/
  protected static function loginModel($data)
  {
    $sql = mainModel::connectionDb()->prepare("SELECT * FROM usuario WHERE usuario_usuario = :user AND usuario_clave = :pass AND usuario_estado = 'Activa'");
    $sql->bindParam(":user", $data["user"]);
    $sql->bindParam(":pass", $data["pass"]);
    $sql->execute();
    return $sql;
  }
}
