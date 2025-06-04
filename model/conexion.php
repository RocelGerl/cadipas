<?php
function conectarDB()
{
    $conn = new mysqli("localhost", "mcovrcis_cadipas", "@dr13LR0c", "mcovrcis_cadipas", "3306");
    $conn->set_charset("utf8");
    date_default_timezone_set("America/La_Paz");
    
    return $conn;
}

?>

