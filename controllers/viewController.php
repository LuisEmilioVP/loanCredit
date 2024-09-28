<?php
//** -- Incudes -- **/
//* - Models
require_once "./models/viewModel.php";

class viewController extends viewModel
{
  /** ---------- Controlador: Obtener Plantillas ---------- **/
  public function getTemplatesController()
  {
    return require_once "./views/template.php";
  }

  /** ---------- Controlador: Obtener Vistas ---------- **/
  public function getViewsController()
  {
    //* - Comprobar sii esta definida la variable $_GET
    if (isset($_GET["view"])) {
      $route = explode("/", $_GET["view"]);
      $response = viewModel::getViewsModel($route[0]);
    } else {
      $response = "login";
    }

    return $response;
  }
}
