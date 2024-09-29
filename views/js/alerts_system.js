const form_ajax = document.querySelectorAll('.FromAjax');

//* --- Enviar formularios con ajax --- *//
function sendFormAjax(form) {
	form.preventDefault();

	//* - Obtener datos del form
	let data = new FormData(this);
	//* - Obtener método del form
	let method_form = this.getAttribute('method');
	//* - Obtener el action del form
	let action_form = this.getAttribute('action');
	//* - Obtener el tipo de form
	let data_form = this.getAttribute('data-form');

	let header_form = new Headers();

	//** Array para pasar todas las configuraciones a las funciones para el envío y recibimiento de los datos */

	let config_form = {
		method: method_form,
		headers: header_form,
		mode: 'cors',
		cache: 'no-cache',
		body: data,
	};

	//** Definir los textos de las alertas, correspondiente a las acciones definidas (guardar, eliminar, limpiar, actualizar, buscar, etc.) */

	let text_alert;

	if (data_form === 'save') {
		text_alert = 'Los datos quedarán guardados en el sistema';
	} else if (data_form === 'delete') {
		text_alert = 'Los datos serán eliminados completamente del sistema';
	} else if (data_form === 'update') {
		text_alert = 'Los datos del sistema serán actualizados';
	} else if (data_form === 'search') {
		text_alert =
			'Se eliminará el término de búsqueda, tendrás que volver a escribir nuevamente';
	} else if (data_form === 'loans') {
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
			fetch(action_form, config_form)
				.then((response) => response.json())
				.then((responmse) => {
					return alertsSystem(responmse);
				});
			Swal.fire({
				title: '¡Bien!',
				text: 'La operación fue realizada correctamente.',
				type: 'success',
			});
		}
	});
}

/** --- Funcion: Detectar envio de form --- **/
form_ajax.forEach((forms) => {
	forms.addEventListener('submit', sendFormAjax);
});

/** --- Funcion: Alertas del Sistema --- **/
function alertsSystem(alert) {
	if (alert.AlertSys === 'simple') {
		Swal.fire({
			title: alert.Titulo,
			text: alert.Texto,
			type: alert.Tipo,
			confirmButtonText: 'Aceptar',
		});
	} else if (alert.AlertSys === 'reload') {
		Swal.fire({
			title: alert.Titulo,
			text: alert.Texto,
			type: alert.Tipo,
			confirmButtonText: 'Aceptar',
		}).then((result) => {
			if (result.value) {
				location.reload();
			}
		});
	} else if (alert.AlertSys === 'clean') {
		Swal.fire({
			title: alert.Titulo,
			text: alert.Texto,
			type: alert.Tipo,
			confirmButtonText: 'Aceptar',
		}).then((result) => {
			if (result.value) {
				document.querySelector('.FromAjax').reset();
			}
		});
	} else if (alert.AlertSys === 'redirect') {
		window.location.href = alert.URL;
	}
}
