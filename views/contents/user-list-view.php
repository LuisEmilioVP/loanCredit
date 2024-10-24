<?php
if ($_SESSION['role_spm'] != 1) {
  echo $ins_login->logoutController();
  exit();
}
?>

<!-- Page header -->
<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE USUARIOS
  </h3>
  <p class="text-justify">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit
    nostrum rerum animi natus beatae ex. Culpa blanditiis tempore amet alias
    placeat, obcaecati quaerat ullam, sunt est, odio aut veniam ratione.
  </p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a href="<?php echo APP_SERVER; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO USUARIO</a>
    </li>
    <li>
      <a class="active" href="<?php echo APP_SERVER; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
        LISTA DE
        USUARIOS</a>
    </li>
    <li>
      <a href="<?php echo APP_SERVER; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO</a>
    </li>
  </ul>
</div>

<!-- Content -->
<div class="container-fluid">
  <!-- Table and Paginador -->
  <?php
  require_once "./controllers/userController.php";

  $ins_user = new userController();
  echo $ins_user->paginationUsersController($page[1], 15, $_SESSION['role_spm'], $_SESSION['id_spm'], $page[0], "");
  ?>
</div>
