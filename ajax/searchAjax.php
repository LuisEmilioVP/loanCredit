<?php
//* - Includes
session_start(['name' => 'LoanC']);

require_once "../config/app.php";


if (isset($_POST['search_init']) || isset($_POST['delete_search']) || isset($_POST['date_start']) || isset($_POST['date_end'])) {
  $form_url_search = [
    "user" => "user-search",
    "client" => "client-search",
    "item" => "item-search",
    "reservation" => "reservation-search"
  ];

  if (isset($_POST['model_data'])) {
    $model = $_POST['model_data'];

    if (!isset($form_url_search[$model])) {
      $alert = [
        "Alerts" => "simple",
        "Title" => "Ocurrió un error inesperado",
        "Text" => "No podemos continuar con la búsqueda debido a un error.",
        "Tipe" => "error"
      ];
      echo json_encode($alert);
      exit();
    }
  } else {
    $alert = [
      "Alerts" => "simple",
      "Title" => "Ocurrió un error inesperado",
      "Text" => "No se puede continuar con la búsqueda debido a un error de configuración.",
      "Tipe" => "error"
    ];
    echo json_encode($alert);
    exit();
  }

  if ($model == "reservation") {
    $init_date_search = "date_init_" . $model;
    $end_date_search = "date_finish_" . $model;

    //* - Iniciar busqueda
    if (isset($_POST['date_start']) || isset($_POST['date_end'])) {

      if ($_POST['date_start'] == "" || $_POST['date_end'] == "") {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "Por favor ingrese una fecha de inicio y de fin.",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }

      $_SESSION[$init_date_search] = $_POST['date_start'];
      $_SESSION[$end_date_search] = $_POST['date_end'];
    }

    //* - Eliminar busqueda
    if (isset($_POST['delete_search'])) {
      unset($_SESSION[$init_date_search]);
      unset($_SESSION[$end_date_search]);
    }
  } else {
    $search_var = "search_" . $model;
    //* - Iniciar busqueda
    if (isset($_POST['search_init'])) {

      if ($_POST['search_init'] == "") {
        $alert = [
          "Alerts" => "simple",
          "Title" => "Ocurrió un error inesperado",
          "Text" => "Por favor ingrese un criterio de búsqueda.",
          "Tipe" => "error"
        ];
        echo json_encode($alert);
        exit();
      }

      $_SESSION[$search_var] = $_POST['search_init'];
    }

    //* - Eliminar busqueda
    if (isset($_POST['delete_search'])) {
      unset($_SESSION[$search_var]);
    }
  }

  //* - Redireccionar
  $_url = $form_url_search[$model];

  $alert = [
    "Alerts" => "redirect",
    "Url" => APP_SERVER . $_url . "/"
  ];

  echo json_encode($alert);
} else {
  session_unset();
  session_destroy();
  header('Location: ' . APP_SERVER . 'login/');
  exit();
}
