<script>
  function search_client() {
    let input_item = document.querySelector('#input_cliente').value;

    //* - Eliminar espacios en blanco
    input_item = input_item.trim();

    if (input_item != '') {
      // console.log('valor del input', input_item);
      let data = new FormData();
      data.append('seaech_client', input_item);

      fetch('<?php echo APP_SERVER; ?>ajax/loanAjax.php', {
          method: 'POST',
          body: data,
        })
        .then((response) => response.text())
        .then((response) => {
          let client_table = document.querySelector('#tabla_clientes');
          client_table.innerHTML = response;
        })
        .catch((error) => {
          console.error('Error: ', error);
        });
    } else {
      Swal.fire({
        title: 'Ocurrió un error inesperado',
        text: 'Debes ingresar algunos de los datos requeridos para la búsqueda.',
        type: 'error',
        confirmButtonText: 'Aceptar',
      });
    }
  }

  function add_client(id) {
    $('#ModalCliente').modal('hide');

    Swal.fire({
      title: '¿Quieres agregar este cliente?',
      text: 'El cliente será agregado para realizar un préstamo',
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, agregar',
      cancelButtonText: 'No, cancelar',
    }).then((result) => {
      if (result.value) {
        let add_data = new FormData();
        add_data.append('add_client_id', id);

        fetch('<?php echo APP_SERVER; ?>ajax/loanAjax.php', {
            method: 'POST',
            body: add_data,
          })
          .then((response) => response.json())
          .then((response) => {
            return alerts_ajax(response);
          })
          .catch((error) => {
            console.error('Error: ', error);
          });
      } else {
        $('#ModalCliente').modal('show');
      }
    });
  }

  function search_item() {
    let input_item = document.querySelector('#input_item').value;

    //* - Eliminar espacios en blanco
    input_item = input_item.trim();

    if (input_item != '') {
      // console.log('valor del input', input_item);
      let data = new FormData();
      data.append('seaech_item', input_item);

      fetch('<?php echo APP_SERVER; ?>ajax/loanAjax.php', {
          method: 'POST',
          body: data,
        })
        .then((response) => response.text())
        .then((response) => {
          let item_table = document.querySelector('#tabla_items');
          item_table.innerHTML = response;
        })
        .catch((error) => {
          console.error('Error: ', error);
        });
    } else {
      Swal.fire({
        title: 'Ocurrió un error inesperado',
        text: 'Debes ingresar algunos de los datos requeridos para la búsqueda.',
        type: 'error',
        confirmButtonText: 'Aceptar',
      });
    }
  }

  function add_item(id) {
    $('#ModalItem').modal('hide');
    $('#ModalAgregarItem').show(100);
    $('#ModalAgregarItem').modal('show');
    document.querySelector('#add_item_id').setAttribute('value', id);
  }

  function show_modal_item() {
    $('#ModalAgregarItem').modal('hide');
    $('#ModalItem').show(100);
    $('#ModalItem').modal('show');
  }
</script>
