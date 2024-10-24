const form_ajax = document.querySelectorAll('.FromAjax');

//* --- Enviar formularios con ajax --- *//
function sendFormAjax(form) {
	form.preventDefault();

	//* - Obtener datos del form
	let data = new FormData(this);
	//* - Obtener método del form
	let method = this.getAttribute('method');
	//* - Obtener el action del form
	let action = this.getAttribute('action');
	//* - Obtener el tipo de form
	let type = this.getAttribute('data-form');

	let header = new Headers();

	//** Array para pasar todas las configuraciones a las funciones para el envío y recibimiento de los datos */

	let config = {
		method: method,
		headers: header,
		mode: 'cors',
		cache: 'no-cache',
		body: data,
	};

	//** Definir los textos de las alertas, correspondiente a las acciones definidas (guardar, eliminar, limpiar, actualizar, buscar, etc.) */

	let text_alert;

	if (type === 'save') {
		text_alert = 'Los datos quedarán guardados en el sistema';
	} else if (type === 'delete') {
		text_alert = 'Los datos serán eliminados completamente del sistema';
	} else if (type === 'update') {
		text_alert = 'Los datos del sistema serán actualizados';
	} else if (type === 'search') {
		text_alert =
			'Se eliminará el término de búsqueda, tendrás que volver a escribir nuevamente';
	} else if (type === 'loans') {
		text_alert =
			' Desea remover los datos seleccionados para préstamos o reservaciones';
	} else {
		text_alert = '¿Quieres realizar la operación Solicitada?';
	}

	//** --- Función para mostrar las alertas --- */
	Swal.fire({
		title: '¿Estás seguro?',
		text: text_alert,
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Aceptar',
		cancelButtonText: 'Cancelar',
	}).then((result) => {
		if (result.value) {
			fetch(action, config)
				.then((response) => response.json())
				.then((response) => {
					return alerts_ajax(response);
				})
				.catch((error) => {
					console.error('Error: ', error);
				});
		}
	});
}

/** --- Funcion: Detectar envio de form --- **/
form_ajax.forEach((forms) => {
	forms.addEventListener('submit', sendFormAjax);
});

/** --- Funcion: Alertas del Sistema --- **/
function alerts_ajax(alert) {
	if (alert.Alerts === 'simple') {
		Swal.fire({
			title: alert.Title,
			text: alert.Text,
			type: alert.Tipe,
			confirmButtonText: 'Aceptar',
		});
	} else if (alert.Alerts === 'reload') {
		Swal.fire({
			title: alert.Title,
			text: alert.Text,
			type: alert.Tipe,
			confirmButtonText: 'Aceptar',
		}).then((result) => {
			if (result.value) {
				location.reload();
			}
		});
	} else if (alert.Alerts === 'clean') {
		Swal.fire({
			title: alert.Title,
			text: alert.Text,
			type: alert.Tipe,
			confirmButtonText: 'Aceptar',
		}).then((result) => {
			if (result.value) {
				document.querySelector('.FromAjax').reset();
			}
		});
	} else if (alert.Alerts === 'redirect') {
		window.location.href = alert.Url;
	}
}
