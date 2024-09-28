<?php
/*----| Configuraciones de Encriptación y Conexión a la BD |----*/

//*- Parametros de la base de datos
const DB_HOST = 'localhost';
const DB_NAME = 'bd_loancredit';
const DB_USER = 'root';
const DB_PASS = '';
const DB_PORT = '3306';
//*- Estructura PDO para la conexión
const SGBD = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT;

//*- Procesar por hash
const METHOD_HASH = 'AES-256-CBC';

//*- Llave secreta
const SECRET_KEY = '$PRESTAMO@2024$';

//*- Identificador único
const SECRET_IV = '796011';
