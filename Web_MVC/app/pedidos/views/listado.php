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
        <button class="btn btn-success" name="btn_nuevo_pedido" onclick=" window.open('ficha.php?btn_nuevo_pedido=true','_blank')">Nuevo Pedido</button>

    </div>
    <div>
        <?php if(!empty($busqueda_pedidos)): ?>
            <table>
                <thead>


                <input  type="hidden" name="order_dir" value=<?php echo($filtros['DIR']); ?>>
                <input  type="hidden" name="order_by" value=<?php echo($filtros['ORDERBY']);?>>
                <th style="cursor:pointer;" onclick="ordenar('Referencia')"
                <th>Referencia</th>
                <th style="cursor:pointer;" onclick="ordenar('nombre')"
                <th>Rider</th>
                <th>Fecha creación</th>
                <th>Dirección de recogida</th>
                <th>Dirección de entrega</th>
                <th style="cursor:pointer;" onclick="ordenar('Distancia')"
                <th>Distancia</th>
                <th>Estado</th>
                </thead>
                <tbody>
                <?php foreach($busqueda_pedidos as $row_pedido):?>
                    <tr>
                        <td><a href="ficha.php?id=<?php echo($row_pedido['Referencia']);?>"><?php echo($row_pedido['Referencia']);?></a></td>
                        <?php
                        $nombre_completo=$row_pedido['nombre']." ".$row_pedido['apellidos'];
                        if($nombre_completo!=" "):?>
                        <td><?php echo($nombre_completo); ?></td>
                        <?php else:?>
                        <td><?php echo("-"); ?></td>
                        <?php endif;?>
                        <td><?php echo($row_pedido['Fecha_creacion']); ?></td>
                        <?php
                        $direccion_recogida=$row_pedido['Direccion_recogida'];
                        if($direccion_recogida!=null):?>
                        <td><?php echo($direccion_recogida); ?></td>
                        <?php else:?>
                        <td><?php echo("-"); ?></td>
                        <?php endif;?>
                        <?php
                        $direccion_entrega=$row_pedido['Direccion_entrega'];
                        if($direccion_entrega!=null):?>
                            <td><?php echo($direccion_entrega); ?></td>
                        <?php else:?>
                            <td><?php echo("-"); ?></td>
                        <?php endif;?>
                        <td class="distancia_pedido"><?php echo($row_pedido['Distancia']); ?></td>
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
            echo("<a href='listado.php?page=1'><span> << </span></a>");
        }
        $anterior = $_REQUEST['page'] - 1;
        echo("<a href='listado.php?page=" . ($page - 1) . "'>" . "  ".$anterior." " . "</a>");
    }
}

    echo("<a>" . $_REQUEST["page"] . "</a>");
    $siguiente = $_REQUEST['page'] + 1;
    $ultima = $num_reg / $pedidos_pag;
    /*
    if ($ultima == $_REQUEST['page'] + 1) {
        $ultima = "";
    }
    */
    if ($page < $pages && $pages > 1) {
        echo("<a href='listado.php?page=" . ($page + 1) . "'>" . "  ".$siguiente." " . "</a>");
        if($_REQUEST['page']>$ultima){
            $_REQUEST['page']= $ultima;
        }
        if($_REQUEST['page']+1<$ultima){
            echo("<a href='listado.php?page=" . ($ultima) . "'><span> >> </span></a><br>");
        }
    }

?>






<script>
    function ordenar(campo){
        let order = 'asc';

        let current_order_dir = document.getElementsByName('order_dir')[0].value;
        let current_order_value = document.getElementsByName('order_by')[0].value;
        if(current_order_value === campo){
            if(current_order_dir === 'asc'){
                order = 'desc';
            }
        }
        document.getElementsByName('order_dir')[0].value = order;
        document.getElementsByName('order_by')[0].value = campo;
        document.forms[0].submit();
    }


    function calcular_distancia(){
        let distancias=document.getElementsByClassName("distancia_pedido");
        if(distancias[0].value===0){

        }
        let dir_recog= document.getElementsByName("txtDir_recog");
        let dir_entreg=document.getElementsByName("txtDir_entreg");
        if(dir_recog[0].value!=="" && dir_entreg[0].value!==""){
            if(confirm("¿Estas seguro de que quieres calcular la distancia?")) {
                let submit=document.getElementById("myForm")
                submit.submit();
            }
        }else if(dir_recog[0].value===""){
            alert("La direccion de recogida no esta indicada");
        }else if(dir_entreg[0].value===""){
            alert("La direccion de entrega no esta indicada");
        }
    }
</script>

