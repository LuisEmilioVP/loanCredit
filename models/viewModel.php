<?php
class viewModel
{
  /** ---------- Modelo: Obtener Vistas ---------- **/
  protected static function getViewsModel($view)
  {
    //* - Lista de todas las vistas del sistema
    $listViewSystem = ["client-list", "client-new", "client-search", "client-update", "company", "home", "item-list", "item-new", "item-search", "item-update", "reservation-list", "reservation-new", "reservation-pending", "reservation-reservation", "reservation-search", "reservation-update", "user-list", "user-new", "user-search", "user-update"];

    //* - Comprobar si la vista existe
    if (in_array($view, $listViewSystem)) {
      //* - Verificar que existen los archivos correspondientes
      if (is_file("./views/contents/" . $view . "-view.php")) {
        $content = "./views/contents/" . $view . "-view.php";
      } else {
        $content = "404";
      }
    } elseif ($view == "login" || $view == "index") {
      $content = "login";
    } else {
      $content = "404";
    }

    return $content;
  }
}
