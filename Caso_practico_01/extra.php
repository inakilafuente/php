<?php
/**
 * Supongamos que María, Juan, Enrique, Ana y Silvia comparten los gastos de un viaje
 * En $arr_pagos tenemos los pagos realizados por cada persona y su importe
 * Para simplificar el ejercicio, todos los pagos son compartidos entre todos
 *
 * Realiza un algoritmo que reparta los gastos por igual, indicando qué deudas han de saldarse. Ejemplo:
 * - María debe X a Ana
 * - Enrique debe X a Silvia
 * - Juan debe X a Silvia
 */


$participantes = array("Maria", "Juan", "Enrique", "Ana", "Silvia");

$arr_pagos = array(
    array(
        "paga" => "Maria",
        "importe" => 15.30
    ),
    array(
        "paga" => "Enrique",
        "importe" => 11.25
    ),
    array(
        "paga" => "Ana",
        "importe" => 3.6
    ),
    array(
        "paga" => "Maria",
        "importe" => 21.35
    ),
    array(
        "paga" => "Silvia",
        "importe" => 17.5
    ),
    array(
        "paga" => "Silvia",
        "importe" => 1.2
    ),
    array(
        "paga" => "Enrique",
        "importe" => 19.1
    ),
    array(
        "paga" => "Maria",
        "importe" => 2.9
    ),
    array(
        "paga" => "Enrique",
        "importe" => 23
    ),
    array(
        "paga" => "Ana",
        "importe" => 7.1
    ),
);
function create_array(array $nombres_participantes){
    $array_pagos=array();
    for ($i = 0; $i < count($nombres_participantes); $i++) {
        $array_pagos[$nombres_participantes[$i]]=0;
    }
    return $array_pagos;
}
function pagos_equitativos(array $participantes,array $pagos ){
    if(count($participantes)<1 || count ($pagos)<1){
        echo("Alguno de los arrays introducidos no tiene valores \n");
    }else{
        $importe_repartir=0;
        $array_pagos=create_array($participantes);
        for($i=0;$i<count($pagos);$i++){
            $nombre=$pagos[$i]["paga"];
            if(!in_array($nombre,$participantes)){
                echo("El nombre introducido no se encuentra entre los participantes");
            }else{
                $importe=$pagos[$i]["importe"];
                if(is_numeric($importe)){
                    $importe_repartir=$importe/count($array_pagos);
                }else{
                    echo("El valor no es un numero");
                }
                $keys=array_keys($array_pagos);
                for($j=0;$j<count($keys);$j++){
                    if($keys[$j]!=$nombre){
                        $array_pagos[$keys[$j]]-=$importe_repartir;
                    }else{
                        $array_pagos[$keys[$j]]+=$importe_repartir*(count($participantes)-1);
                    }
                }
            }
        }
        asort($array_pagos);
        #print_r($array_pagos);
        $saldar_deudas=array();
        $keys_pagos=array_keys(($array_pagos));
        $l=0;
        $nombre_deudor=$keys_pagos[$l];
        $cantidad_pago=$array_pagos[$nombre_deudor];
        $cantidad_pago=abs($cantidad_pago);
        $tam=count($array_pagos)-1;
        for($p=0;$p<$tam;$p++){
            asort($array_pagos);
            #echo("::::ITERACION ". $p."::::". "\n");
            if(($clave_deudor=array_search(min($array_pagos),$array_pagos))!==false){
                $nombre_deudor=$clave_deudor;
            }
            $cantidad_pago=$array_pagos[$nombre_deudor];
            $cantidad_pago=abs($cantidad_pago);
            if(($clave_acreedor=array_search(max($array_pagos),$array_pagos))!==false){
                $nombre_a_pagar=$clave_acreedor;
            }
            $cantidad_recibir=$array_pagos[$nombre_a_pagar];
            /*
            echo("Nombre a pagar: ".$nombre_a_pagar ."\n");
            echo("Cantidad a recibir: ".$cantidad_recibir ."\n");
            echo("Nombre deudor: ".$nombre_deudor ."\n");
            echo("Cantidad pago: ".$cantidad_pago ."\n");
            */
            if($cantidad_recibir>=$cantidad_pago){
                array_push($saldar_deudas,array(
                    "acreedor" => $nombre_a_pagar,
                    "paga" => $nombre_deudor,
                    "importe" => $cantidad_pago,
                ));
                /*
                echo("----SALDAR_DEUDAS----"."\n");
                echo("---------------------"."\n");
                print_r($saldar_deudas);
                echo("---------------------"."\n");
                echo("---------------------"."\n");
                */
                $array_pagos[$nombre_a_pagar]-=$cantidad_pago;
                $array_pagos[$nombre_deudor]+=$cantidad_pago;
                /*
                echo("/////ARRAY_PAGOS////"."\n");
                echo("////////////////////"."\n");
                print_r($array_pagos);
                echo("////////////////////"."\n");
                echo("////////////////////"."\n");
                */
            }else{
                array_push($saldar_deudas,array(
                    "acreedor" => $nombre_a_pagar,
                    "paga" => $nombre_deudor,
                    "importe" => $cantidad_recibir,
                ));
                /*
                echo("----SALDAR_DEUDAS----"."\n");
                echo("---------------------"."\n");
                print_r($saldar_deudas);
                echo("---------------------"."\n");
                echo("---------------------"."\n");
                echo("cantidad_pago:" .$cantidad_pago . "\n");
                echo("cantidad_recibir:" .$cantidad_recibir . "\n");
                */
                $array_pagos[$nombre_deudor]=$cantidad_recibir-$cantidad_pago;
                $array_pagos[$nombre_a_pagar]=0;
                /*
                echo("/////ARRAY_PAGOS////"."\n");
                echo("////////////////////"."\n");
                print_r($array_pagos);
                echo("////////////////////"."\n");
                echo("////////////////////"."\n");
                */

            }
            if($array_pagos[$nombre_deudor]==0){
                if(($clave=array_search($nombre_deudor,$keys_pagos))!==false){
                    unset($keys_pagos[$clave]);
                }
                unset($array_pagos[$nombre_deudor]);

                #print_r($array_pagos);
                #print_r($keys_pagos);
            }

        }
        #print_r($saldar_deudas);
        for($q=0;$q<count($saldar_deudas);$q++){
            echo($saldar_deudas[$q]["paga"]." paga a " . $saldar_deudas[$q]["acreedor"] . " el importe de ". $saldar_deudas[$q]["importe"] . "€ \n");
        }
    }
}

pagos_equitativos($participantes,$arr_pagos);