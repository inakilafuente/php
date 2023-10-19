<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <fieldset>
        <?php if(!$nuevo_pedido): ?>
        <legend>Ficha articulo:</legend>
        <label for="fname">ID:</label><br>
        <input type="text" id="fname" name="id_pedido" value=<?php echo($pedido[0]["PK_id"]);?> readonly><br><br>
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
            <?php if($error_estado): ?>
                <label for="lname"><?php echo ($error_estado_msg);?></label><br>
            <?php endif;?>
            </select><br><br>
        <label for="fname">Direccion recogida:</label><br>
        <input type="text" id="fname" name="txtDir_recog"  value='<?php echo($pedido[0]["Direccion_recogida"]);?>'><br><br>
        <label for="lname">Hora recogida:</label><br>
        <?php $date_recogida = strtotime($pedido[0]["Hora_recogida"]);
            ?>
        <input type="datetime-local" id="lname" name="date_recog" value=<?php echo date('Y-m-d\TH:i', $date_recogida); ?>><br><br>
        <label for="fname">Direccion entrega:</label><br>
        <input type="text" id="fname" name="txtDir_entreg"  value='<?php echo($pedido[0]["Direccion_entrega"]);?>'><br><br>
        <label for="lname">Hora entrega:</label><br>
            <?php $date_entrega = strtotime($pedido[0]["Hora_entrega"]);
            ?>
        <input type="datetime-local" id="lname"  name="date_entreg" value=<?php echo date('Y-m-d\TH:i', $date_entrega); ?>><br><br>
        <label for="fname">Tiempo entrega:</label><br>
        <input type="text" id="fname" name="txtTiempo"  value=<?php echo($pedido[0]["Tiempo_entrega"]); ?> readonly  ><br><br>
        <label for="lname">Distancia:</label><br>
        <input type="text" id="lname" name="txtDist"  value=<?php echo($pedido[0]["Distancia"]); ?> readonly><br><br>
        <label for="fname">Referencia:</label><br>
        <input type="text" id="fname" name="id"  value=<?php echo($pedido[0]["Referencia"]); ?> readonly><br><br>
        <label for="lname">Fecha creacion:</label><br>
        <input type="datetime-local" id="lname" name="date_crecion"  value=<?php echo date('Y-m-d\TH:i', $date_creacion); ?> readonly><br><br>
        <label for="lname">ID Rider:</label><br>
        <input type="text" id="lname" name="fk_idRider"  value=<?php echo($pedido[0]["FK_ID_Rider"]); ?>><br><br>
        <input type="submit" value="Modificar pedido">




        <?php else: ?>
        <legend>Ficha nuevo articulo:</legend>
        <label for="fname">ID:</label><br>
        <input type="text" id="fname" name="id_pedido"  value=<?php echo($id_disponible); ?> readonly> <br><br>
            Estado:<br>
            <select name="selectEstado">
                <option>-</option>
                <?php
                foreach($res_estados as $row_estado): ?>
                    <option><?php echo($row_estado);?></option>
                <?php endforeach; ?>
            </select><br><br>
            <?php if($error_estado): ?>
                <label for="lname"><?php echo ($error_estado_msg);?></label>
            <?php endif;?>
        <label for="fname">Direccion recogida:</label><br>
        <input type="text" id="fname" name="txtDir_recog" value=""><br><br>
        <label for="lname">Hora recogida:</label><br>
        <input type="datetime-local" id="lname"  name="date_recog" value="" ><br><br>
        <label for="fname">Direccion entrega:</label><br>
        <input type="text" id="fname" name="txtDir_entreg"  value=""><br><br>
        <label for="lname">Hora entrega:</label><br>
        <input type="datetime-local" id="lname"  name="date_entreg" value=""><br><br>
        <label for="fname">Tiempo entrega:</label><br>
        <input type="text" id="fname" name="txtTiempo"  value="" readonly><br><br>
        <label for="lname">Distancia:</label><br>
        <input type="text" id="lname" name="txtDist"  value="" readonly><br><br>
        <label for="fname">Referencia:</label><br>
        <input type="text" id="fname" name="id"  value=""><br><br>
            <?php if($error_ref_existe): ?>
                <label for="lname"><?php echo ($error_ref_exist_msg);?></label>
            <?php endif;?>
        <label for="lname">Fecha creacion:</label><br>
        <input type="datetime-local" id="lname" name="date_crecion"  value=<?php echo ($date_creacion); ?> readonly><br><br>
        <label for="lname">ID Rider:</label><br>
        <input type="text" id="lname" name="fk_idRider"  value="" readonly><br><br>
        <input type="submit" value="Crear pedido">

        <?php endif; ?>
    </fieldset>
</form>