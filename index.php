<?php
//* - Includes
require_once "./config/app.php";
require_once "./controllers/viewController.php";

//** -- Controlador: Obtener Plantillas -- **/
$template = new viewController();
$template->getTemplatesController();
