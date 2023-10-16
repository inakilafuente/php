He revisado el caso práctico 3 y te dejo algunos consejos:

Al abrir la aplicación, cargas siempre los pedidos y los riders. No suele ser lo habitual. 
Imagina si en una aplicación web siempre cargáramos toda la información disponible independientemente de si vamos a hacer algo con ella o no.
Al final, cuando quieres modificar un pedido recorres todo el array obtenido al principio, cuando lo normal sería buscar ese pedido en concreto en base de datos en base a la referencia.
Cuando uses cláusulas de guarda, no apliques "else" después. Es lo que queremos evitar con ellas.

Luego algunas puntualizaciones de tus clases:
- El Tiempo de entrega de un pedido no parece un campo a almacenar. Con hora de entrega y recogida es muy sencillo calcularlo "en el aire" y no dependes de que alguien te lo calcule desde fuera de la clase.
- Cuando guardes fechas vacías, usa NULL. El guión es sólo para mostrar por pantalla, pero ¿qué ocurre si intentas guardar un guión en base de datos en un campo datetime? Ya te has dado cuenta tú solo en la función guardar_pedido ;) Tienes que transformar de nuevo el guión a un nulo.

SET FOREIGN_KEY_CHECKS=0 no debería ser lo habitual. Es una práctica totalmente desaconsejada. Si las FK te dan algún problema, deberías pensar cómo resolverlo en lugar de usar el atajo fácil.
