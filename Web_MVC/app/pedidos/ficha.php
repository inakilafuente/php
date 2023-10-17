<?php
// Este controlador se encarga de mostrar la vista de una ficha de un Pedido

$host = "172.17.0.3";
$port = 3306;
$user = "root";
$password = "test1234";
$conexion_bd = mysqli_connect($host, $user, $password, 'BD');
if(!$conexion_bd){
    echo 'Error conectando a base de datos: ' . mysqli_connect_error();
    exit;
}
$array_pedido=array();
$id_pedido = $_GET['id'];
$pedido=get_pedido($conexion_bd,$array_pedido,$id_pedido);
print_r($pedido);
// Comprobar que exista el pedido recibido...
// ¿Qué hacemos si queremos crear un pedido nuevo?

/*
if(empty($id_pedido)){
    echo 'Pedido no encontrado';
    http_response_code(404);
    return;
}
*/
//$row_pedido = $base_datos->get_pedido($id_pedido);

require_once 'views/ficha.php';


function get_pedido($conexion_bd,$array_pedido,$id_pedido){
    $query="SELECT * FROM PEDIDO WHERE Referencia=".$id_pedido;
    $res_pedido = mysqli_query($conexion_bd, $query);
    if($res_pedido === false){
        echo 'Query error: ' . mysqli_error($conexion_bd);
        exit;
    }
    while($row_pedido = $res_pedido->fetch_assoc()){
        $array_pedido[]=$row_pedido;
    }
    return $array_pedido;
}