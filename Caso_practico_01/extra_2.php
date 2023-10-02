<?php
/**
 * Vamos a realizar una pequeña vista consumiendo una API externa
 * https://633dc0417e19b17829155626.mockapi.io/api/employees
 *
 * La URL anterior nos devuelve una lista de empleados
 * 1. Consulta la URL para obtener el json
 * 2. Transforma el contenido recibido en "algo" manejable por PHP
 * 3. Pinta cada uno de los empleados por pantalla
 *      [ID, imagen, nombre, ciudad, empresa, fecha de creación en formato Español]
 * --> Opción fácil, pintar por consola. Las imágenes no se verán, así que filtra la url y muestra sólo el nombre "1128.jpg"
 * --> Opción algo menos fácil, pintar en un HTML simple accediendo desde el navegador. Sin complicaciones
 *
 * EXTRA:
 * La API admite varios parámetros de filtrado: https://github.com/mockapi-io/docs/wiki/Code-examples#filtering
 * Ajusta tu script para admitir parámetros opcionales y ejecutarlo de este modo:
 *      php consume_api.php [pagina] [limite] [busqueda]
 *      Ejemplo: "php consume_api.php 2 5 ar"
 *      Ejecutar la línea anterior debería llamar a la API incluyendo lo siguiente:
 *      https://633dc0417e19b17829155626.mockapi.io/api/employees?page=2&limit=5&search=ar
 *
 * Investiga cómo leer los parámetros recibidos por consola o navegador ;)
 * Navegador:
 *  a) http://localhost:8000/01.%20Sintaxis%20PHP/04_caso_practico/extra2.php/2/5/ar
 *  b) http://localhost:8000/01.%20Sintaxis%20PHP/04_caso_practico/extra2.php?page=2&limit=5&search=ar
 *
 * NOTA: No olvides sanear los parámetros recibidos ;)
 */

$url='https://633dc0417e19b17829155626.mockapi.io/api/employees';
function obtener_datos(string $url,$pagina=false,$limite=false,$busqueda=false){
    if($pagina||$limite||$busqueda){
        $url.="?";
    }
    if($pagina){
        $url=$url."&page=".$pagina;
    }
    if($limite){
        $url=$url."&limit=".$limite;
    }
    if($busqueda){
        $url=$url."&search=".$busqueda;
    }
    echo("Estas consultando esta URL: ".$url."\n");
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $respuesta=curl_exec($ch);
    if(curl_errno($ch)){
        $error_msg=curl_errno(($ch));
        echo("Error al conectarse a la API");
    }else{
        curl_close($ch);

        $data=json_decode($respuesta,true);
        foreach ($data as $empleado){
            echo("::::::::::::::::::::::::::::::::::::\n");
            echo("Empleado: ". $empleado['id'] ."\n");
            echo("Imagen: ". $empleado['avatar'] ."\n");
            echo("Nombre: ". $empleado['name'] ."\n");
            echo("Ciudad: ". $empleado['city'] ."\n");
            echo("Empresa: ". $empleado['company'] ."\n");
            $timestamp=$empleado['createdAt'];
            $dateTime = date('Y-m-d H:i:s', $timestamp);
            echo("Fecha Creacion: ". $dateTime ."\n");
            echo("::::::::::::::::::::::::::::::::::::\n");
        }
    }
}


if(count($argv)==4){
    $pagina=(int) $argv[1];
    $limite=(int) $argv[2];
    $busqueda=(string) $argv[3];
    obtener_datos($url,$pagina,$limite,$busqueda);
}else if (count($argv)==3){
    $pagina=(int) $argv[1];
    $limite=(int) $argv[2];
    obtener_datos($url,$pagina,$limite);
}else if(count($argv)==2) {
    $pagina = (int)$argv[1];
    obtener_datos($url,$pagina);
}


