<?php
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/*Inicia validacion del lado del servidor*/

if (empty($_POST['nombre_empresa'])) {
    $errors[] = "Nombre de empresa esta vacío";
} else if (empty($_POST['telefono'])) {
    $errors[] = "Teléfono esta vacío";
} else if (empty($_POST['impuesto'])) {
    $errors[] = "IVA esta vacío";
} else if (empty($_POST['moneda'])) {
    $errors[] = "Moneda esta vacío";
} else if (empty($_POST['codigo_establecimiento'])) {
    $errors[] = "Codigo establecimiento esta vacío";  
} else if (empty($_POST['codigo_punto_emision'])) {
    $errors[] = "Codigo Punto Emision esta vacío";
} else if (
    !empty($_POST['nombre_empresa']) &&
    !empty($_POST['telefono']) &&
    !empty($_POST['impuesto']) &&
    !empty($_POST['moneda']) 
) {
    
    /* Connect To Database*/
    require_once "../db.php";
    require_once "../php_conexion.php";
    // escaping, additionally removing everything that could be (html/javascript-) code
    $nombre_empresa = mysqli_real_escape_string($conexion, (strip_tags($_POST["nombre_empresa"], ENT_QUOTES)));
    $giro           = mysqli_real_escape_string($conexion, (strip_tags($_POST["giro"], ENT_QUOTES)));
    $fiscal         = mysqli_real_escape_string($conexion, (strip_tags($_POST["fiscal"], ENT_QUOTES)));
    $telefono       = mysqli_real_escape_string($conexion, (strip_tags($_POST["telefono"], ENT_QUOTES)));
    $email          = mysqli_real_escape_string($conexion, (strip_tags($_POST["email"], ENT_QUOTES)));
    $impuesto       = mysqli_real_escape_string($conexion, (strip_tags($_POST["impuesto"], ENT_QUOTES)));
    $nom_impuesto   = mysqli_real_escape_string($conexion, (strip_tags($_POST["nom_impuesto"], ENT_QUOTES)));
    $moneda         = mysqli_real_escape_string($conexion, (strip_tags($_POST["moneda"], ENT_QUOTES)));
    $direccion      = mysqli_real_escape_string($conexion, (strip_tags($_POST["direccion"], ENT_QUOTES)));
    $ciudad         = mysqli_real_escape_string($conexion, (strip_tags($_POST["ciudad"], ENT_QUOTES)));
    $estado         = mysqli_real_escape_string($conexion, (strip_tags($_POST["estado"], ENT_QUOTES)));
    $codigo_postal  = mysqli_real_escape_string($conexion, (strip_tags($_POST["codigo_postal"], ENT_QUOTES)));
    $archivo = '';
    if(isset($_FILES['firma'])){
        //var_dump($_FILES['firma']);
        $archivo  = $_FILES['firma']['name'];
        $temp = $_FILES['firma']['tmp_name'];
        
        if (move_uploaded_file($temp, $_SERVER['DOCUMENT_ROOT'].'/imporsuitv01/sysadmin/vistas/xml/firmas/'.$archivo)) {
            chmod($_SERVER['DOCUMENT_ROOT'].'/imporsuitv01/sysadmin/vistas/xml/firmas/'.$archivo, 0777);
            //echo '<div><b>Se ha subido correctamente la imagen.</b></div>';
        }else{
            //echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
        }
    }
    $firma  = $archivo;
    
    $autofactura  = mysqli_real_escape_string($conexion, (strip_tags($_POST["autofactura"], ENT_QUOTES)));
    $autocredito  = mysqli_real_escape_string($conexion, (strip_tags($_POST["autocredito"], ENT_QUOTES)));
    $autodebito  = mysqli_real_escape_string($conexion, (strip_tags($_POST["autodebito"], ENT_QUOTES)));
    $autoguia  = mysqli_real_escape_string($conexion, (strip_tags($_POST["autoguia"], ENT_QUOTES)));
    $autoretencion  = mysqli_real_escape_string($conexion, (strip_tags($_POST["autoretencion"], ENT_QUOTES)));
    $autoliquidacion  = mysqli_real_escape_string($conexion, (strip_tags($_POST["autoliquidacion"], ENT_QUOTES)));

    $ambiente  = mysqli_real_escape_string($conexion, (strip_tags($_POST["ambiente"], ENT_QUOTES)));
    $tipoEmision  = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipoEmision"], ENT_QUOTES)));
    $codigo_establecimiento  = mysqli_real_escape_string($conexion, (strip_tags($_POST["codigo_establecimiento"], ENT_QUOTES)));
    $ruc  = mysqli_real_escape_string($conexion, (strip_tags($_POST["ruc"], ENT_QUOTES)));
    $codigo_punto_emision  = mysqli_real_escape_string($conexion, (strip_tags($_POST["codigo_punto_emision"], ENT_QUOTES)));
    $passFirna  = mysqli_real_escape_string($conexion, (strip_tags($_POST["passFirna"], ENT_QUOTES)));
    $secuencialfactura  = mysqli_real_escape_string($conexion, (strip_tags($_POST["secuencialfactura"], ENT_QUOTES)));
    $secuencialcredito  = mysqli_real_escape_string($conexion, (strip_tags($_POST["secuencialcredito"], ENT_QUOTES)));
    $secuencialdebito  = mysqli_real_escape_string($conexion, (strip_tags($_POST["secuencialdebito"], ENT_QUOTES)));
    $secuencialguia  = mysqli_real_escape_string($conexion, (strip_tags($_POST["secuencialguia"], ENT_QUOTES)));
    $secuencialretencion  = mysqli_real_escape_string($conexion, (strip_tags($_POST["secuencialretencion"], ENT_QUOTES)));
    $secuencialliquidacion  = mysqli_real_escape_string($conexion, (strip_tags($_POST["secuencialliquidacion"], ENT_QUOTES)));

    $sql = "UPDATE perfil SET nombre_empresa='" . $nombre_empresa . "',
                                            giro_empresa='" . $giro . "',
                                            fiscal_empresa='" . $fiscal . "',
                                            telefono='" . $telefono . "',
                                            email='" . $email . "',
                                            impuesto='" . $impuesto . "',
                                            nom_impuesto='" . $nom_impuesto . "',
                                            moneda='" . $moneda . "',
                                            direccion='" . $direccion . "',
                                            ciudad='" . $ciudad . "',
                                            estado='" . $estado . "',
                                            codigo_postal='$codigo_postal',

                                            ambiente='" . $ambiente . "',
                                            tipoEmision='" . $tipoEmision . "',
                                            codigo_establecimiento='" . $codigo_establecimiento . "',
                                            codigo_punto_emision='" . $codigo_punto_emision . "',
                                            ruc='" . $ruc . "',
                                            firma='" . $firma . "',
                                            passFirma='" . $passFirna . "',
                                            secuencialfactura='" . $secuencialfactura . "',
                                            secuencialliquidacion='" . $secuencialliquidacion . "',
                                            secuencialcredito='" . $secuencialcredito . "',
                                            secuencialdebito='" . $secuencialdebito . "',
                                            secuencialguia='" . $secuencialguia . "',
                                            secuencialretencion='" . $secuencialretencion . "',
                                            autofactura='" . $autofactura . "',
                                            autoliquidacion='" . $autoliquidacion . "',
                                            autocredito='" . $autocredito . "',
                                            autodebito='" . $autodebito . "',
                                            autoguia='" . $autoguia . "',
                                            autoretencion='" . $autoretencion . "'
                                            WHERE id_perfil='1'";
                                            
    $query_update = mysqli_query($conexion, $sql);

    if ($query_update) {
        $messages[] = "Datos han sido actualizados satisfactoriamente.";
    } else {
        $errors[] = "Lo siento algo ha salido mal intenta nuevamente." . mysqli_error($conexion);
    }
} else {
    $errors[] = "Error desconocido.";
}

if (isset($errors)) {

    ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Error!</strong>
                    <?php
    foreach ($errors as $error) {
        echo $error;
    }
    ?>
            </div>
            <?php
}
if (isset($messages)) {

    ?>
                <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>¡Bien hecho!</strong>
                        <?php
foreach ($messages as $message) {
        echo $message;
    }
    ?>
                </div>
                <?php
}

?>