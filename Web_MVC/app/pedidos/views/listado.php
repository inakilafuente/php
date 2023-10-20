<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
    <div>
        Referencia: <input type="text" name="txReferencia" value="">
        Rider:
        <select name="selRider">

            <option>-</option>
            <?php
            $i=0;
            foreach($res_riders as $row_rider): ?>
                <option><?php echo($row_rider['nombre']. " ". $row_rider['apellidos']);?></option>
            <?php endforeach; ?>


        </select>
        Estado:
        <select name="selEstado">

            <option>-</option>
            <!--
            <option value="1">Pendiente</option>
            <option value="2">Recogido</option>
            <option value="3">Entregado</option>
            -->
            <?php
            $i=0;
            foreach($res_estados as $row_estado): ?>
                <option><?php echo($row_estado);?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Buscar">
    </div>
    <div>
        <?php if(!empty($res_pedidos)): ?>
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
                <?php foreach($res_pedidos as $row_pedido):?>
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

<button class="btn btn-success" name="btn_nuevo_pedido" onclick=" window.open('ficha.php?btn_nuevo_pedido=true','_blank')">Nuevo Pedido</button>