<?php
//* - Detectar la petición ajax o no
if ($petitionAjax) {
  require_once "../config/server.php";
} else {
  require_once "../config/server.php";
}

final class mainModel
{
  /** ---------- Funcion: Conextar a la Base de Dato ---------- **/
  protected static function connectionDb()
  {
    $pdo = new PDO(SGBD, DB_USER, DB_PASS);
    $pdo->exec("SET CHARACTER SET utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  }

  /** ---------- Funcion: Ejecutar Consultas Simples ---------- **/
  protected static function simpleQuery($query)
  {
    $sql = self::connectionDb()->prepare($query);
    $sql->execute();
    return $sql;
  }

  /** ---------- Funcion: Encriptar Cadenas ---------- **/
  public function encryption($string)
  {
    $output = false;
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    $output = openssl_encrypt($string, METHOD_HASH, $key, 0, $iv);
    $output = base64_encode($output);
    return $output;
  }

  /** ---------- Funcion: Desencriptar Cadenas ---------- **/
  protected static function decryption($string)
  {
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    $output = openssl_decrypt(base64_decode($string), METHOD_HASH, $key, 0, $iv);
    return $output;
  }

  /** ---------- Funcion: General Codigos Aleaterios ---------- **/
  protected static function randomCode($length, $chars, $numbers)
  {
    for ($i = 1; $i <= $length; $i++) {
      $random = rand(0, 9);
      $chars .= $random;
    }
    return $chars . '-' . $numbers;
  }

  /** ---------- Funcion: Limpiar Cadenas ---------- **/
  protected static function cleanData($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = str_ireplace("<scrip>", "", $data);
    $data = str_ireplace("</script>", "", $data);
    $data = str_ireplace("</script type=", "", $data);
    $data = str_ireplace("SELECT * FROM", "", $data);
    $data = str_ireplace("INSERT INTO", "", $data);
    $data = str_ireplace("DELETE FROM", "", $data);
    $data = str_ireplace("DROP TABLE", "", $data);
    $data = str_ireplace("DROP DATABASE", "", $data);
    $data = str_ireplace("TRUNCATE TABLE", "", $data);
    $data = str_ireplace("SHOW TABLES", "", $data);
    $data = str_ireplace("SHOW DATABASES", "", $data);
    $data = str_ireplace("union", "", $data);
    $data = str_ireplace("select", "", $data);
    $data = str_ireplace("from", "", $data);
    $data = str_ireplace("drop", "", $data);
    $data = str_ireplace("table", "", $data);
    $data = str_ireplace("database", "", $data);
    $data = str_ireplace("truncate", "", $data);
    $data = str_ireplace("delete", "", $data);
    $data = str_ireplace("script", "", $data);
    $data = str_ireplace("script type", "", $data);
    $data = str_ireplace("script type=", "", $data);
    $data = str_ireplace("script type = ", "", $data);
    $data = str_ireplace("--", "", $data);
    $data = str_ireplace("<", "", $data);
    $data = str_ireplace(">", "", $data);
    $data = str_ireplace("[", "", $data);
    $data = str_ireplace("]", "", $data);
    $data = str_ireplace("{", "", $data);
    $data = str_ireplace("}", "", $data);
    $data = str_ireplace("^", "", $data);
    $data = str_ireplace("=", "", $data);
    $data = str_ireplace("==", "", $data);
    $data = str_ireplace("===", "", $data);
    $data = str_ireplace("^", "", $data);
    $data = str_ireplace(";", "", $data);
    $data = str_ireplace("::", "", $data);
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
  }

  /** ---------- Funcion: Verificar Datos ---------- **/
  protected static function verifyData($filter, $data)
  {
    if (preg_match("/^" . $filter . "$/", $data)) {
      return false;
    } else {
      return true;
    }
  }

  /** ---------- Funcion: Verificar Fechas ---------- **/
  protected static function verifyDate($date)
  {
    $values = explode('-', $date);
    if (count($values) == 3 && checkdate($values[1], $values[2], $values[0])) {
      return false;
    } else {
      return true;
    }
  }

  /** ---------- Funcion: Paginacion de Tablas ---------- **/
  protected static function pagination($url, $page, $nextPage, $buttons)
  {
    $table = '<nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">';

    if ($page == 1) {
      $table .= '<li class="page-item disabled"><a class="page-link"><i class="fa-solid fa-angles-left"></i></a></li>';
    } else {
      $table .= '
        <li class="page-item"><a class="page-link" href="' . $url . '1/"><i class="fa-solid fa-angles-left"></i></a></li>

        <li class="page-item"><a class="page-link" href="' . $url . ($page - 1) . '/">Anterior</a></li>
        ';
    }

    $ci = 0;
    for ($i = $page; $i < $nextPage; $i++) {
      if ($ci >= $buttons) {
        break;
      }

      if ($page == $i) {
        $table .= '<li class="page-item"><a class="page-link active"
          href="' . $url . $i . '/">' . $i . '</a></li>';
      } else {
        $table .= '<li class="page-item"><a class="page-link"
          href="' . $url . $i . '/">' . $i . '</a></li>';
      }

      $ci++;
    }

    if ($page == $nextPage) {
      $table .= '<li class="page-item disabled"><a class="page-link"><i class="fa-solid fa-angles-right"></i></a></li>';
    } else {
      $table .= '
        <li class="page-item"><a class="page-link" href="' . $url . ($page + 1) . '/">Siguiente</a></li>

        <li class="page-item"><a class="page-link" href="' . $url . $nextPage . '/"><i class="fa-solid fa-angles-right"></i></a></li>
        ';
    }

    $table .= '</ul></nav>';
    return $table;
  }
}
