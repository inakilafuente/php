<form action="" method="get">
    <fieldset>
        <?php if(!$nuevo_pedido): ?>
        <legend>Ficha articulo:</legend>
        <label for="fname">ID:</label><br>
        <input type="text" id="fname" name="fname" disabled value=<?php echo($pedido[0]["PK_id"]); ?>> <br><br>
        Estado:<br>
        <?php
            if($pedido[0]["Estado"]==0){
                $estado_pedido="PENDIENTE";
            }
            if($pedido[0]["Estado"]==1){
                $estado_pedido="RECOGIDO";
            }
            if($pedido[0]["Estado"]==2){
                $estado_pedido="ENTREGADO";
            }?>
        <select name="selectEstado">
                <option>-</option>
                <?php
                foreach($res_estados as $row_estado):
                    if($row_estado==$estado_pedido):?>
                        <option selected><?php echo($estado_pedido); ?></option>
                    <?php else: ?>
                        <option><?php echo($row_estado);?></option>
                <?php
                endif;
                endforeach;?>
            </select><br><br>
        <label for="fname">Direccion recogida:</label><br>
        <input type="text" id="fname" name="fname" disabled value=<?php echo($pedido[0]["Direccion_recogida"]); ?>><br><br>
        <label for="lname">Hora recogida:</label><br>
        <?php $date_recogida = strtotime($pedido[0]["Hora_recogida"]);
            ?>
        <input type="datetime-local" id="lname" name="lname" value=<?php echo date('Y-m-d\TH:i', $date_recogida); ?>><br><br>
        <label for="fname">Direccion entrega:</label><br>
        <input type="text" id="fname" name="fname" disabled value=<?php echo($pedido[0]["Direccion_entrega"]);?>><br><br>
        <label for="lname">Hora entrega:</label><br>
            <?php $date_entrega = strtotime($pedido[0]["Hora_entrega"]);
            ?>
        <input type="datetime-local" id="lname"  name="lname" value=<?php echo date('Y-m-d\TH:i', $date_entrega); ?>><br><br>
        <label for="fname">Tiempo entrega:</label><br>
        <input type="text" id="fname" name="fname" disabled value=<?php echo($pedido[0]["Tiempo_entrega"]); ?>><br><br>
        <label for="lname">Distancia:</label><br>
        <input type="text" id="lname" name="lname" disabled value=<?php echo($pedido[0]["Distancia"]); ?>><br><br>
        <label for="fname">Referencia:</label><br>
        <input type="text" id="fname" name="fname" disabled value=<?php echo($pedido[0]["Referencia"]); ?>><br><br>
        <label for="lname">Fecha creacion:</label><br>
        <input type="text" id="lname" name="lname" disabled value=<?php echo($pedido[0]["Fecha_creacion"]); ?>><br><br>
        <label for="lname">ID Rider:</label><br>
        <input type="text" id="lname" name="lname" disabled value=<?php echo($pedido[0]["FK_ID_Rider"]); ?>><br><br>
        <input type="submit" value="Modificar pedido">




        <?php else: ?>
        <legend>Ficha nuevo articulo:</legend>
        <label for="fname">ID:</label><br>
        <input type="text" id="fname" name="fname" disabled value=""> <br><br>
            <select name="selEstado">
                <option>-</option>
                <?php
                foreach($res_estados as $row_estado): ?>
                    <option><?php echo($row_estado);?></option>
                <?php endforeach; ?>
            </select><br><br>
        <label for="fname">Direccion recogida:</label><br>
        <input type="text" id="fname" name="fname" disabled value=""><br><br>
        <label for="lname">Hora recogida:</label><br>
        <input type="datetime-local" id="lname" disabled name="lname" value=""><br><br>
        <label for="fname">Direccion entrega:</label><br>
        <input type="text" id="fname" name="fname" disabled value=""><br><br>
        <label for="lname">Hora entrega:</label><br>
        <input type="datetime-local" id="lname" disabled name="lname" value=""><br><br>
        <label for="fname">Tiempo entrega:</label><br>
        <input type="text" id="fname" name="fname" disabled value=""><br><br>
        <label for="lname">Distancia:</label><br>
        <input type="text" id="lname" name="lname" disabled value=""><br><br>
        <label for="fname">Referencia:</label><br>
        <input type="text" id="fname" name="fname" disabled value=""><br><br>
        <label for="lname">Fecha creacion:</label><br>
        <input type="text" id="lname" name="lname" disabled value=""><br><br>
        <label for="lname">ID Rider:</label><br>
        <input type="text" id="lname" name="lname" disabled value=""><br><br>
        <input type="submit" value="Crear pedido">

        <?php endif; ?>
    </fieldset>
</form>