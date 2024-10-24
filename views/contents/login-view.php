<div class="login-container">
  <div class="login-content">
    <p class="text-center">
      <img src="<?php echo APP_SERVER; ?>views/assets/img/logo.png" alt="LoanCredit-logo" class="img-fluid">
    </p>
    <h2 class="text-center">Loan Credit</h2>
    <hr class="line-separator">
    <p class="text-center mt-2">Iniciar Sesion con tu cuenta</p>

    <form action="" method="POST" autocomplete="off">
      <div class="form-group">
        <label for="UserName" class="bmd-label-floating phpting"><i class="fas fa-user-secret"></i> &nbsp; Usuario</label>
        <input type="text" class="form-control" id="UserName" name="user_log" pattern="[a-zA-Z0-9]{3,35}" maxlength="35"
          required="">
      </div>
      <div class="form-group">
        <label for="UserPassword" class="bmd-label-floating"><i class="fas fa-key"></i> &nbsp; Contraseña</label>
        <input type="password" class="form-control" id="UserPassword" name="pass_log" pattern="[a-zA-Z0-9$@.\-]{7,100}"
          maxlength="100" required="">
      </div>
      <button type="submit" class="btn-login text-center">LOG IN</button>
    </form>
  </div>
</div>

<?php
if (isset($_POST['user_log']) && isset($_POST['pass_log'])) {
  require_once "./controllers/loginController.php";
  $ins_login = new loginController();

  echo $ins_login->loginController();
}
?>
