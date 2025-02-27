<?php
//include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/*Inicia validacion del lado del servidor*/
if (empty($_POST['session'])) {
    $errors[] = "ID VACIO";
} else if (!empty($_POST['cliente'])) {
    /* Connect To Database*/
    require_once "../sysadmin/vistas/db.php";
require_once "../sysadmin/vistas/php_conexion.php";
    //Archivo de funciones PHP
    require_once "../sysadmin/vistas/funciones.php";
    $session_id     = $_POST['session'];
    $nombre     = $_POST['nombre'];
    $telefono    = $_POST['telefono'];
    $calle_principal    = $_POST['calle_principal'];
    $calle_secundaria    = $_POST['calle_secundaria'];
    $referencia    = $_POST['referencia'];
    $provincia     = $_POST['provinica'];
    $ciudad     = $_POST['ciudad'];
    $observacion     = $_POST['observacion'];
    
    $simbolo_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);
//Comprobamos si hay archivos en la tabla temporal
   // echo "select * from tmp_ventas where session_id='" . $session_id . "'";
    $sql_count = mysqli_query($conexion, "select * from tmp_ventas where session_id='" . $session_id . "'");
    $count     = mysqli_num_rows($sql_count);
   
    // escaping, additionally removing everything that could be (html/javascript-) code
    $id_cliente     = intval($_POST['cliente']);
    $id_vendedor    = 1;
    $users          = 1;
    $condiciones    = 1;
    //$numero_factura = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST["factura"], ENT_QUOTES)));
    $validez        = 3;
    $date_added     = date("Y-m-d H:i:s");
    //Operacion de Creditos
    if ($condiciones == 4) {
        $estado = 2;
    } else {
        $estado = 1;
    }
    //echo "select LAST_INSERT_ID(id_factura) as last from facturas_cot order by id_factura desc limit 0,1 ";
//Seleccionamos el ultimo compo numero_fatura y aumentamos una
    $sql        = mysqli_query($conexion, "select LAST_INSERT_ID(id_factura) as last from facturas_cot order by id_factura desc limit 0,1 ");
    $rw         = mysqli_fetch_array($sql);
    $id_factura = $rw['last'] + 1;
// finde la ultima fatura
    //Control de la  numero_fatura y aumentamos una
    //echo "SELECT RIGHT(numero_factura,6) as factura FROM facturas_cot ORDER BY factura DESC LIMIT 1";
    $query_id = mysqli_query($conexion, "SELECT RIGHT(numero_factura,6) as factura FROM facturas_cot ORDER BY factura DESC LIMIT 1")
    or die('error ' . mysqli_error($conexion));
    
    $count = mysqli_num_rows($query_id);

    if ($count != 0) {

        $data_id = mysqli_fetch_assoc($query_id);
        $factura = $data_id['factura'] + 1;
    } else {
        $factura = 1;
    }

    $buat_id = str_pad($factura, 6, "0", STR_PAD_LEFT);
    $factura = "COT-$buat_id";
// fin de numero de fatura
    // consulta principal
    $nums          = 1;
    $impuesto      = get_row('perfil', 'impuesto', 'id_perfil', 1);
    $sumador_total = 0;
    $sum_total     = 0;
    $t_iva         = 0;
   // echo  "select * from productos, tmp_ventas where productos.id_producto=tmp_ventas.id_producto and tmp_ventas.session_id='" . $session_id . "'";
    $sql           = mysqli_query($conexion, "select * from productos, tmp_ventas where productos.id_producto=tmp_ventas.id_producto and tmp_ventas.session_id='" . $session_id . "'");
    while ($row = mysqli_fetch_array($sql)) {
        $id_tmp          = $row["id_tmp"];
        $id_producto     = $row['id_producto'];
        $codigo_producto = $row['codigo_producto'];
        $cantidad        = $row['cantidad_tmp'];
        $desc_tmp        = $row['desc_tmp'];
        $nombre_producto = $row['nombre_producto'];
        // control del impuesto por productos.
        if ($row['iva_producto'] == 0) {
            $p_venta   = $row['precio_tmp'];
            $p_venta_f = number_format($p_venta, 2); //Formateo variables
            $p_venta_r = str_replace(",", "", $p_venta_f); //Reemplazo las comas
            $p_total   = $p_venta_r * $cantidad;
            $f_items   = rebajas($p_total, $desc_tmp); //Aplicando el descuento
            /*--------------------------------------------------------------------------------*/
            $p_total_f = number_format($f_items, 2); //Precio total formateado
            $p_total_r = str_replace(",", "", $p_total_f); //Reemplazo las comas

            $sum_total += $p_total_r; //Sumador
            $t_iva = ($sum_total * $impuesto) / 100;
            $t_iva = number_format($t_iva, 2, '.', '');
        }
        //end impuesto

        $precio_venta   = $row['precio_tmp'];
        $precio_venta_f = number_format($precio_venta, 2); //Formateo variables
        $precio_venta_r = str_replace(",", "", $precio_venta_f); //Reemplazo las comas
        $precio_total   = $precio_venta_r * $cantidad;
        $final_items    = rebajas($precio_total, $desc_tmp); //Aplicando el descuento
        /*--------------------------------------------------------------------------------*/
        $precio_total_f = number_format($final_items, 2); //Precio total formateado
        $precio_total_r = str_replace(",", "", $precio_total_f); //Reemplazo las comas
        $sumador_total += $precio_total_r; //Sumador

        //Insert en la tabla detalle_factura
       // echo "INSERT INTO detalle_fact_cot VALUES (NULL,'$id_factura','$factura','$id_producto','$cantidad','$desc_tmp','$precio_venta_r')";
        $insert_detail = mysqli_query($conexion, "INSERT INTO detalle_fact_cot VALUES (NULL,'$id_factura','$factura','$id_producto','$cantidad','$desc_tmp','$precio_venta_r')");
    }
    // Fin de la consulta Principal
    $subtotal      = number_format($sumador_total, 2, '.', '');
    $total_iva     = ($subtotal * $impuesto) / 100;
    $total_iva     = number_format($total_iva, 2, '.', '') - number_format($t_iva, 2, '.', '');
    $total_factura = $subtotal + $total_iva;
    //echo "INSERT INTO facturas_cot VALUES (NULL,'$factura','$date_added','$id_cliente','$id_vendedor','$condiciones','$total_factura','$estado','$users','$validez','1')";
    $insert        = mysqli_query($conexion, "INSERT INTO facturas_cot VALUES (NULL,'$factura','$date_added','$id_cliente','$id_vendedor','$condiciones','$total_factura','$estado','$users','$validez','1','$nombre','$telefono','$provincia','$calle_principal','$ciudad','$calle_secundaria','$referencia','$observacion')");
    $delete        = mysqli_query($conexion, "DELETE FROM tmp_ventas WHERE session_id='" . $session_id . "'");
    header("Location: ../gracias.php");
// SI TODO ESTA CORRECTO

    if ($insert_detail) {
        echo "<script>
    $('#modal_cot').modal('show');
</script>";
        #$messages[] = "Venta  ha sido Guardada satisfactoriamente.";
    } else {
        $errors[] = "Lo siento algo ha salido mal intenta nuevamente." . mysqli_error($conexion);
    }
} else {
    $errors[] = "Error desconocido.";
}

if (isset($errors)) {

    ?>
    <div class="alert alert-danger" role="alert">
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
<!-- Modal -->
