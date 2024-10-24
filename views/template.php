<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
  <!-- Favicon -->
  <link rel="shortcut icon" href="<?php echo APP_SERVER; ?>views/assets/img/Favicon.png" type="image/x-icon">
  <title><?php echo APP_COMPANY; ?></title>
  <!-- Include Css files   -->
  <?php include "./views/include/styles.php"; ?>
</head>

<body>
  <?php
  $petitionAjax = false;
  require_once "./controllers/viewController.php";
  $IntaciaView = new viewController();

  $resultView = $IntaciaView->getViewsController();

  //*Detectar si estamos usando el Login o no
  if ($resultView == "login" || $resultView == "404") {
    require_once "./views/contents/" . $resultView . "-view.php";
  } else {
    //* - Iniciar la sesión
    session_start(['name' => 'LoanC']);

    $page = explode("/", $_GET['view']);
    //* - Forzar Cierre de Sesion
    require_once "./controllers/loginController.php";
    $ins_login = new loginController();

    if (!isset($_SESSION['token_spm']) || !isset($_SESSION['user_spm']) || !isset($_SESSION['role_spm']) || !isset($_SESSION['id_spm'])) {
      echo $ins_login->logoutController();
      exit();
    }
  ?>
    <!-- Main container -->
    <main class="full-box main-container">
      <!-- Nav lateral include start -->
      <?php include "./views/include/navlateral.php"; ?>
      <!-- Nav lateral include end -->

      <!-- Page content navBar include start -->
      <section class="full-box page-content">
        <?php
        include "./views/include/navbar.php";

        //*Icluir las Vistas
        include $resultView;
        ?>
      </section>
      <!-- Page content navBar include end -->
    </main>

    <!-- Include JavaScript files -->
  <?php
    include "./views/include/logout.php";
  }
  include "./views/include/scritps.php";
  ?>
</body>

</html>
