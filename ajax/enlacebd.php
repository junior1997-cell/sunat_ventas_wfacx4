<?php

require_once "../modelos/Conex.php";
require_once "../config/Conexion.php";

switch ($_GET["op"]){
    case 'empresa':
        $rspta = $conexion->query("SHOW DATABASES");
        echo json_encode($rspta->fetch_all());
        break;

    case 'verificarempresa':
        $empresa = $_POST['dbase'];
        $file = fopen("../config/global.php", "w+");
        fwrite($file, '<?php' . PHP_EOL);
        fwrite($file, 'define("DB_HOST","'.DB_HOST.'");' . PHP_EOL);
        fwrite($file, 'define("DB_USERNAME", "'.DB_USERNAME.'");' . PHP_EOL);
        fwrite($file, 'define("DB_PASSWORD", "'.DB_PASSWORD.'");' . PHP_EOL);
        fwrite($file, 'define("DB_NAME", "'.$empresa.'");' . PHP_EOL);  // Solo DB_NAME debe ser actualizada.
        fwrite($file, 'define("DB_ENCODE","'.DB_ENCODE.'");' . PHP_EOL);
        fwrite($file, 'define("PRO_NOMBRE","'.PRO_NOMBRE.'");' . PHP_EOL);
        fwrite($file, '?>' . PHP_EOL);
        fclose($file);
        break;
}




?>
