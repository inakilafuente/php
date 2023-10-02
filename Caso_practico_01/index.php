<?php
$opcion=0;
while($opcion!=6){
echo "1. Invertir cadena \n
2. Invertir mayus/minus \n
3. Encolar \n
4. Desencolar \n
5. Desapilar \n
6. Salir \n
Escoja una opción: ";

$opcion = (int) readline("Escoja una opción:");
if(gettype($opcion)=="integer"){
    switch ($opcion) {
        case 1:
            echo("Escribe una cadena:");
            $cadena = (string) readline("Escribe una cadena:");
            if(gettype(($cadena))=="string"){
                $cadena = strrev($cadena);
                echo("La cadena invertida es: ".$cadena. "\n");
            }else{
                echo"El valor introducido no es una cadena";
            }
            break;
        case 2:
            echo("Escribe una cadena:");
            $cadena = (string) readline("Escribe una cadena:");
            if(gettype(($cadena))=="string"){
                echo("La cadena resultado es: ");
                echo(strtolower($cadena) ^ strtoupper($cadena) ^ $cadena);
                echo("\n");
            }else{
                echo"El valor introducido no es una cadena";
            }
            break;
        case 3:
            echo("Escribe una cadena:");
            $cadena = (string) readline("Escribe una cadena:");
            if(gettype(($cadena))=="string"){
                echo("La cadena resultado es: ");
                $array_result[]=$cadena;
                print_r($array_result);
                echo("\n");
            }else{
                echo"El valor introducido no es una cadena";
            }
            break;
        case 4:
            if(count($array_result)>0){
                unset($array_result[count($array_result)-1]);
                print_r($array_result);
                echo("\n");
            }else{
                echo("El array no tiene valores". "\n");
            }
            break;
        case 5:
            if(count($array_result)>0){
                unset($array_result[0]);
                print_r($array_result);
                echo("\n");
            }else {
                echo("El array no tiene valores". "\n");
            }
            break;
        default:
            break;
    }
}

}



