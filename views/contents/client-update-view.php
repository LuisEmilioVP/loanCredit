<?php
if ($_SESSION['role_spm'] < 1 || $_SESSION['role_spm'] > 2) {
  echo $ins_login->logoutController();
  exit();
}
?>
<!-- Page header -->
<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR CLIENTE
  </h3>
  <p class="text-justify">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem odit amet asperiores quis minus, dolorem
    repellendus optio doloremque error a omnis soluta quae magnam dignissimos, ipsam, temporibus sequi, commodi
    accusantium!
  </p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a href="<?php echo APP_SERVER; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
    </li>
    <li>
      <a href="<?php echo APP_SERVER; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
        CLIENTES</a>
    </li>
    <li>
      <a href="<?php echo APP_SERVER; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE</a>
    </li>
  </ul>
</div>

<!-- Content here-->
<div class="container-fluid">
  <?php
  require_once "./controllers/clientController.php";
  $ins_client = new clientController();

  $data_client = $ins_client->selectClientController("Unique", $page[1]);

  if ($data_client->rowCount() == 1) {
    $content = $data_client->fetch();
  ?>
    <form class="form-neon FromAjax" action="<?php echo APP_SERVER; ?>ajax/clientAjax.php" method="POST" data-form="update" autocomplete="off">
      <input type="hidden" name="cliente_id_up" value="<?php echo $page[1] ?>">
      <fieldset>
        <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
        <div class="container-fluid">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="cliente_dni" class="bmd-label-floating">DNI</label>
                <input type="text" pattern="[0-9\-]{10,20}" class="form-control" name="cliente_dni_up" id="cliente_dni"
                  maxlength="20" value="<?php echo $content['cliente_dni']; ?>" required="">
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="cliente_nombre" class="bmd-label-floating">Nombre</label>
                <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,35}" class="form-control" name="cliente_nombre_up"
                  id="cliente_nombre" maxlength="35" value="<?php echo $content['cliente_nombre']; ?>" required="">
              </div>
            </div>

            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="cliente_apellido" class="bmd-label-floating">Apellido</label>
                <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,35}" class="form-control" name="cliente_apellido_up"
                  id="cliente_apellido" maxlength="35" value="<?php echo $content['cliente_apellido']; ?>" required="">
              </div>
            </div>

            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="cliente_telefono" class="bmd-label-floating">Teléfono</label>
                <input type="text" pattern="[0-9\(\)\+ ]{14,20}" class="form-control" name="cliente_telefono_up"
                  id="cliente_telefono" maxlength="20" value="<?php echo $content['cliente_telefono']; ?>">
              </div>
            </div>

            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="cliente_direccion" class="bmd-label-floating">Dirección</label>
                <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\(\).,#\- ]{6,190}" class="form-control"
                  name="cliente_direccion_up" id="cliente_direccion" maxlength="150" value="<?php echo $content['cliente_direccion']; ?>">
              </div>
            </div>

          </div>
        </div>
      </fieldset>
      <br><br><br>
      <p class="text-center" style="margin-top: 40px;">
        <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp;
          ACTUALIZAR</button>
      </p>
    </form>
  <?php } else { ?>
    <div class="alert alert-danger text-center" role="alert">
      <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
      <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
      <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
    </div>
  <?php } ?>
</div>
