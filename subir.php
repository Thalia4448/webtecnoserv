<?php
    if(isset($_POST['btn'])) {
        $conexion = new mysqli('localhost', 'root', '', 'images') or die('Error.');
        $tmp = $_FILES['img']['tmp_name'];
        $bytesArchivo = $conexion->real_escape_string(file_get_contents($tmp));
        $comentario = $_POST['coment'];
        $conexion->query("INSERT img(Comentario, img) VALUES('$comentario', '".$bytesArchivo."')");
    }
?>

<form action="" method="post" enctype="multipart/form-data">
    <label for="">Comentario</label>
    <input type="text" name="coment" id="coment">
    <br>
    <label for="">Imagen</label>
    <input type="file" name="img" id="img">
    <br>
    <button type="submit" name="btn">Agregar</button>
</form>