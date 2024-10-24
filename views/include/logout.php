<script>
  let btn_logout = document.querySelector('.btn-exit-system');

  btn_logout.addEventListener('click', (e) => {
    e.preventDefault();

    Swal.fire({
      title: '¿Estás seguro de cerrar la sesión?',
      text: 'Está a punto de cerrar la sesión y salir del sistema',
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, salir',
      cancelButtonText: 'No, cancelar'
    }).then((result) => {
      if (result.value) {
        let url = '<?php echo APP_SERVER; ?>ajax/loginAjax.php';
        let token = '<?php echo $ins_login->encryption($_SESSION['token_spm']); ?>';
        let user = '<?php echo $ins_login->encryption($_SESSION['user_spm']); ?>';

        //* - Enciar datos de las variables
        let data = new FormData();
        data.append('token', token);
        data.append('user', user);

        fetch(url, {
            method: 'POST',
            body: data
          })
          .then((response) => response.json())
          .then((response) => {
            return alerts_ajax(response);
            console.log('response es: ', response);
          })
          .catch((error) => {
            console.error('Error: ', error);
          });
      }
    });
  });
</script>
