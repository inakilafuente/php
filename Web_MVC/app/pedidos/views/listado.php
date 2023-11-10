<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="GET">
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
            Referencia: <input type="text" name="txReferencia" value=<?php echo($_SESSION["txReferencia"]);?>><br>
        <?php else:?>
            Referencia: <input type="text" name="txReferencia" value=""><br>
        <?php endif;?>

        <?php if($_SESSION["selRider"]!=null):?>
            Rider:
            <select class="form-select form-select-sm" name="selRider">
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
        <select class="form-select form-select-sm" name="selRider">
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
            <select class="form-select form-select-sm" name="selEstado">
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
            <select class="form-select form-select-sm" name="selEstado">
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
        <label for="scales" class="form-label">Pedidos Finalizados</label><br>
        <input type="submit" class="btn btn-primary" value="Buscar">
        <button class="btn btn-success" name="btn_nuevo_pedido" onclick=" window.open('ficha.php?btn_nuevo_pedido=true','_blank')">Nuevo Pedido</button>

    </div>
    <div>
        <?php if(!empty($busqueda_pedidos)): ?>
            <table class="table table-striped">
                <thead>


                <input  type="hidden" name="order_dir" value=<?php echo($filtros['DIR']); ?>>
                <input  type="hidden" name="order_by" value=<?php echo($filtros['ORDERBY']);?>>
                <th>Referencia <img src="../../../images/order_logo.png" alt="Ordenar" width="20" height="20" style="cursor:pointer;" onclick="ordenar('Referencia')"></th>
                <th>Rider <img src="../../../images/order_logo.png" alt="Ordenar" width="20" height="20" style="cursor:pointer;" onclick="ordenar('nombre')"></th>
                <th>Fecha creación</th>
                <th>Dirección de recogida</th>
                <th>Dirección de entrega</th>
                <th>Distancia <img src="../../../images/order_logo.png" alt="Ordenar" width="20" height="20" style="cursor:pointer;" onclick="ordenar('Distancia')"></th>
                <th>Estado</th>
                </thead>
                <tbody>
                <?php foreach($busqueda_pedidos as $row_pedido):?>
                    <tr>
                        <td><a href="ficha.php?id=<?php echo($row_pedido['Referencia']);?>"><?php echo($row_pedido['Referencia']);?></a></td>
                        <?php
                        $nombre_completo=$row_pedido['nombre']." ".$row_pedido['apellidos'];
                        if($nombre_completo!=" "):?>
                        <td ><?php echo($nombre_completo); ?></td>
                        <?php else:?>
                        <td><?php echo("-"); ?></td>
                        <?php endif;?>
                        <td><?php echo($row_pedido['Fecha_creacion']); ?></td>
                        <?php
                        $direccion_recogida=$row_pedido['Direccion_recogida'];
                        if($direccion_recogida!=null):?>
                        <td id="txtDir_recog"><?php echo($direccion_recogida); ?></td>
                        <?php else:?>
                        <td><?php echo("-"); ?></td>
                        <?php endif;?>
                        <?php
                        $direccion_entrega=$row_pedido['Direccion_entrega'];
                        if($direccion_entrega!=null):?>
                            <td id="txtDir_entreg"><?php echo($direccion_entrega); ?></td>
                        <?php else:?>
                            <td><?php echo("-"); ?></td>
                        <?php endif;?>
                        <?php if($row_pedido['Distancia']!=0): ?>
                        <td><?php echo($row_pedido['Distancia']); ?></td>
                        <?php else: ?>

                        <td><img class="actualizar_dist" src="../../../images/actualizar.png" alt="Actualizar distancia" width="20" height="20" onclick="calcular_distancia()"></td>

                        <?endif;?>
                        <td>
                            <?php
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
            echo("<a href='listado.php?txReferencia=&selRider=-&selEstado=-&pedidos_finalizados=on&order_dir=&order_by=&page=1'><span> << </span></a>");
        }
        $anterior = $_REQUEST['page'] - 1;
        echo("<a href='listado.php?txReferencia=".$_REQUEST["txReferencia"]."&selRider=".$_REQUEST["selRider"]."&selEstado=".$_REQUEST["selEstado"]."&pedidos_finalizados=".$_REQUEST["pedidos_finalizados"]."&order_dir=".$_REQUEST["order_dir"]."&order_by=".$_REQUEST["order_by"]."&page=" . ($page - 1) . "'>" . "  ".$anterior." " . "</a>");
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
        echo("<a href='listado.php?txReferencia=".$_REQUEST["txReferencia"]."&selRider=".$_REQUEST["selRider"]."&selEstado=".$_REQUEST["selEstado"]."&pedidos_finalizados=".$_REQUEST["pedidos_finalizados"]."&order_dir=".$_REQUEST["order_dir"]."&order_by=".$_REQUEST["order_by"]."&page="  . ($page + 1) . "'>" . "  ".$siguiente." " . "</a>");
        if($_REQUEST['page']>$ultima){
            $_REQUEST['page']= $ultima;
        }
        if($_REQUEST['page']+1<$ultima){
            echo("<a href='listado.php?txReferencia=".$_REQUEST["txReferencia"]."&selRider=".$_REQUEST["selRider"]."&selEstado=".$_REQUEST["selEstado"]."&pedidos_finalizados=".$_REQUEST["pedidos_finalizados"]."&order_dir=".$_REQUEST["order_dir"]."&order_by=".$_REQUEST["order_by"]."&page=" . ($ultima) . "'><span> >> </span></a><br>");
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
</script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>

    function calcular_distancia(){
        let distancias=document.getElementsByClassName("actualizar_dist");
        let parent=distancias[0].parentElement;
        let dir_recog= document.getElementById("txtDir_recog");

        let valor_fila=document.createElement("td");


        let dir_entreg=document.getElementById("txtDir_entreg");
        alert(dir_recog.innerHTML);
        alert(dir_entreg.innerHTML);
        if(dir_recog.innerHTML!=="" && dir_entreg.innerHTML!==""){
            if(confirm("¿Estas seguro de que quieres calcular la distancia?")) {

                let array_recog=dir_recog.innerHTML.split("");
                let recogida_geo = array_recog[0];
                for (let i = 1; i < array_recog.length; i++) {
                    recogida_geo += "%20" + array_recog[i];
                }

                let array_entrega=dir_entreg.innerHTML.split("");
                let entrega_geo = array_entrega[0];
                for (let i = 1; i < array_entrega.length; i++) {
                    entrega_geo += "%20" + array_entrega[i];
                }

                let latitud_recogida;
                let longitud_recogida;
                let latitud_entrega;
                let longitud_entrega;
                $.get("http://api.positionstack.com/v1/forward?access_key=521c5d20deb4b33ed5b197c77b3d1ebe&query=" + dir_recog.innerHTML)
                    .done(function(data){
                        data.forEach(element => {
                            latitud_recogida = element.value['0']['latitude'];
                            longitud_recogida = element.value['0']['longitude'];
                        });
                    })
                    .fail(function(xhr, status, error){
                        alert("Error durante la ejecución de la Api");
                    })

                $.get("http://api.positionstack.com/v1/forward?access_key=521c5d20deb4b33ed5b197c77b3d1ebe&query=" + dir_entreg.innerHTML)
            .done(function(data){
                    data.forEach(element => {
                        latitud_entrega = element.value['0']['latitude'];
                        longitud_entrega = element.value['0']['longitude'];
                    });
                })
                    .fail(function(xhr, status, error){
                        alert("Error durante la ejecución de la Api");
                    })


                let R = 6371; // Radius of the earth in km
                let dLat = deg2rad(latitud_entrega-latitud_recogida);  // deg2rad below
                let dLon = deg2rad(longitud_entrega-longitud_recogida);
                let a =
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(deg2rad(latitud_recogida)) * Math.cos(deg2rad(latitud_entrega)) *
                    Math.sin(dLon/2) * Math.sin(dLon/2)
                ;
                let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                let d = R * c;
                let result=Math.round(d);
                valor_fila.innerHTML=result;
                distancias[0].remove();
                parent.appendChild(valor_fila);
            }
        }else if(dir_recog[0].value===""){
            alert("La direccion de recogida no esta indicada");
        }else if(dir_entreg[0].value===""){
            alert("La direccion de entrega no esta indicada");
        }
    }


    function deg2rad(deg) {
        return deg * (Math.PI/180)
    }
</script>

