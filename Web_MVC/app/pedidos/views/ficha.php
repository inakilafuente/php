<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <fieldset>
            <?php if($pedido!=null){

                $id_pedido=$pedido[0]["PK_id"];
            if($pedido[0]["Estado"]==0){
                $estado="PENDIENTE";
            }
            if($pedido[0]["Estado"]==1){
                $estado="RECOGIDO";
            }
            if($pedido[0]["Estado"]==2) {
                $estado = "ENTREGADO";
            }
                $dir_recog = $pedido[0]["Direccion_recogida"];
                $date_recog = strtotime($pedido[0]["Hora_recogida"]);
                $dir_entreg = $pedido[0]["Direccion_entrega"];
                $date_entreg = strtotime($pedido[0]["Hora_entrega"]);
                $tiempo = $pedido[0]["Tiempo_entrega"];
                $dist = $pedido[0]["Distancia"];
                $ref = $pedido[0]["Referencia"];
                $fk_id_rider = $pedido[0]["FK_ID_Rider"];
            }
            ?>
        <?php if(!$nuevo_pedido): ?>
        <legend>Ficha articulo:</legend>
        <label for="fname">ID:</label><br>

        <input type="text" id="fname" name="id_pedido" value=<?php echo($id_pedido)?> readonly><br><br>
            Estado:<br>
        <select name="selectEstado">
                <option>-</option>
                <?php
                foreach($res_estados as $row_estado):
                    if($row_estado==$estado):?>
                        <option selected><?php echo($estado); ?></option>
                    <?php else: ?>
                        <option><?php echo($row_estado);?></option>
                <?php
                endif;
                endforeach;?>
            </select><br>
            <?php if($error_estado): ?>
                <label for="lname" style="color: red"><?php echo ($error_estado_msg);?></label><br><br>
            <?php endif;?>
            <br><label for="fname">Direccion recogida:</label><br>
        <input type="text" id="fname" name="txtDir_recog"  value='<?php echo($dir_recog);?>'><br><br>
        <label for="lname">Hora recogida:</label><br>

        <input type="datetime-local" id="lname" name="date_recog" value=<?php echo date('Y-m-d\TH:i', $date_recog); ?>><br><br>
        <label for="fname">Direccion entrega:</label><br>
        <input type="text" id="fname" name="txtDir_entreg"  value='<?php echo($dir_entreg);?>'><br><br>
        <label for="lname">Hora entrega:</label><br>

        <input type="datetime-local" id="lname"  name="date_entreg" value=<?php echo date('Y-m-d\TH:i', $date_entreg); ?>><br><br>
        <label for="fname">Tiempo entrega:</label><br>
        <input type="text" id="fname" name="txtTiempo"  value=<?php echo($tiempo); ?> readonly><br><br>
        <label for="lname">Distancia:</label><br>
        <input type="text" id="lname" name="txtDist"  value=<?php echo($dist); ?> readonly><br><br>
        <label for="fname">Referencia:</label><br>
        <input type="text" id="fname" name="id"  value=<?php echo($ref); ?>><br>
            <?php if($error_ref_existe): ?>
                <label for="lname" style="color: red"><?php echo ($error_ref_exist_msg);?></label><br><br>
            <?php endif;?>
            <br><label for="lname">Fecha creacion:</label><br>
        <input type="datetime-local" id="lname" name="date_crecion"  value=<?php echo date('Y-m-d\TH:i', $date_creacion); ?> readonly><br><br>
        <label for="lname">ID Rider:</label><br>
        <input type="text" id="lname" name="fk_idRider"  value=<?php echo($fk_id_rider); ?>><br><br>
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
        <label for="fname">Direccion recogida:</label><br>
        <input type="text" id="fname" name="txtDir_recog" value=""><br><br>
        <label for="lname">Hora recogida:</label><br>
        <input type="datetime-local" id="lname"  name="date_recog" value="" ><br><br>
        <label for="fname">Direccion entrega:</label><br>
        <input type="text" id="fname" name="txtDir_entreg"  value=""><br><br>
        <label for="lname">Hora entrega:</label><br>
        <input type="datetime-local" id="lname"  name="date_entreg" value=""><br><br>
        <label for="fname">Tiempo entrega:</label><br>
        <input type="text" id="fname" name="txtTiempo"  value=<?php echo($tiempo)?> readonly><br><br>
        <label for="lname">Distancia:</label><br>
        <input type="text" id="lname" name="txtDist"  value=<?php echo($dist)?> readonly><br><br>
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