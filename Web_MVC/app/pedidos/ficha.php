<?php
// Este controlador se encarga de mostrar la vista de una ficha de un Pedido
$id_pedido = $_GET['id'];

// Comprobar que exista el pedido recibido...
// ¿Qué hacemos si queremos crear un pedido nuevo?

if(empty($id_pedido)){
    echo 'Pedido no encontrado';
    http_response_code(404);
    return;
}

//$row_pedido = $base_datos->get_pedido($id_pedido);

require_once 'views/ficha.php';