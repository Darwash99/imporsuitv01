<?php


function get_row($table, $row, $id, $equal)
{
    global $conexion;
   // echo "select $row from $table where $id='$equal'";
    $query = mysqli_query($conexion, "select $row from $table where $id='$equal'");
    $rw    = mysqli_fetch_array($query);
    $value = $rw[$row];
    return $value;
}

function condicion($tipo)
{
    if ($tipo == 1) {
        return 'Efectivo';
    } elseif ($tipo == 2) {
        return 'Cheque';
    } elseif ($tipo == 3) {
        return 'Transferencia bancaria';
    } elseif ($tipo == 4) {
        return 'Crédito';
    }
}
/*--------------------------------------------------------------*/
/* MODIFICAR LOS DATOS DEL GRAFICO
/*--------------------------------------------------------------*/
function monto($table, $mes, $periodo)
{
    global $conexion;
    $fecha_inicial = "$periodo-$mes-1";
    if ($mes == 1 or $mes == 3 or $mes == 5 or $mes == 7 or $mes == 8 or $mes == 10 or $mes == 12) {
        $dia_fin = 31;
    } else if ($mes == 2) {
        if ($periodo % 4 == 0) {
            $dia_fin = 29;
        } else {
            $dia_fin = 28;
        }
    } else {
        $dia_fin = 30;
    }
    $fecha_final = "$periodo-$mes-$dia_fin";

    $query = mysqli_query($conexion, "select sum(monto_factura) as monto from $table where fecha_factura between '$fecha_inicial' and '$fecha_final'");
    $row   = mysqli_fetch_array($query);
    $monto = floatval($row['monto']);
    return $monto;
}
function stock($stock)
{
    if ($stock == 0) {
        return '<span class="badge badge-danger">' . $stock . '</span>';
    } else if ($stock <= 3) {
        return '<span class="badge badge-warning">' . $stock . '</span>';
    } else {
        return '<span class="badge badge-primary">' . $stock . '</span>';
    }
}
/*--------------------------------------------------------------*/
/* Funcion para obtener el total de Pacientes
/*--------------------------------------------------------------*/
function total_clientes()
{
    global $conexion;
    $orderSql       = "SELECT * FROM clientes";
    $orderQuery     = $conexion->query($orderSql);
    $countPacientes = $orderQuery->num_rows;

    echo '' . $countPacientes . '';
}
/*--------------------------------------------------------------*/
/* Funcion para obtener el total de Creditos
/*--------------------------------------------------------------*/
function total_creditos()
{
    $id_moneda    = get_row('perfil', 'moneda', 'id_perfil', 1);
    $fecha_actual = date('Y-m-d');
    global $conexion;
    $orderSql     = "SELECT * FROM facturas_ventas where date(fecha_factura) = '$fecha_actual' and estado_factura=2";
    $orderQuery   = $conexion->query($orderSql);
    $totalRevenue = 0;
    while ($orderResult = $orderQuery->fetch_assoc()) {
        $totalRevenue += $orderResult['monto_factura'];
    }

    echo '' . $id_moneda . '' . number_format($totalRevenue, 2) . '';
}
/*--------------------------------------------------------------*/
/* Funcion para obtener el total de Abonos a proveedores
/*--------------------------------------------------------------*/
function total_cxp()
{
    $id_moneda    = get_row('perfil', 'moneda', 'id_perfil', 1);
    $fecha_actual = date('Y-m-d');
    global $conexion;
    //---------------------------------------------------------------------------------------
    $abonoSql    = "SELECT * FROM creditos_abonos_prov where date(fecha_abono) = '$fecha_actual'";
    $abonoQuery  = $conexion->query($abonoSql);
    $total_abono = 0;
    while ($abonoResult = $abonoQuery->fetch_assoc()) {
        $total_abono += $abonoResult['abono'];
    }

    echo '' . $id_moneda . '' . number_format($total_abono, 2) . '';
}


function total_visitas()
{
    /* $id_moneda    = get_row('perfil', 'moneda', 'id_perfil', 1);
    $fecha_actual = date('Y-m-d');
    global $conexion;
    //---------------------------------------------------------------------------------------
    $abonoSql    = "SELECT count(id) as total FROM registros_visitas where date(fecha_hora) = '$fecha_actual'";
    //echo $abonoSql; 
    $abonoQuery  = $conexion->query($abonoSql);
    var_dump($abonoSql);die;
    $total_abono = 0;
    while ($abonoResult = $abonoQuery->fetch_assoc()) {
        $total_abono += $abonoResult['total'];
    }

    echo '' . '' . $total_abono . '';*/
}

function total_pedidos()
{
     $id_moneda    = get_row('perfil', 'moneda', 'id_perfil', 1);
    $fecha_actual = date('Y-m-d');
    global $conexion;
    //---------------------------------------------------------------------------------------
    $abonoSql    = "SELECT * FROM facturas_cot where date(fecha_factura) = '$fecha_actual'";
    $abonoQuery  = $conexion->query($abonoSql);
    $total_abono = 0;
    while ($abonoResult = $abonoQuery->fetch_assoc()) {
        $total_abono += $abonoResult['monto_factura'];
    }

    echo '' . $id_moneda . '' . number_format($total_abono, 2) . '';
}
/*--------------------------------------------------------------*/
/* Funcion para obtener el total de Abonos a proveedores
/*--------------------------------------------------------------*/
function total_cxc()
{
    $id_moneda    = get_row('perfil', 'moneda', 'id_perfil', 1);
    $fecha_actual = date('Y-m-d');
    global $conexion;
    //---------------------------------------------------------------------------------------
    $abonoSql    = "SELECT * FROM creditos_abonos where date(fecha_abono) = '$fecha_actual'";
    $abonoQuery  = $conexion->query($abonoSql);
    $total_abono = 0;
    while ($abonoResult = $abonoQuery->fetch_assoc()) {
        $total_abono += $abonoResult['abono'];
    }

    echo '' . $id_moneda . '' . number_format($total_abono, 2) . '';
}
/*--------------------------------------------------------------*/
/* Funcion para obtener el total de Ingresos
/*--------------------------------------------------------------*/
function total_ingresos()
{
    $id_moneda    = get_row('perfil', 'moneda', 'id_perfil', 1);
    $fecha_actual = date('Y-m-d');
    global $conexion;
    $orderSql     = "SELECT * FROM facturas_ventas where date(fecha_factura) = '$fecha_actual'";
    $orderQuery   = $conexion->query($orderSql);
    $totalRevenue = 0;
    while ($orderResult = $orderQuery->fetch_assoc()) {
        $totalRevenue += $orderResult['monto_factura'];
    }

    echo '' . $id_moneda . '' . number_format($totalRevenue, 2) . '';
}
/*--------------------------------------------------------------*/
/* Funcion para obtener el total de Egresos
/*--------------------------------------------------------------*/
function total_egresos()
{
    $id_moneda    = get_row('perfil', 'moneda', 'id_perfil', 1);
    $fecha_actual = date('Y-m-d');
    global $conexion;
    $orderSql    = "SELECT * FROM facturas_compras where date(fecha_factura) = '$fecha_actual'";
    $orderQuery  = $conexion->query($orderSql);
    $totalEgreso = 0;
    while ($orderResult = $orderQuery->fetch_assoc()) {
        $totalEgreso += $orderResult['monto_factura'];
    }

    echo '' . $id_moneda . '' . number_format($totalEgreso, 2) . '';
}
/*--------------------------------------------------------------*/
/* Funcion para obtener el total de Inventario Bajo
/*--------------------------------------------------------------*/
function poner_inventario()
{
    global $conexion;
    $lowStockSql   = "SELECT * FROM productos WHERE stock_producto <= 3 AND estado_producto = 1";
    $lowStockQuery = $conexion->query($lowStockSql);

    echo '' . $countLowStock . '';
}
/*--------------------------------------------------------------*/
/* Funcion para obtener las Ultimas Ventas
/*--------------------------------------------------------------*/
function latest_order()
{
    global $conexion;
    $id_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);

    $sql = mysqli_query($conexion, "select * from facturas_ventas where id_cliente >0 order by  id_factura desc limit 0,5");
    while ($rw = mysqli_fetch_array($sql)) {
        $id_factura     = $rw['id_factura'];
        $numero_factura = $rw['numero_factura'];

        $supplier_id       = $rw['id_cliente'];
        $sql_s             = mysqli_query($conexion, "select nombre_cliente from clientes where id_cliente='" . $supplier_id . "'");
        $rw_s              = mysqli_fetch_array($sql_s);
        $supplier_name     = $rw_s['nombre_cliente'];
        $date_added        = $rw['fecha_factura'];
        list($date, $hora) = explode(" ", $date_added);
        list($Y, $m, $d)   = explode("-", $date);
        $fecha             = $d . "-" . $m . "-" . $Y;
        $total             = number_format($rw['monto_factura'], 2);
        ?>
        <tr>
            <td><a href="editar_venta.php?id_factura=<?php echo $id_factura; ?>" data-toggle="tooltip" title="Ver Factura"><label class='badge badge-primary'><?php echo $numero_factura; ?></label></a></td>
            <td><?php echo $fecha; ?></td>
            <td class='text-left'><b><?php echo $id_moneda . '' . $total; ?></b></td>
        </tr>
        <?php

    }
}

