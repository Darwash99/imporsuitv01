<?php session_start(); ?>
<head>
<style>
        .loader-page {
    position: fixed;
    z-index: 25000;
    background: rgb(255, 255, 255);
    left: 0px;
    top: 0px;
    height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition:all .3s ease;
  }
  .loader-page::before {
    content: "";
    position: absolute;
    border: 2px solid rgb(50, 150, 176);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    box-sizing: border-box;
    border-left: 2px solid rgba(50, 150, 176,0);
    border-top: 2px solid rgba(50, 150, 176,0);
    animation: rotarload 1s linear infinite;
    transform: rotate(0deg);
  }
  @keyframes rotarload {
      0%   {transform: rotate(0deg)}
      100% {transform: rotate(360deg)}
  }
  .loader-page::after {
    content: "";
    position: absolute;
    border: 2px solid rgba(50, 150, 176,.5);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    box-sizing: border-box;
    border-left: 2px solid rgba(50, 150, 176, 0);
    border-top: 2px solid rgba(50, 150, 176, 0);
    animation: rotarload 1s ease-out infinite;
    transform: rotate(0deg);
  }
    </style>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="../../assets/js/lib_firma_sri/js/uft8.js"></script>
<script src="../../assets/js/lib_firma_sri/js/fiddle.js"></script>
<script src="../../assets/js/lib_firma_sri/js/forge.min.js"></script>
<script src="../../assets/js/lib_firma_sri/js/moment.min.js"></script>
<script src="../../assets/js/lib_firma_sri/js/buffer.js"></script>
<?php
/*-------------------------
Autor: Obed Alvarado
Web: obedalvarado.pw
Mail: info@obedalvarado.pw
---------------------------*/
if (!isset($_SESSION['user_login_status']) and $_SESSION['user_login_status'] != 1) {
    header("location: ../../../login.php");
    exit;
}
/* Connect To Database*/
include "../../db.php";
include "../../php_conexion.php";
//Archivo de funciones PHP
include "../../funciones.php";

function validar_clave($clave) {

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
$id_factura = intval($_GET['id_factura']);
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
$digito_verificador_clave = validar_clave($clave);
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
$file = fopen("../comprobantes/factura_" . $id_factura . ".xml", "w+");
fwrite($file, $xml.$xml_detalles);
//$ruta_factura = 'http://localhost/punto_venta/vistas/xml/comprobantes/factura_' . $id_factura . ".xml";
//$ruta = 'http://localhost/punto_venta/vistas/xml/firmas/'.$ruta_firma;
//$enlace_actual = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = 'https://';
}
else {
  $protocol = 'http://';
}
$ruta_factura =  $protocol.$_SERVER['HTTP_HOST'].'/imporsuitv01/sysadmin/vistas/xml/comprobantes/factura_' . $id_factura . ".xml";
$ruta =  $protocol.$_SERVER['HTTP_HOST'].'/imporsuitv01/sysadmin/vistas/xml/firmas/'.$ruta_firma;
$ruta_certificado =  $ruta;
$pass = $pass_firma;
$ruta_respuesta='';
//var_dump($ruta_factura);die;
echo ' <script>obtenerComprobanteFirmado_sri("' . $ruta_certificado . '","' . $pass . '","' .$ruta_respuesta. '","' .$ruta_factura.'","' .$id_factura.'","FACTURA")</script>';
?>
    <script>
            /*$(window).on('load', function () {
                setTimeout(function () {
                    $(".loader-page").css({visibility:"hidden",opacity:"0"})
                }, 20000);
                
            });*/
        </script>
<?php
