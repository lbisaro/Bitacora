<?php
/**
 * Archivo de configuración del sistema en entorno local
 */
 
/** Datos referentes al entorno del sistema */
    //define('SERVER_ENTORNO' , 'Produccion');
    define('SERVER_ENTORNO' , 'Test');

    //Se define si se trata de un server WIN (No se requiere en server Linux)
    define('WIN_SERVER',true);


/** Conexion a Sql  */
    define('DB_HOST','localhost');
    define('DB_NAME','bitacora');
    define('DB_USER','root');
    define('DB_PASSWORD','');

/** Paths  */
    // Root dir
    define('ROOT_DIR', "C:\\xampp\htdocs\\bitacora");
    // Temp dir
    define('TMP_PATH', "C:\\xampp\\tmp");
    // Log dir
    define('LOG_PATH', 'C:\\xampp\\log\\bitacora');
    // Base Url
    define('BASE_URL', 'http://localhost/bitacora');

/** Mailer */
    define('MAILER_From'       , 'username@gmail.com');
    define('MAILER_SMTPAuth'   , true);
    define('MAILER_SMTPSecure' , "tls");                        // sets the prefix to the server
    define('MAILER_Host'       , "smtp.gmail.com");             // sets GMAIL as the SMTP server
    define('MAILER_Port'       , 587);                          // set the SMTP port for the GMAIL server
    define('MAILER_Username'   , "username@gmail.com");  // GMAIL username
    define('MAILER_Password'   , "password");

?>