function visitas()
{
    /*global $conexion;
    $id_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);

    $sql="select count(id) valor, pagina, fecha_hora from registros_visitas where pagina <> 'PRODUCTO' group by pagina UNION
select count(id) valor, productos.nombre_producto, fecha_hora from registros_visitas, productos where registros_visitas.id_producto=productos.id_producto and registros_visitas.id_producto <> 0 group by registros_visitas.ID_PRODUCTO
order by valor desc"; 
    //echo $sql;
    $sql = mysqli_query($conexion, "$sql");
    while ($rw = mysqli_fetch_array($sql)) {
        //$id_factura     = $rw['id'];
      //  $numero_factura = $rw['numero_factura'];

       // $supplier_id       = $rw['id_cliente'];
     //   $sql_s             = mysqli_query($conexion, "select nombre_cliente from clientes where id_cliente='" . $supplier_id . "'");
     //   $rw_s              = mysqli_fetch_array($sql_s);
        $pagina     = $rw['pagina'];
        $date_added        = $rw['fecha_hora'];
        list($date, $hora) = explode(" ", $date_added);
        list($Y, $m, $d)   = explode("-", $date);
        $fecha             = $d . "-" . $m . "-" . $Y;
        $total             = $rw['valor'];
        ?>
        <tr>
            <td><a href="#" data-toggle="tooltip" title=""><label class='badge badge-primary'><?php echo strtoupper($pagina); ?></label></a></td>
           
            <td class='text-right'><b><?php echo $total; ?></b></td>
        </tr>
        <?php

    }*/
}

function ultimos_pedidos()
{
    global $conexion;
    $id_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);

    $sql = mysqli_query($conexion, "select * from facturas_cot where id_cliente >0 order by  id_factura desc limit 0,5");
    while ($rw = mysqli_fetch_array($sql)) {
        $id_factura     = $rw['id_factura'];
        $numero_factura = $rw['numero_factura'];

        $supplier_id       = $rw['id_cliente'];
        $sql_s             = mysqli_query($conexion, "select nombre_cliente from clientes where id_cliente='" . $supplier_id . "'");
        $rw_s              = mysqli_fetch_array($sql_s);
        $supplier_name     = $rw_s['nombre_cliente'];
        $date_added        = $rw['fecha_factura'];
        list($date, $hora) = explode(" ", $date_added);
        list($Y, $m, $d)   = explode("-", $date);
        $fecha             = $d . "-" . $m . "-" . $Y;
        $total             = number_format($rw['monto_factura'], 2);
        ?>
        <tr>
            <td><a href="editar_venta.php?id_factura=<?php echo $id_factura; ?>" data-toggle="tooltip" title="Ver Factura"><label class='badge badge-primary'><?php echo $numero_factura; ?></label></a></td>
            <td><?php echo $fecha; ?></td>
            <td class='text-left'><b><?php echo $id_moneda . '' . $total; ?></b></td>
        </tr>
        <?php

    }
}
/*--------------------------------------------------------------*/
/* Funcion para obtener el total de Ventas del Vendedor
/*--------------------------------------------------------------*/
function venta_users()
{
    $id_moneda    = get_row('perfil', 'moneda', 'id_perfil', 1);
    $fecha_actual = date('Y-m-d');
    $users        = intval($_SESSION['id_users']);
    global $conexion;
    $orderSql   = "SELECT * FROM facturas_ventas where id_users_factura = '$users' and date(fecha_factura) = '$fecha_actual'";
    $orderQuery = $conexion->query($orderSql);
    $countOrder = $orderQuery->num_rows;

    $totalRevenue = 0;
    while ($orderResult = $orderQuery->fetch_assoc()) {
        $totalRevenue += $orderResult['monto_factura'];
    }

    echo '' . $id_moneda . '' . number_format($totalRevenue, 2) . '';
}
/*--------------------------------------------------------------*/
/* Calculo del Descuento
/*--------------------------------------------------------------*/
function rebajas($base, $dto = 0)
{
    $ahorro = ($base * $dto) / 100;
    $final  = $base - $ahorro;
    return $final;
}
/*--------------------------------------------------------------*/
/* Control de Stock
/*--------------------------------------------------------------*/
function guardar_historial($id_producto, $user_id, $fecha, $nota, $reference, $quantity, $tipo)
{
    global $conexion;
    $sql = "INSERT INTO historial_productos (id_historial, id_producto, id_users, fecha_historial, nota_historial, referencia_historial, cantidad_historial, tipo_historial)
  VALUES (NULL, '$id_producto', '$user_id', '$fecha', '$nota', '$reference', '$quantity','$tipo');";
    $query = mysqli_query($conexion, $sql);

}
function agregar_stock($id_producto, $quantity)
{
    global $conexion;
    $update = mysqli_query($conexion, "update productos set stock_producto=stock_producto+'$quantity' where id_producto='$id_producto' and inv_producto=0");
    if ($update) {
        return 1;
    } else {
        return 0;
    }

}
function eliminar_stock($id_producto, $quantity)
{
    global $conexion;
    $update = mysqli_query($conexion, "update productos set stock_producto=stock_producto-'$quantity' where id_producto='$id_producto' and inv_producto=0");
    if ($update) {
        return 1;
    } else {
        return 0;
    }

}
/*--------------------------------------------------------------*/
/* Control de KARDEX
/*--------------------------------------------------------------*/
function guardar_salidas($fecha, $id_producto, $cant_salida, $costo_salida, $total_salida, $cant_saldo, $costo_saldo, $total_saldo, $fecha_added, $users, $tipo)
{
    global $conexion;
    $sql = "INSERT INTO kardex (fecha_kardex, producto_kardex, cant_salida, costo_salida, total_salida, cant_saldo, costo_saldo, total_saldo, added_kardex, users_kardex, tipo_movimiento)
  VALUES ('$fecha','$id_producto','$cant_salida','$costo_salida','$total_salida', '$cant_saldo','$costo_saldo','$total_saldo','$fecha_added','$users','$tipo');";
    $query = mysqli_query($conexion, $sql);

}
function guardar_entradas($fecha, $id_producto, $cant_entrada, $costo_entrada, $total_entrada, $cant_saldo, $costo_promedio, $total_saldo, $fecha_added, $users, $tipo)
{
    global $conexion;
    $sql = "INSERT INTO kardex (fecha_kardex, producto_kardex, cant_entrada, costo_entrada, total_entrada, cant_saldo, costo_saldo, total_saldo, added_kardex, users_kardex, tipo_movimiento)
  VALUES ('$fecha','$id_producto','$cant_entrada','$costo_entrada','$total_entrada', '$cant_saldo','$costo_promedio','$total_saldo','$fecha_added','$users','$tipo');";
    $query = mysqli_query($conexion, $sql);

}
function formato($valor)
{
    return number_format($valor, 2);
    //return number_format($valor, 2, '.', '.');
}
function iva($sin_iva)
{
    $iva     = get_row('perfil', 'impuesto', 'id_perfil', 1);
    $con_iva = $sin_iva + ($iva * ($sin_iva / 100));
    $con_iva = round($con_iva, 2) - $sin_iva;
    return $con_iva;
}

