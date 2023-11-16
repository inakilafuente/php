<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<form id="myForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <fieldset>
            <?php
            if($pedido!=null){
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
                $date_recog = $pedido[0]["Hora_recogida"];
                $dir_entreg = $pedido[0]["Direccion_entrega"];
                $date_entreg = $pedido[0]["Hora_entrega"];
                $tiempo = $pedido[0]["Tiempo_entrega"];
                $dist = $pedido[0]["Distancia"];
                $ref = $pedido[0]["Referencia"];
                $fk_id_rider = $pedido[0]["FK_ID_Rider"];
            }else{
                $id_pedido = $_POST["id_pedido"];
                $estado = $_POST["selectEstado"];
                $dir_recog = $_POST["txtDir_recog"];
                $date_recog = $_POST["date_recog"];
                $dir_entreg = $_POST["txtDir_entreg"];
                $date_entreg = $_POST["date_entreg"];
                $tiempo = $_POST["txtTiempo"];
                //$dist = $_POST["txtDist"];
                $ref = $_POST["id"];
                $date_crecion = $_POST["date_crecion"];
                $fk_id_rider = $_POST["fk_idRider"];

                if($tiempo=="readonly"){
                    $tiempo=0;
                }
                if($dist=="readonly"){
                    $dist=0;
                }
            }
            ?>
        <?php if(!$nuevo_pedido): ?>
        <legend>Ficha articulo:</legend>
        <label for="fname" class="form-label">ID:</label><br>

        <input type="text" id="fname" name="id_pedido" class="form-control" value=<?php echo($id_pedido)?> readonly><br><br>
            Estado:<br>
        <select class="form-select form-select-sm" name="selectEstado">
                <option>-</option>
                <?php
                if($estado==0){
                    $estado="PENDIENTE";
                }
                if($estado==1){
                    $estado="RECOGIDO";
                }
                if($estado==2) {
                    $estado = "ENTREGADO";
                }
                foreach($res_estados as $row_estado):
                    if($row_estado==$estado):?>
                        <option selected><?php echo($estado); ?></option>
                    <?php else: ?>
                        <option><?php echo($row_estado);?></option>
                <?php
                endif;
                endforeach;?>
            </select><br>
        <?php if($puede_recoger):?>
            <input type="button" class="form-control" value="Recoger Pedido" onclick="cambiar_estado_pedido()"><br>
        <?php elseif($puede_entregar): ?>
            <input type="button" class="form-control" value="Entregar Pedido" onclick="cambiar_estado_pedido()"><br>
        <?php endif;?>
            <?php if($error_estado): ?>
                <label for="lname" class="form-label" style="color: red"><?php echo ($error_estado_msg);?></label><br>
            <?php endif;?>
            <br><label for="fname" class="form-label">Direccion recogida:</label><br>
        <input type="text" id="fname" name="txtDir_recog" class="form-control"  value='<?php echo($dir_recog);?>'><br><br>
        <label for="lname" class="form-label">Hora recogida:</label><br>
        <input type="datetime-local" id="lname" name="date_recog"  class="form-control" value=<?php echo $date_recog; ?>><br><br>
        <label for="fname" class="form-label">Direccion entrega:</label><br>
        <input type="text" id="fname" name="txtDir_entreg" class="form-control"  value='<?php echo($dir_entreg);?>'><br><br>
        <label for="lname">Hora entrega:</label><br>

        <input type="datetime-local" id="lname"  name="date_entreg" class="form-control" value=<?php echo ($date_entreg); ?>><br><br>
        <label for="fname" class="form-label">Tiempo entrega:</label><br>
        <?php if($tiempo==0): ?>
                <input type="text" id="fname" name="txtTiempo"  class="form-control" value="-" readonly><br><br>
        <?else: ?>
                <input type="text" id="fname" name="txtTiempo"  class="form-control" value=<?php echo($tiempo); ?> readonly><br><br>
        <?endif;?>

        <label for="lname">Distancia:</label><br>

        <input type="text" id="lname" name="txtDist"  class="form-control" value=<?php echo($dist); ?> readonly><br><br>
        <?php if($dist==0): ?>
        <input type="button" value="Calcular Distancia" onclick="calcular_distancia()"><br><br>
        <?php endif; ?>
        <label for="fname">Referencia:</label><br>
        <input type="text" id="fname" name="id" class="form-control" value=<?php echo($ref); ?>><br>
            <?php if($error_ref_existe && $error_ref_exist_msg!=""): ?>
                <label for="lname" style="color: red"><?php echo ($error_ref_exist_msg);?></label><br><br>
            <?php endif;?>
            <?php if($error_ref_vacia): ?>
                <label for="lname" style="color: red"><?php echo ($error_ref_vacia_msg);?></label><br><br>
            <?php endif;?>
            <br><label for="lname">Fecha creacion:</label><br>
        <input type="datetime-local" id="lname" name="date_crecion"  class="form-control" value=<?php echo date('Y-m-d\TH:i', $date_creacion); ?> readonly><br><br>
        <label for="lname">ID Rider:</label><br>
        <input type="text" id="lname" name="fk_idRider"  class="form-control" value=<?php echo($fk_id_rider); ?>><br><br>
        <?php if(!$error_rider_existe): ?>
                <label for="lname" style="color: red"><?php echo ($error_rider_existe_msg);?></label><br><br>
        <?php endif;?>
        <?php if($error_rider_ocupado): ?>
            <label for="lname" style="color: red"><?php echo ($error_rider_ocupado_msg);?></label><br><br>
        <?php endif;?>
        <input type="submit" class="btn btn-primary" value="Modificar pedido">
            <input type="button"  class="btn btn-danger" value="Cancelar" onclick="history.back();">
            <!-- Button trigger modal -->
            <?php if($estado=='PENDIENTE'){?>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Asignar Rider
            </button>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Asignar Rider</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <select name="select_RIDER_MODAL">
                                <?php
                                if(count($array_riders_disponibles_asignar)<1){
                                   echo("No hay riders disponibles");
                                }  else{
                                foreach($array_riders_disponibles_asignar as $row_rider): ?>
                                    <option><?php echo($row_rider['nombre']." ". $row_rider['apellidos']);?></option>
                                <?php endforeach; ?>
                            </select><br><br>

                        </div>
                        <?php }}?>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <input type="submit" class="btn btn-primary" value="Asignar">
                        </div>
                    </div>
                </div>
            </div>








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
        <input type="text" id="fname" name="txtTiempo"  value="-" readonly><br><br>
        <label for="lname">Distancia:</label><br>
        <input type="text" id="lname" name="txtDist"  value="-" readonly><br><br>
        <label for="fname">Referencia:</label><br>
        <input type="text" id="fname" name="id"  value=""><br><br>
        <?php if($error_ref_existe): ?>
            <label for="lname"><?php echo ($error_ref_exist_msg);?></label>
        <?php endif;?>
            <?php if($error_ref_vacia): ?>
                <label for="lname" style="color: red"><?php echo ($error_ref_vacia_msg);?></label><br><br>
            <?php endif;?>
        <label for="lname">Fecha creacion:</label><br>
        <input type="datetime-local" id="lname" name="date_crecion"  value=<?php echo ($date_creacion); ?> readonly><br><br>
        <label for="lname">ID Rider:</label><br>
        <input type="text" id="lname" name="fk_idRider"  value="" readonly><br><br>
        <input type="submit" class="btn btn-primary" value="Crear pedido"><input type="button" value="Cancelar" onclick="self.close()">

        <?php endif; ?>
    </fieldset>
</form>


<script type="text/javascript">
    function cambiar_estado_pedido(){
        var estado_pedido = document.getElementsByName('selectEstado')
        if(estado_pedido[0].value==="RECOGIDO"){
            if(confirm("¿Estas seguro de que quieres entregar el pedido?")){
                estado_pedido[0].value="ENTREGADO";
            }

        }
        if(estado_pedido[0].value==="PENDIENTE"){
            if(confirm("¿Estas seguro de que quieres recoger el pedido?")) {
                estado_pedido[0].value = "RECOGIDO";
            }
        }
        let submit=document.getElementById("myForm")
        submit.submit();
    }

    function calcular_distancia(){
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