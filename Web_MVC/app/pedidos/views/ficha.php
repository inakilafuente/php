<form action="" method="get">
    <fieldset>
        <legend>Ficha articulo:</legend>
        <label for="fname">ID:</label><br>
        <input type="text" id="fname" name="fname" disabled value=<?php echo($pedido[0]["PK_id"]); ?>> <br><br>
        <label for="lname">Estado:</label><br>
        <input type="text" id="lname" name="lname" disabled value=<?php echo($pedido[0]["Estado"]); ?>><br><br>
        <label for="fname">Direccion recogida:</label><br>
        <input type="text" id="fname" name="fname" disabled value=<?php echo($pedido[0]["Direccion_recogida"]); ?>><br><br>
        <label for="lname">Hora recogida:</label><br>
        <input type="datetime-local" id="lname" disabled name="lname" value=<?php echo($pedido[0]["Hora_recogida"]); ?>><br><br>
        <label for="fname">Direccion entrega:</label><br>
        <input type="text" id="fname" name="fname" disabled value=<?php echo($pedido[0]["Direccion_entrega"]);?>><br><br>
        <label for="lname">Hora entrega:</label><br>
        <input type="datetime-local" id="lname" disabled name="lname" value=<?php echo($pedido[0]["Hora_entrega"]);?>><br><br>
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
        <input type="submit" value="Crear pedido">
    </fieldset>
</form>