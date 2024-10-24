<!-- Page header -->
<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i> &nbsp; DASHBOARD
  </h3>
  <p class="text-justify">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit
    nostrum rerum animi natus beatae ex. Culpa blanditiis tempore amet alias
    placeat, obcaecati quaerat ullam, sunt est, odio aut veniam ratione.
  </p>
</div>

<!-- Content -->
<div class="full-box tile-container">
  <a href="<?php echo APP_SERVER; ?>client-list/" class="tile">
    <div class="tile-tittle">Clientes</div>
    <div class="tile-icon">
      <i class="fas fa-users fa-fw"></i>
      <p>5 Registrados</p>
    </div>
  </a>

  <a href="<?php echo APP_SERVER; ?>item-list/" class="tile">
    <div class="tile-tittle">Items</div>
    <div class="tile-icon">
      <i class="fas fa-pallet fa-fw"></i>
      <p>9 Registrados</p>
    </div>
  </a>

  <a href="<?php echo APP_SERVER; ?>reservation-reservation/" class="tile">
    <div class="tile-tittle">Reservaciones</div>
    <div class="tile-icon">
      <i class="far fa-calendar-alt fa-fw"></i>
      <p>30 Registradas</p>
    </div>
  </a>

  <a href="<?php echo APP_SERVER; ?>reservation-pending/" class="tile">
    <div class="tile-tittle">Prestamos</div>
    <div class="tile-icon">
      <i class="fas fa-hand-holding-usd fa-fw"></i>
      <p>200 Registrados</p>
    </div>
  </a>

  <a href="<?php echo APP_SERVER; ?>reservation-list/" class="tile">
    <div class="tile-tittle">Finalizados</div>
    <div class="tile-icon">
      <i class="fas fa-clipboard-list fa-fw"></i>
      <p>700 Registrados</p>
    </div>
  </a>

  <?php
  if ($_SESSION['role_spm'] == 1) {
    require_once "./controllers/userController.php";
    $ins_userUpdate = new userController();
    $countUsers = $ins_userUpdate->selectUserController("Count", 0);
  ?>
    <a href="<?php echo APP_SERVER; ?>user-list/" class="tile">
      <div class="tile-tittle">Usuarios</div>
      <div class="tile-icon">
        <i class="fas fa-user-secret fa-fw"></i>
        <p><?php echo $countUsers->rowCount(); ?> Registrados</p>
      </div>
    </a>
  <?php } ?>

  <a href="<?php echo APP_SERVER; ?>company/" class="tile">
    <div class="tile-tittle">Empresa</div>
    <div class="tile-icon">
      <i class="fas fa-store-alt fa-fw"></i>
      <p>1 Registrada</p>
    </div>
  </a>
</div>