function validar_clave2($clave) {

    if ($clave == "") {
        $verificado = false;
        return $verificado;
    }

    $x = 2;
    $sumatoria = 0;
    for ($i = strlen($clave) - 1; $i >= 0; $i--) {
        if ($x > 7) {
            $x = 2;
        }
        $sumatoria = $sumatoria + ($clave[$i] * $x);
        $x++;
    }
    $digito = $sumatoria % 11;
    $digito = 11 - $digito;

    switch ($digito) {
        case 10:
            $digito = "1";
            break;
        case 11:
            $digito = "0";
            break;
    }

    /*
      if (strtolower($digito_v)==$digito){
      $verificado=true;
      } else {
      $verificado=false;
      }

     */

    return $digito;
}
function generax($id){
    global $conexion;
    $id_factura = intval($id);
    $sql_count  = mysqli_query($conexion, "select * from facturas_ventas where id_factura='" . $id_factura . "'");
    $count      = mysqli_num_rows($sql_count);
    if ($count == 0) {
        echo "<script>alert('Factura no encontrada')</script>";
        echo "<script>window.close();</script>";
        exit;
    }
    
    $sql_factura    = mysqli_query($conexion, "select * from facturas_ventas where id_factura='" . $id_factura . "'");
    $rw_factura     = mysqli_fetch_array($sql_factura);
    $xml_detalles = '<detalles>';
    $query = mysqli_query($conexion, "SELECT  productos.id_producto, productos.codigo_producto as 'codigo', productos.descripcion_producto as 'descripcion',
                                    detalle_fact_ventas.cantidad as 'cantidad', detalle_fact_ventas.precio_venta as 'precioUnitario' 
                                    FROM detalle_fact_ventas
        INNER JOIN facturas_ventas  ON detalle_fact_ventas.id_factura = facturas_ventas.id_factura
        left JOIN productos ON productos.id_producto = detalle_fact_ventas.id_producto WHERE detalle_fact_ventas.id_factura = '" . $id_factura . "'");
    $contadorProductos = 0;
    $detallesProductos = array();
    $totalSinImpuestos = 0;
    while ($data_productos = $query->fetch_assoc()) {
        
        if($data_productos["descripcion"] == ''){
            $descripcion = 'sin descripcion';
        }else{
            $descripcion = $data_productos["descripcion"];
        }
        $xml_detalles .= '<detalle>
        <codigoPrincipal>' . $data_productos["codigo"] . '</codigoPrincipal>
        <codigoAuxiliar>' .$data_productos["codigo"]. '</codigoAuxiliar>
        <descripcion>' .$descripcion . '</descripcion>
        <cantidad>' . $data_productos['cantidad'] . '</cantidad>
        <precioUnitario>' .number_format( $data_productos['precioUnitario'], 2) . '</precioUnitario>            
        <descuento>0</descuento>
        <precioTotalSinImpuesto>' . number_format($data_productos['precioUnitario'], 2)  . '</precioTotalSinImpuesto>';
        $xml_detalles .= '<impuestos>';
                
                    $xml_detalles .= '
                <impuesto>
                    <codigo>2</codigo>
                    <codigoPorcentaje>0</codigoPorcentaje>
                    <tarifa>0</tarifa>
                    <baseImponible>' .number_format( $data_productos['precioUnitario'], 2)  . '</baseImponible>
                    <valor>0</valor>
                </impuesto></impuestos></detalle>
            ';
        $totalSinImpuestos +=  $data_productos['precioUnitario'];
        //$totalSinImpuestostotal +=  $dataCitas['valortotal'];
    }
    $xml_detalles .= '</detalles>';

    // Datos para el Encabezado del XML
    $query = mysqli_query($conexion, "SELECT * from perfil ORDER BY id_perfil DESC")
    or die('error: ' . mysqli_error($conexion));
    $dataperfil = mysqli_fetch_assoc($query);
    //datos del cliente
    $id_tipo_ambiente= $dataperfil['ambiente'];
    $id_tipo_emision = $dataperfil['tipoEmision'];
    $razon_social_empresa = $dataperfil['giro_empresa'];
    $nombre_comercial_empresa = $dataperfil['nombre_empresa'];
    $nro_documento_empresa = $dataperfil['ruc'];
    //$nro_documento_empresa = '1713683801001';


    $clave_acceso = '';
    $codigo_establecimiento = $dataperfil['codigo_establecimiento'];
    $codigo_punto_emision = $dataperfil['codigo_punto_emision'];
    //$secuencial = $id_factura;


    $secuencial = $rw_factura['secuencial'];

    $direccion_empresa = $dataperfil['direccion'];
    //$fecha_emision = date('m/d/Y h:i:s a', time());
    $fecha_emision = date('m/d/Y h:i:s a', time());

    $direccion_sucursal = $dataperfil['direccion'];
    //$id_tipo_documento = '05';
    $id_tipo_documento = '04';

    $ruta_firma = $dataperfil['firma'];
    $pass_firma = $dataperfil['passFirma'];
    $valortotal = $rw_factura['monto_factura'];
    $formaPago = $rw_factura['formaPago'];

    //Cliente
    $id_cliente = $rw_factura['id_cliente'];
    $querycliente = mysqli_query($conexion, "SELECT * from clientes where id_cliente='" . $id_cliente . "'")
    or die('error: ' . mysqli_error($conexion));
    $datacliente = mysqli_fetch_assoc($querycliente);

    $nombre_cliente = $datacliente['nombre_cliente'];
    $direccion_cliente = $datacliente['direccion_cliente'];

    $clave = "" . date('dmY', strtotime($fecha_emision)) . "" . '01' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_factura, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision . "";
    $digito_verificador_clave = validar_clave2($clave);
    $clave_acceso = "" . date('dmY', strtotime($fecha_emision)) . "" . '01' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_factura, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision  . "" . $digito_verificador_clave . "";
    

    $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <factura id="comprobante" version="1.1.0">
            <infoTributaria>
                <ambiente>' . $id_tipo_ambiente . '</ambiente>
                <tipoEmision>' . $id_tipo_emision . '</tipoEmision>
                <razonSocial>' . $razon_social_empresa . '</razonSocial>
                <nombreComercial>' . $nombre_comercial_empresa . '</nombreComercial>
                <ruc>' . $nro_documento_empresa . '</ruc>
                <claveAcceso>' . $clave_acceso . '</claveAcceso>
                <codDoc>01</codDoc>
                <estab>' . $codigo_establecimiento . '</estab>
                <ptoEmi>' . $codigo_punto_emision . '</ptoEmi>
                <secuencial>' . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . '</secuencial>
                <dirMatriz>' . $direccion_empresa . '</dirMatriz>
            </infoTributaria>
            <infoFactura>
                <fechaEmision>' . date("d/m/Y", strtotime($fecha_emision)) . '</fechaEmision>
                <dirEstablecimiento>' . $direccion_sucursal . '</dirEstablecimiento>
                <obligadoContabilidad>NO</obligadoContabilidad>       
                <tipoIdentificacionComprador>' . $id_tipo_documento . '</tipoIdentificacionComprador>
                <razonSocialComprador>'.$nombre_cliente.'</razonSocialComprador>
                <identificacionComprador>0490041877001</identificacionComprador>
                <direccionComprador>'.$direccion_cliente.'</direccionComprador>';
                $xml.='<totalSinImpuestos>' . number_format($valortotal, 2) . '</totalSinImpuestos>';
                $xml .= '<totalDescuento>0</totalDescuento>';

                $xml .=   '<totalConImpuestos>
                    <totalImpuesto>
                        <codigo>2</codigo>
                        <codigoPorcentaje>0</codigoPorcentaje>
                        <baseImponible>' . number_format($totalSinImpuestos, 2) . '</baseImponible>
                        <tarifa>0</tarifa>                
                        <valor>0.00</valor>
                    </totalImpuesto>';
                $xml .='</totalConImpuestos>        
                <propina>0.00</propina>        
                <importeTotal>' . number_format($totalSinImpuestos, 2) . '</importeTotal>
                <moneda>DOLAR</moneda>
                <pagos>
                    <pago>
                        <formaPago>'.$formaPago.'</formaPago>
                        <total>' . number_format($totalSinImpuestos, 2) . '</total>
                        <plazo>1</plazo>
                        <unidadTiempo>Dias</unidadTiempo>
                    </pago>            
                </pagos>
                <valorRetIva>0.00</valorRetIva>
                <valorRetRenta>0.00</valorRetRenta>
            </infoFactura>';
            $xml_detalles .= '
            <infoAdicional>
                <campoAdicional nombre="Direccion">direccion</campoAdicional>
                <campoAdicional nombre="Telefono">telefono</campoAdicional>		
                <campoAdicional nombre="Email">email</campoAdicional>
            </infoAdicional>
        </factura>';
    
    //$file = fopen("C:/xampp/htdocs/punto_venta/vistas/xml/comprobantes/factura_" . $id_factura . ".xml", "w+");
    $file = fopen($_SERVER['DOCUMENT_ROOT']."/imporsuitv01/sysadmin/vistas/xml/comprobantes/factura_" . $id_factura . ".xml", "w+");
    fwrite($file, $xml.$xml_detalles);
}

function generaxmlliquidacion($id){
    global $conexion;
    $id_liquidacion = intval($id);
    $sql_count  = mysqli_query($conexion, "select * from liquidacioncompra where id_factura='" . $id_liquidacion . "'");
    $count      = mysqli_num_rows($sql_count);
    if ($count == 0) {
        echo "<script>alert('Liquidacion no encontrada')</script>";
        echo "<script>window.close();</script>";
        exit;
    }
    $sql_factura    = mysqli_query($conexion, "select * from liquidacioncompra where id_factura='" . $id_liquidacion . "'");
    $rw_factura     = mysqli_fetch_array($sql_factura);

    $xml_detalles = '<detalles>';
    $query = mysqli_query($conexion, "SELECT  productos.id_producto, productos.codigo_producto as 'codigo', productos.descripcion_producto as 'descripcion',
                                  liquidacioncomprahasproducto.cantidad as 'cantidad', liquidacioncomprahasproducto.precio_venta as 'precioUnitario' 
                                  FROM liquidacioncomprahasproducto
        INNER JOIN liquidacioncompra  ON liquidacioncomprahasproducto.id_liquidacion = liquidacioncompra.id_factura
        left JOIN productos ON productos.id_producto = liquidacioncomprahasproducto.id_producto WHERE liquidacioncomprahasproducto.id_liquidacion = '" . $id_liquidacion . "'");
    $contadorProductos = 0;
    $detallesProductos = array();
    $totalSinImpuestos = 0;
    while ($data_productos = $query->fetch_assoc()) {
        if($data_productos["descripcion"] == ''){
            $descripcion = 'sin descripcion';
        }else{
            $descripcion = $data_productos["descripcion"];
        }
        $xml_detalles .= '<detalle>
        <codigoPrincipal>' .$data_productos["codigo"]. '</codigoPrincipal>
        <codigoAuxiliar>' .$data_productos["codigo"]. '</codigoAuxiliar>
        <descripcion>' .$descripcion . '</descripcion>
        <cantidad>' . $data_productos['cantidad'] . '</cantidad>
        <precioUnitario>' .number_format( $data_productos['precioUnitario'], 2) . '</precioUnitario>            
        <descuento>0.00</descuento>
        <precioTotalSinImpuesto>' . number_format($data_productos['precioUnitario'], 2)  . '</precioTotalSinImpuesto>';
        $xml_detalles .= '<impuestos>';
                
                      $xml_detalles .= '
                  <impuesto>
                      <codigo>2</codigo>
                      <codigoPorcentaje>0</codigoPorcentaje>
                      <tarifa>0</tarifa>
                      <baseImponible>' .number_format( $data_productos['precioUnitario'], 2)  . '</baseImponible>
                      <valor>0</valor>
                  </impuesto></impuestos></detalle>
              ';
        $totalSinImpuestos +=  $data_productos['precioUnitario'];
        
        //$totalSinImpuestostotal +=  $dataCitas['valortotal'];
    }
    $xml_detalles .= '</detalles>';

    // Datos para el Encabezado del XML
    $query = mysqli_query($conexion, "SELECT * from perfil ORDER BY id_perfil DESC")
    or die('error: ' . mysqli_error($conexion));
    $dataperfil = mysqli_fetch_assoc($query);
    $id_tipo_ambiente= $dataperfil['ambiente'];
    $id_tipo_emision = $dataperfil['tipoEmision'];
    $razon_social_empresa = $dataperfil['giro_empresa'];
    $nombre_comercial_empresa = $dataperfil['nombre_empresa'];
    $nro_documento_empresa = $dataperfil['ruc'];
    //$nro_documento_empresa = '1713683801001';

    $clave_acceso = '';
    $codigo_establecimiento = $dataperfil['codigo_establecimiento'];
    $codigo_punto_emision = $dataperfil['codigo_punto_emision'];
    //$secuencial = $id_factura;
    $secuencial = $id_liquidacion;   
    $direccion_empresa = $dataperfil['direccion'];
    //$fecha_emision = date('m/d/Y h:i:s a', time());
    $fecha_emision = date('m/d/Y h:i:s a', time());

    $direccion_sucursal = $dataperfil['direccion'];
    //$id_tipo_documento = '05';
    $id_tipo_documento = '04';

    $ruta_firma = $dataperfil['firma'];
    $pass_firma = $dataperfil['passFirma'];
    $valortotal = $rw_factura['monto_factura'];

    $clave = "" . date('dmY', strtotime($fecha_emision)) . "" . '03' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_liquidacion, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision . "";
    $digito_verificador_clave = validar_clave2($clave);
    $clave_acceso = "" . date('dmY', strtotime($fecha_emision)) . "" . '03' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_liquidacion, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision  . "" . $digito_verificador_clave . "";
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <liquidacionCompra id="comprobante" version="1.0.0">
            <infoTributaria>
                <ambiente>' . $id_tipo_ambiente . '</ambiente>
                <tipoEmision>' . $id_tipo_emision . '</tipoEmision>
                <razonSocial>' . $razon_social_empresa . '</razonSocial>
                <nombreComercial>' . $nombre_comercial_empresa . '</nombreComercial>
                <ruc>' . $nro_documento_empresa . '</ruc>
                <claveAcceso>' . $clave_acceso . '</claveAcceso>
                <codDoc>03</codDoc>
                <estab>' . $codigo_establecimiento . '</estab>
                <ptoEmi>' . $codigo_punto_emision . '</ptoEmi>
                <secuencial>' . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . '</secuencial>
                <dirMatriz>' . $direccion_empresa . '</dirMatriz>
            </infoTributaria>
            <infoLiquidacionCompra>
                <fechaEmision>' . date("d/m/Y", strtotime($fecha_emision)) . '</fechaEmision>
                <dirEstablecimiento>' . $direccion_sucursal . '</dirEstablecimiento>
                <obligadoContabilidad>NO</obligadoContabilidad>
                <tipoIdentificacionProveedor>' . $id_tipo_documento . '</tipoIdentificacionProveedor>
                <razonSocialProveedor>Alexandra</razonSocialProveedor>
                <identificacionProveedor>0490041877001</identificacionProveedor>
                <direccionProveedor>Call123</direccionProveedor>';
                $xml.='<totalSinImpuestos>' . number_format($valortotal, 2) . '</totalSinImpuestos>
                <totalDescuento>0.00</totalDescuento>';
                $xml .='<totalConImpuestos>
                    <totalImpuesto>
                        <codigo>2</codigo>
                        <codigoPorcentaje>0</codigoPorcentaje>
                        <baseImponible>' . number_format($totalSinImpuestos, 2) . '</baseImponible>
                        <valor>0.00</valor>
                    </totalImpuesto>';
                $xml .='</totalConImpuestos>
                <importeTotal>' . number_format($totalSinImpuestos, 2) . '</importeTotal>
                <moneda>DOLAR</moneda>
                <pagos>
                    <pago>
                        <formaPago>01</formaPago>
                        <total>' . number_format($totalSinImpuestos, 2) . '</total>
                    </pago>
                </pagos>
            </infoLiquidacionCompra>';
            $xml_detalles .= '
            <infoAdicional>
                <campoAdicional nombre="Direccion">direccion</campoAdicional>
                <campoAdicional nombre="Telefono">telefono</campoAdicional>		
                <campoAdicional nombre="Email">email</campoAdicional>
            </infoAdicional>
        </liquidacionCompra>';
    
    //$file = fopen("C:/xampp/htdocs/punto_venta/vistas/xml/comprobantes/LC_" . $id_liquidacion . ".xml", "w+");
    $file = fopen($_SERVER['DOCUMENT_ROOT']."/imporsuitv01/sysadmin/vistas/xml/comprobantes/LC_" . $id_liquidacion . ".xml", "w+");
    fwrite($file, $xml.$xml_detalles);
}

function generaxmlcredito($id){
    global $conexion;
    $id_credito = intval($id);
    $sql_count  = mysqli_query($conexion, "select * from notacredito where id_factura='" . $id_credito . "'");
    $count      = mysqli_num_rows($sql_count);
    if ($count == 0) {
        echo "<script>alert('Factura no encontrada')</script>";
        echo "<script>window.close();</script>";
        exit;
    }
    $sql_factura    = mysqli_query($conexion, "select * from notacredito where id_factura='" . $id_credito . "'");
    $rw_factura     = mysqli_fetch_array($sql_factura);


    $xml_detalles = '<detalles>';
    $query = mysqli_query($conexion, "SELECT  productos.id_producto, productos.codigo_producto as 'codigo', productos.descripcion_producto as 'descripcion',
                                    notacreditohasproducto.cantidad as 'cantidad', notacreditohasproducto.precio_venta as 'precioUnitario' 
                                    FROM notacreditohasproducto
        INNER JOIN notacredito  ON notacreditohasproducto.id_notacredito = notacredito.id_factura
        left JOIN productos ON productos.id_producto = notacreditohasproducto.id_producto WHERE notacreditohasproducto.id_notacredito = '" . $id_credito . "'");
    $contadorProductos = 0;
    $detallesProductos = array();
    $totalSinImpuestos = 0;

    while ($data_productos = $query->fetch_assoc()) {
        
        if($data_productos["descripcion"] == ''){
            $descripcion = 'sin descripcion';
        }else{
            $descripcion = $data_productos["descripcion"];
        }
        $xml_detalles .= '<detalle>
        <codigoInterno>01</codigoInterno>
        <descripcion>' .$descripcion . '</descripcion>
        <cantidad>' . $data_productos['cantidad'] . '</cantidad>
        <precioUnitario>' .number_format( $data_productos['precioUnitario'], 2) . '</precioUnitario>            
        <descuento>0.00</descuento>
        <precioTotalSinImpuesto>' . number_format($data_productos['precioUnitario'], 2)  . '</precioTotalSinImpuesto>';
        $xml_detalles .= '<impuestos>';
                
                    $xml_detalles .= '
                <impuesto>
                    <codigo>2</codigo>
                    <codigoPorcentaje>0</codigoPorcentaje>
                    <tarifa>0</tarifa>
                    <baseImponible>' .number_format( $data_productos['precioUnitario'], 2)  . '</baseImponible>
                    <valor>0</valor>
                </impuesto></impuestos></detalle>
            ';
        $totalSinImpuestos +=  $data_productos['precioUnitario'];
        
        //$totalSinImpuestostotal +=  $dataCitas['valortotal'];
    }
    $xml_detalles .= '</detalles>';

    // Datos para el Encabezado del XML
    $query = mysqli_query($conexion, "SELECT * from perfil ORDER BY id_perfil DESC")
    or die('error: ' . mysqli_error($conexion));
    $dataperfil = mysqli_fetch_assoc($query);
    $id_tipo_ambiente= $dataperfil['ambiente'];
    $id_tipo_emision = $dataperfil['tipoEmision'];
    $razon_social_empresa = $dataperfil['giro_empresa'];
    $nombre_comercial_empresa = $dataperfil['nombre_empresa'];
    $nro_documento_empresa = $dataperfil['ruc'];
    //$nro_documento_empresa = '1713683801001';


    $clave_acceso = '';
    $codigo_establecimiento = $dataperfil['codigo_establecimiento'];
    $codigo_punto_emision = $dataperfil['codigo_punto_emision'];
    //$secuencial = $id_factura;
    $secuencial = $id_credito;   
    $direccion_empresa = $dataperfil['direccion'];
    //$fecha_emision = date('m/d/Y h:i:s a', time());
    $fecha_emision = date('m/d/Y h:i:s a', time());

    $direccion_sucursal = $dataperfil['direccion'];
    //$id_tipo_documento = '05';
    $id_tipo_documento = '04';

    $ruta_firma = $dataperfil['firma'];
    $pass_firma = $dataperfil['passFirma'];
    $valortotal = $rw_factura['monto_factura'];
    $fechaemisiondocmodulo = $rw_factura['fechaemisiondocmodulo'];
    $fechaemisiondocmodulo = date('d/m/Y', strtotime($fechaemisiondocmodulo));
    $motivo = $rw_factura['motivo'];
    $numdocmod = $rw_factura['numdocmod'];

    $clave = "" . date('dmY', strtotime($fecha_emision)) . "" . '04' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_credito, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision . "";
    $digito_verificador_clave = validar_clave2($clave);
    $clave_acceso = "" . date('dmY', strtotime($fecha_emision)) . "" . '04' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_credito, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision  . "" . $digito_verificador_clave . "";
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <notaCredito id="comprobante" version="1.0.0">
            <infoTributaria>
                <ambiente>' . $id_tipo_ambiente . '</ambiente>
                <tipoEmision>' . $id_tipo_emision . '</tipoEmision>
                <razonSocial>' . $razon_social_empresa . '</razonSocial>
                <nombreComercial>' . $nombre_comercial_empresa . '</nombreComercial>
                <ruc>' . $nro_documento_empresa . '</ruc>
                <claveAcceso>' . $clave_acceso . '</claveAcceso>
                <codDoc>04</codDoc>
                <estab>' . $codigo_establecimiento . '</estab>
                <ptoEmi>' . $codigo_punto_emision . '</ptoEmi>
                <secuencial>' . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . '</secuencial>
                <dirMatriz>' . $direccion_empresa . '</dirMatriz>
            </infoTributaria>
            <infoNotaCredito>
                <fechaEmision>' . date("d/m/Y", strtotime($fecha_emision)) . '</fechaEmision>
                <dirEstablecimiento>' . $direccion_sucursal . '</dirEstablecimiento>
                <tipoIdentificacionComprador>' . $id_tipo_documento . '</tipoIdentificacionComprador>
                <razonSocialComprador>Alexandra</razonSocialComprador>
                <identificacionComprador>0490041877001</identificacionComprador>
                <obligadoContabilidad>NO</obligadoContabilidad>
                <codDocModificado>01</codDocModificado>
                <numDocModificado>'.$numdocmod.'</numDocModificado>
                <fechaEmisionDocSustento>'. $fechaemisiondocmodulo . '</fechaEmisionDocSustento>';
                $xml.='<totalSinImpuestos>' . number_format($valortotal, 2) . '</totalSinImpuestos>';
                $xml .= '<valorModificacion>' . number_format($valortotal, 2) . '</valorModificacion>
                <moneda>DOLAR</moneda>';
                $xml .='<totalConImpuestos>
                    <totalImpuesto>
                        <codigo>2</codigo>
                        <codigoPorcentaje>0</codigoPorcentaje>
                        <baseImponible>' . number_format($totalSinImpuestos, 2) . '</baseImponible>
                        <valor>0.00</valor>
                    </totalImpuesto>';
                $xml .='</totalConImpuestos>        
                <motivo>motivo</motivo>
            </infoNotaCredito>';
            $xml_detalles .= '
            <infoAdicional>
                <campoAdicional nombre="Direccion">direccion</campoAdicional>
                <campoAdicional nombre="Telefono">telefono</campoAdicional>		
                <campoAdicional nombre="Email">email</campoAdicional>
            </infoAdicional>
        </notaCredito>';
    
    
    //$file = fopen("C:/xampp/htdocs/punto_venta/vistas/xml/comprobantes/NC_" . $id_credito . ".xml", "w+");
    $file = fopen($_SERVER['DOCUMENT_ROOT']."/imporsuitv01/sysamin/vistas/xml/comprobantes/NC_" . $id_credito . ".xml", "w+");
    fwrite($file, $xml.$xml_detalles);
}

function generaxmldebito($id){
    global $conexion;
    $id_debito = intval($id);
    $sql_count  = mysqli_query($conexion, "select * from notadebito20 where id_factura='" . $id_debito . "'");
    $count      = mysqli_num_rows($sql_count);
    if ($count == 0) {
        echo "<script>alert('Factura no encontrada')</script>";
        echo "<script>window.close();</script>";
        exit;
    }
    $sql_factura    = mysqli_query($conexion, "select * from notadebito20 where id_factura='" . $id_debito . "'");
    $rw_factura     = mysqli_fetch_array($sql_factura);
    //$xml_detalles = '<detalles>';
    $query = mysqli_query($conexion, "SELECT  *
                                    FROM motivodebito20
                                    LEFT JOIN notadebito20 ON notadebito20.id_factura = motivodebito20.id_notadebito
                                    WHERE motivodebito20.id_notadebito = '" . $id_debito . "'");
    $contadorProductos = 0;
    $detallesProductos = array();
    $totalSinImpuestos = 0;
    $xml_impuestos = '';
        $xml_impuestosfinal = '';
        $xml_pagos = '';
        $xml_motivos = '';
        $vartot = 0;
        $base = 0;
        $res = 0;
        while ($data_productos = $query->fetch_assoc()) {
    
            $base +=  $data_productos['precio_venta'];
                $res = floatval($data_productos['precio_venta'] * 0.12);
                $vartot += $res;
                $temp = floatval($res + $data_productos['precio_venta']);
                $xml_impuestos .= '<impuesto>
                                    <codigo>2</codigo>
                                    <codigoPorcentaje>2</codigoPorcentaje>
                                    <tarifa>12</tarifa>
                                    <baseImponible>' . $data_productos['precio_venta'] .'</baseImponible>
                                    <valor>' . $res .'</valor>
                                </impuesto>';
                $xml_pagos .= '<pago>
                                <formaPago>01</formaPago>
                                <total>' . $temp . '</total>
                                <plazo>12</plazo>
                                <unidadTiempo>Dias</unidadTiempo>
                            </pago>';
                $xml_motivos .= '<motivo>
                                    <razon>' . $data_productos['descripcion_venta'] . '</razon>
                                    <valor>' . $data_productos['precio_venta'] . '</valor>
                                </motivo>';
        }
    //$xml_detalles .= '</detalles>';

    // Datos para el Encabezado del XML
    $query = mysqli_query($conexion, "SELECT * from perfil ORDER BY id_perfil DESC")
    or die('error: ' . mysqli_error($conexion));
    $dataperfil = mysqli_fetch_assoc($query);
    $id_tipo_ambiente= $dataperfil['ambiente'];
    $id_tipo_emision = $dataperfil['tipoEmision'];
    $razon_social_empresa = $dataperfil['giro_empresa'];
    $nombre_comercial_empresa = $dataperfil['nombre_empresa'];
    $nro_documento_empresa = $dataperfil['ruc'];
    //$nro_documento_empresa = '1713683801001';

    $clave_acceso = '';
    $codigo_establecimiento = $dataperfil['codigo_establecimiento'];
    $codigo_punto_emision = $dataperfil['codigo_punto_emision'];
    //$secuencial = $id_factura;
    $secuencial = $id_debito;

    $direccion_empresa = $dataperfil['direccion'];
    //$fecha_emision = date('m/d/Y h:i:s a', time());
    $fecha_emision = date('m/d/Y h:i:s a', time());

    $direccion_sucursal = $dataperfil['direccion'];
    //$id_tipo_documento = '05';
    $id_tipo_documento = '04';

    $ruta_firma = $dataperfil['firma'];
    $pass_firma = $dataperfil['passFirma'];
    //$valortotal = $rw_factura['monto_factura'];
    $num_doc_modificado = $rw_factura['numdocmod'];
    $fechaemisiondocmodulo = $rw_factura['fechaemisiondocmodulo'];
    $fechaemisiondocmodulo = date('d/m/Y', strtotime($fechaemisiondocmodulo));
    $clave = "" . date('dmY', strtotime($fecha_emision)) . "" . '05' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_debito, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision . "";
    $digito_verificador_clave = validar_clave2($clave);
    $clave_acceso = "" . date('dmY', strtotime($fecha_emision)) . "" . '05' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_debito, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision  . "" . $digito_verificador_clave . "";
    $total_sin_impuestos = $base;

    $xml = '<?xml version="1.0" encoding="UTF-8"?>
    <notaDebito id="comprobante" version="1.0.0">
        <infoTributaria>
            <ambiente>' . $id_tipo_ambiente . '</ambiente>
            <tipoEmision>' . $id_tipo_emision . '</tipoEmision>
            <razonSocial>' . $razon_social_empresa . '</razonSocial>
            <nombreComercial>' . $nombre_comercial_empresa . '</nombreComercial>
            <ruc>' . $nro_documento_empresa . '</ruc>
            <claveAcceso>' . $clave_acceso . '</claveAcceso>
            <codDoc>05</codDoc>
            <estab>' . $codigo_establecimiento . '</estab>
            <ptoEmi>' . $codigo_punto_emision . '</ptoEmi>
            <secuencial>' . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . '</secuencial>
            <dirMatriz>' . $direccion_empresa . '</dirMatriz>
        </infoTributaria>
        <infoNotaDebito>
            <fechaEmision>' . date("d/m/Y", strtotime($fecha_emision)) . '</fechaEmision>
            <dirEstablecimiento>' . $direccion_sucursal . '</dirEstablecimiento>
            <tipoIdentificacionComprador>' . $id_tipo_documento . '</tipoIdentificacionComprador>
            <razonSocialComprador>Alexandra</razonSocialComprador>
            <identificacionComprador>0490041877001</identificacionComprador>
            <obligadoContabilidad>NO</obligadoContabilidad>      
            <codDocModificado>01</codDocModificado>
            <numDocModificado>' . $num_doc_modificado . '</numDocModificado>
            <fechaEmisionDocSustento>' . $fechaemisiondocmodulo . '</fechaEmisionDocSustento>';
            $xml.='<totalSinImpuestos>' . number_format($total_sin_impuestos, 2) . '</totalSinImpuestos>
            <impuestos>';

            $xml .= $xml_impuestos;
            $total =  floatval($base + $res);
            $xml .= '</impuestos>
            <valorTotal>' . $total . '</valorTotal>
            <pagos>';
                $xml .= $xml_pagos;
                $xml .= '</pagos>
        </infoNotaDebito>
        <motivos>';
        $xml .= $xml_motivos;
        $xml .= '</motivos>
        <infoAdicional>
            <campoAdicional nombre="Direccion">PRUEBAS </campoAdicional>
            <campoAdicional nombre="Telefono">pruebas@hotmail.com</campoAdicional>		
            <campoAdicional nombre="Email">022070995</campoAdicional>
        </infoAdicional>
    </notaDebito>';
        
        
    //$file = fopen("C:/xampp/htdocs/punto_venta/vistas/xml/comprobantes/ND_" . $id_debito . ".xml", "w+");
    $file = fopen($_SERVER['DOCUMENT_ROOT']."/imporsuitv01/sysadmin/vistas/xml/comprobantes/ND_" . $id_debito . ".xml", "w+");
    fwrite($file, $xml);
}

function generaxmlguia($id){
    global $conexion;
    $id_guia = intval($id);
    $sql_count  = mysqli_query($conexion, "select * from guia where id_factura='" . $id_guia . "'");
    $count      = mysqli_num_rows($sql_count);
    if ($count == 0) {
        echo "<script>alert('Guia no encontrada')</script>";
        echo "<script>window.close();</script>";
        exit;
    }
    $sql_factura    = mysqli_query($conexion, "select * from guia where id_factura='" . $id_guia . "'");
    $rw_factura     = mysqli_fetch_array($sql_factura);

    $xml_detalles = '<detalles>';
    $query = mysqli_query($conexion, "SELECT  productos.id_producto, productos.codigo_producto as 'codigo', productos.descripcion_producto as 'descripcion',
                                    guiahasproducto.cantidad as 'cantidad', guiahasproducto.precio_venta as 'precioUnitario' 
                                    FROM guiahasproducto
        INNER JOIN guia  ON guiahasproducto.id_notacredito = guia.id_factura
        left JOIN productos ON productos.id_producto = guiahasproducto.id_producto WHERE guiahasproducto.id_notacredito = '" . $id_guia . "'");
    $contadorProductos = 0;
    $detallesProductos = array();
    $totalSinImpuestos = 0;
    while ($data_productos = $query->fetch_assoc()) {
    
        if($data_productos["descripcion"] == ''){
            $descripcion = 'sin descripcion';
        }else{
            $descripcion = $data_productos["descripcion"];
        }
        
        $xml_detalles .= '<detalle>
                                <codigoInterno>Ccv</codigoInterno>
                                <descripcion>' .$descripcion . '</descripcion>
                                <cantidad>' . $data_productos['cantidad'] . '</cantidad>
                        </detalle>';
    
        //<precioUnitario>' .number_format( $data_productos['precioUnitario'], 2) . '</precioUnitario>            
        //<descuento>0.00</descuento>
        //<precioTotalSinImpuesto>' . number_format($data_productos['precioUnitario'], 2)  . '</precioTotalSinImpuesto>';
        /*$xml_detalles .= '<impuestos>';
                
                      $xml_detalles .= '
                  <impuesto>
                      <codigo>2</codigo>
                      <codigoPorcentaje>0</codigoPorcentaje>
                      <tarifa>0</tarifa>
                      <baseImponible>' .number_format( $data_productos['precioUnitario'], 2)  . '</baseImponible>
                      <valor>0</valor>
                  </impuesto></impuestos></detalle>
              ';
        $totalSinImpuestos +=  $data_productos['precioUnitario'];*/
        
        //$totalSinImpuestostotal +=  $dataCitas['valortotal'];
    }
    $xml_detalles .= '</detalles>';

    // Datos para el Encabezado del XML
    $query = mysqli_query($conexion, "SELECT * from perfil ORDER BY id_perfil DESC")
    or die('error: ' . mysqli_error($conexion));
    $dataperfil = mysqli_fetch_assoc($query);
    $id_tipo_ambiente= $dataperfil['ambiente'];
    $id_tipo_emision = $dataperfil['tipoEmision'];
    $razon_social_empresa = $dataperfil['giro_empresa'];
    $nombre_comercial_empresa = $dataperfil['nombre_empresa'];
    $nro_documento_empresa = $dataperfil['ruc'];
    //$nro_documento_empresa = '1713683801001';

    //destinatario
    $id_cliente = $rw_factura['id_cliente'];
    $querycliente = mysqli_query($conexion, "SELECT * from clientes where id_cliente='".$id_cliente."'")
    or die('error: ' . mysqli_error($conexion));
    $datacliente = mysqli_fetch_assoc($querycliente);

    $identificacionDestinatario = '0707061214';
    $razonSocialDestinatario = $datacliente['razon_social'];
    $direccion_cliente = $datacliente['direccion_cliente'];

    $clave_acceso = '';
    $codigo_establecimiento = $dataperfil['codigo_establecimiento'];
    $codigo_punto_emision = $dataperfil['codigo_punto_emision'];
    //$secuencial = $id_factura;
    $secuencial = $id_guia;   
    $direccion_empresa = $dataperfil['direccion'];
    //$fecha_emision = date('m/d/Y h:i:s a', time());
    $fecha_emision = date('m/d/Y h:i:s a', time());

    $direccion_sucursal = $dataperfil['direccion'];
    //$id_tipo_documento = '05';
    $id_tipo_documento = '06';

    $ruta_firma = $dataperfil['firma'];
    $pass_firma = $dataperfil['passFirma'];
    $valortotal = $rw_factura['monto_factura'];
    $dirPartida =  $rw_factura['dirPartida'];
    $razonSocialTransportista =  $rw_factura['razonSocialTransportista'];
    $rucTransportista =  $rw_factura['rucTransportista'];

    $fechaIniTransporte =  $rw_factura['fechaIniTransporte'];
    $fechaIniTransporte = date('d/m/Y', strtotime($fechaIniTransporte));
    $fechaFinTransporte =  $rw_factura['fechaFinTransporte'];
    $fechaFinTransporte = date('d/m/Y', strtotime($fechaFinTransporte));
    $placa =  $rw_factura['placa'];
    $motivoTraslado =  $rw_factura['motivoTraslado'];

    $clave = "" . date('dmY', strtotime($fecha_emision)) . "" . '06' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_guia, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision . "";
    $digito_verificador_clave = validar_clave2($clave);
    $clave_acceso = "" . date('dmY', strtotime($fecha_emision)) . "" . '06' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_guia, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision  . "" . $digito_verificador_clave . "";
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
    <guiaRemision id="comprobante" version="1.0.0">
        <infoTributaria>
            <ambiente>' . $id_tipo_ambiente . '</ambiente>
            <tipoEmision>' . $id_tipo_emision . '</tipoEmision>
            <razonSocial>' . $razon_social_empresa . '</razonSocial>
            <nombreComercial>' . $nombre_comercial_empresa . '</nombreComercial>
            <ruc>' . $nro_documento_empresa . '</ruc>
            <claveAcceso>' . $clave_acceso . '</claveAcceso>
            <codDoc>06</codDoc>
            <estab>' . $codigo_establecimiento . '</estab>
            <ptoEmi>' . $codigo_punto_emision . '</ptoEmi>
            <secuencial>' . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . '</secuencial>
            <dirMatriz>' . $direccion_empresa . '</dirMatriz>
        </infoTributaria>
        <infoGuiaRemision>
            <dirEstablecimiento>' . $direccion_sucursal . '</dirEstablecimiento>    
            <dirPartida>' . $dirPartida . '</dirPartida>  
            <razonSocialTransportista>' . $razonSocialTransportista . '</razonSocialTransportista>  
            <tipoIdentificacionTransportista>06</tipoIdentificacionTransportista>  
            <rucTransportista>' . $rucTransportista . '</rucTransportista>  
            <obligadoContabilidad>NO</obligadoContabilidad>
            <fechaIniTransporte>' . $fechaIniTransporte . '</fechaIniTransporte>  
            <fechaFinTransporte>' . $fechaFinTransporte . '</fechaFinTransporte>  
            <placa>' . $placa . '</placa>  
        </infoGuiaRemision>';
        $xml.='<destinatarios>
                <destinatario>
                    <identificacionDestinatario>'.$identificacionDestinatario.'</identificacionDestinatario>
                    <razonSocialDestinatario>'.$razonSocialDestinatario.'</razonSocialDestinatario>
                    <dirDestinatario>'.$direccion_cliente.'</dirDestinatario>
                    <motivoTraslado>'.$motivoTraslado.'</motivoTraslado>';
                    $xml_detalles .=
                '</destinatario>
        </destinatarios>
        <infoAdicional>
            <campoAdicional nombre="Direccion">direccion</campoAdicional>
            <campoAdicional nombre="Telefono">telefono</campoAdicional>		
            <campoAdicional nombre="Email">email</campoAdicional>
        </infoAdicional>
    </guiaRemision>';
    
    //$file = fopen("C:/xampp/htdocs/punto_venta/vistas/xml/comprobantes/LC_" . $id_liquidacion . ".xml", "w+");
    $file = fopen($_SERVER['DOCUMENT_ROOT']."/imporsuitv01/sysadmin/vistas/xml/comprobantes/GR_" . $id_guia . ".xml", "w+");
    fwrite($file, $xml.$xml_detalles);
}
function generaxmlretencion($id){
    global $conexion;
    $id_retencion = intval($id);
    $sql_count  = mysqli_query($conexion, "select * from retencion20 where id_factura='" . $id_retencion . "'");
    $count      = mysqli_num_rows($sql_count);
    if ($count == 0) {
        echo "<script>alert('Retencion no encontrada')</script>";
        echo "<script>window.close();</script>";
        exit;
    }
    $sql_factura    = mysqli_query($conexion, "select * from retencion20 where id_factura='" . $id_retencion . "'");
    $rw_factura     = mysqli_fetch_array($sql_factura);
    //$xml_detalles = '<detalles>';
    $query = mysqli_query($conexion, "SELECT  *
                                    FROM impuestocomprobanteretencion20
                                    left JOIN retencion20  ON impuestocomprobanteretencion20.id_retencion = retencion20.id_factura
                                    WHERE impuestocomprobanteretencion20.id_retencion = '" . $id_retencion . "'");
    $contadorProductos = 0;
    $detallesProductos = array();
    $totalSinImpuestos = 0;
    $xml_impuestos = '';
        $xml_impuestosfinal = '';
        $xml_pagos = '';
        $xml_motivos = '';
        $vartot = 0;
        $base = 0;
    while ($data_productos = $query->fetch_assoc()) {
        
        $xml_impuestos .= '<impuesto>
            <codigo>' . $data_productos['codigo'] . '</codigo>
            <codigoRetencion>' . $data_productos['codigoretencion'] . '</codigoRetencion>
            <baseImponible>' . $data_productos['baseimponible'] .'</baseImponible>
            <porcentajeRetener>' . $data_productos['porcentajeretener'] .'</porcentajeRetener>
            <valorRetenido>' . $data_productos['valorretenido'] .'</valorRetenido>
            <codDocSustento>' . $data_productos['coddocsustento'] .'</codDocSustento>
            <numDocSustento>' . $data_productos['numdocsustento'] .'</numDocSustento>
            <fechaEmisionDocSustento>' . date("d/m/Y", strtotime($data_productos['fechaemisiondocsustento'])) . '</fechaEmisionDocSustento>
        </impuesto>';
        
    }
    //$xml_detalles .= '</detalles>';

    // Datos para el Encabezado del XML
    $query = mysqli_query($conexion, "SELECT * from perfil ORDER BY id_perfil DESC")
    or die('error: ' . mysqli_error($conexion));
    $dataperfil = mysqli_fetch_assoc($query);
    $id_tipo_ambiente= $dataperfil['ambiente'];
    $id_tipo_emision = $dataperfil['tipoEmision'];
    $razon_social_empresa = $dataperfil['giro_empresa'];
    $nombre_comercial_empresa = $dataperfil['nombre_empresa'];
    $nro_documento_empresa = $dataperfil['ruc'];
    //$nro_documento_empresa = '1713683801001';

    //destinatario
    $id_cliente = $rw_factura['id_cliente'];
    $querycliente = mysqli_query($conexion, "SELECT * from clientes where id_cliente='".$id_cliente."'")
    or die('error: ' . mysqli_error($conexion));
    $datacliente = mysqli_fetch_assoc($querycliente);
    $identificacionDestinatario = '0707061214';
    $razonSocialDestinatario = $datacliente['razon_social'];
    $direccion_cliente = $datacliente['direccion_cliente'];

    $clave_acceso = '';
    $codigo_establecimiento = $dataperfil['codigo_establecimiento'];
    $codigo_punto_emision = $dataperfil['codigo_punto_emision'];
    //$secuencial = $id_factura;
    $secuencial = $id_retencion;
    $direccion_empresa = $dataperfil['direccion'];
    //$fecha_emision = date('m/d/Y h:i:s a', time());
    $fecha_emision = date('m/d/Y h:i:s a', time());

    $direccion_sucursal = $dataperfil['direccion'];
    //$id_tipo_documento = '05';
    $id_tipo_documento = '05';

    $ruta_firma = $dataperfil['firma'];
    $pass_firma = $dataperfil['passFirma'];
    //$valortotal = $rw_factura['monto_factura'];
    //$num_doc_modificado = $rw_factura['numdocmod'];
    $periodoFiscal = $rw_factura['periodofiscal'];

    //$fechaemisiondocmodulo = $rw_factura['fechaemisiondocmodulo'];
    //$fechaemisiondocmodulo = date('d/m/Y', strtotime($fechaemisiondocmodulo));
    $clave = "" . date('dmY', strtotime($fecha_emision)) . "" . '07' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_retencion, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision . "";
    $digito_verificador_clave = validar_clave2($clave);
    $clave_acceso = "" . date('dmY', strtotime($fecha_emision)) . "" . '07' . "" . $nro_documento_empresa . "" . $id_tipo_ambiente . "" . $codigo_establecimiento . "" . $codigo_punto_emision . "" . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . "" . str_pad($id_retencion, '8', '0', STR_PAD_LEFT) . "" . $id_tipo_emision  . "" . $digito_verificador_clave . "";
    $total_sin_impuestos = $base;

    $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <comprobanteRetencion id="comprobante" version="1.0.0">
            <infoTributaria>
                <ambiente>' . $id_tipo_ambiente . '</ambiente>
                <tipoEmision>' . $id_tipo_emision . '</tipoEmision>
                <razonSocial>' . $razon_social_empresa . '</razonSocial>
                <nombreComercial>' . $nombre_comercial_empresa . '</nombreComercial>
                <ruc>' . $nro_documento_empresa . '</ruc>
                <claveAcceso>' . $clave_acceso . '</claveAcceso>
                <codDoc>07</codDoc>
                <estab>' . $codigo_establecimiento . '</estab>
                <ptoEmi>' . $codigo_punto_emision . '</ptoEmi>
                <secuencial>' . str_pad($secuencial, '9', '0', STR_PAD_LEFT) . '</secuencial>
                <dirMatriz>' . $direccion_empresa . '</dirMatriz>
            </infoTributaria>
            <infoCompRetencion>
                <fechaEmision>' . date("d/m/Y", strtotime($fecha_emision)) . '</fechaEmision>
                <dirEstablecimiento>' . $direccion_sucursal . '</dirEstablecimiento>
                <obligadoContabilidad>NO</obligadoContabilidad>      
                <tipoIdentificacionSujetoRetenido>'.$id_tipo_documento.'</tipoIdentificacionSujetoRetenido>
                <razonSocialSujetoRetenido>'.$razonSocialDestinatario.'</razonSocialSujetoRetenido>
                <identificacionSujetoRetenido>' . $identificacionDestinatario . '</identificacionSujetoRetenido>            
                <periodoFiscal>' . $periodoFiscal . '</periodoFiscal>            
            </infoCompRetencion>
            <impuestos>';
            $xml .= $xml_impuestos;
            $xml .= '</impuestos>
            <infoAdicional>
                <campoAdicional nombre="Direccion">PRUEBAS </campoAdicional>
                <campoAdicional nombre="Telefono">pruebas@hotmail.com</campoAdicional>		
                <campoAdicional nombre="Email">022070995</campoAdicional>
            </infoAdicional>
        </comprobanteRetencion>';
        
    $file = fopen($_SERVER['DOCUMENT_ROOT']."/imporsuitv01/sysadmin/vistas/xml/comprobantes/RC_" . $id_retencion . ".xml", "w+");
    fwrite($file, $xml);
    
}
