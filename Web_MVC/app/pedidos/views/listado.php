<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="GET">
    <?php
    if($_GET["txReferencia"]||$_GET["selRider"]||$_GET["selEstado"]||$_GET["pedidos_finalizados"]){
        session_start();

        $_SESSION["txReferencia"]=htmlentities($_GET['txReferencia']);
        $_SESSION["selRider"]=htmlentities($_GET['selRider']);
        $_SESSION["selEstado"]=htmlentities($_GET['selEstado']);
        $_SESSION["pedidos_finalizados"]=htmlentities($_GET['pedidos_finalizados']);
    }
    ?>

    <div>
        <?php if($_SESSION["txReferencia"]!=null):?>
            Referencia: <input type="text" name="txReferencia" value=<?php echo($_SESSION["txReferencia"]);?>>
        <?php else:?>
            Referencia: <input type="text" name="txReferencia" value="">
        <?php endif;?>

        <?php if($_SESSION["selRider"]!=null):?>
            Rider:
            <select name="selRider">
                <option>-</option>
                <?php
                $i=0;
                foreach($res_riders as $row_rider):
                    $string=$row_rider['nombre']. " ". $row_rider['apellidos'];
                    if($string==$_SESSION["selRider"]):?>
                    <option selected><?php echo($string);?></option>
                    <?php else:?>
                        <option><?php echo($string);?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        <?php else:?>
        Rider:
        <select name="selRider">
            <option>-</option>
            <?php
            $i=0;
            foreach($res_riders as $row_rider): ?>
                <option><?php echo($row_rider['nombre']. " ". $row_rider['apellidos']);?></option>
            <?php endforeach; ?>
        </select>
        <?php endif;?>


        <?php if($_SESSION["selEstado"]!=null):?>
            Estado:
            <select name="selEstado">
                <option>-</option>
                <?php
                $i=0;
                foreach($res_estados as $row_estado):
                if($row_estado==$_SESSION["selEstado"]):?>
                    <option selected><?php echo($row_estado);?></option>
                <?php else:?>
                    <option><?php echo($row_estado);?></option>
                <?php endif;?>
                <?php endforeach; ?>
            </select>
        <?php else:?>
            Estado:
            <select name="selEstado">
                <option>-</option>
                <?php
                $i=0;
                foreach($res_estados as $row_estado): ?>
                    <option><?php echo($row_estado);?></option>
                <?php endforeach; ?>
            </select>

        <?php endif;?>
        <?php if($_SESSION["pedidos_finalizados"]!=null):?>
        <input type="checkbox" id="finalizados" name="pedidos_finalizados" checked/>
        <?php else:?>
        <input type="checkbox" id="finalizados" name="pedidos_finalizados" />
        <?php endif;?>
        <label for="scales">Pedidos Finalizados</label>
        <input type="submit" value="Buscar">
    </div>
    <div>
        <?php if(!empty($busqueda_pedidos)): ?>
            <table>
                <thead>
                <th>Referencia</th>
                <th>Rider</th>
                <th>Fecha creación</th>
                <th>Dirección de recogida</th>
                <th>Dirección de entrega</th>
                <th>Distancia</th>
                <th>Estado</th>
                </thead>
                <tbody>
                <?php foreach($busqueda_pedidos as $row_pedido):?>
                    <tr>
                        <td><a href="ficha.php?id=<?php echo($row_pedido['Referencia']);?>"><?php echo($row_pedido['Referencia']);?></a></td>
                        <td><?php echo($row_pedido['nombre']." ".$row_pedido['apellidos']); ?></td>
                        <td><?php echo($row_pedido['Fecha_creacion']); ?></td>
                        <td><?php echo($row_pedido['Direccion_recogida']); ?></td>
                        <td><?php echo($row_pedido['Direccion_entrega']); ?></td>
                        <td><?php echo($row_pedido['Distancia']); ?></td>
                        <td><?php
                            if ($row_pedido['Estado']==0) {
                                echo('PENDIENTE');
                            }elseif ($row_pedido['Estado']==1) {
                                echo('RECOGIDO');
                            }elseif ($row_pedido['Estado']==2) {
                                echo('ENTREGADO');
                            }?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            No se ha encontrado pedidos
        <?php endif;?>
    </div>
</form>


<?php

echo("<label>Pedidos totales encontrados($num_reg)</label><br>");

if($_REQUEST['page']=="1"){
    $_REQUEST['page']=="0";
    echo("");
}else {
    if ($page > 1) {
        if($_REQUEST['page']>2){
            echo("<li><a href='listado.php?page=1'><span><<</span></a></li>");
        }
        $anterior = $_REQUEST['page'] - 1;
        echo("<li><a href='listado.php?page=" . ($page - 1) . "'>" . $anterior . "</a></li>");
    }
}

    echo("<li><a>" . $_REQUEST["page"] . "</a></li>");
    $siguiente = $_REQUEST['page'] + 1;
    $ultima = $num_reg / $pedidos_pag;
    if ($ultima == $_REQUEST['page'] + 1) {
        $ultima = "";
    }
    if ($page < $pages && $pages > 1) {
        echo("<li><a href='listado.php?page=" . ($page + 1) . "'>" . $siguiente . "</a></li>");
        echo("<li><a href='listado.php?page=" . ($ultima) . "'><span>>></span></a></li><br>");
    }

?>



<button class="btn btn-success" name="btn_nuevo_pedido" onclick=" window.open('ficha.php?btn_nuevo_pedido=true','_blank')">Nuevo Pedido</button>