<?php
/*-------------------------
Autor: Delmar Lopez
Web: www.softwys.com
Mail: softwysop@gmail.com
---------------------------*/
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/* Connect To Database*/
require_once "../db.php"; //Contiene las variables de configuracion para conectar a la base de datos
require_once "../php_conexion.php"; //Contiene funcion que conecta a la base de datos
//Archivo de funciones PHP
require_once "../funciones.php";
//Inicia Control de Permisos
include "../permisos.php";
$user_id = $_SESSION['id_users'];
get_cadena($user_id);
$modulo = "Ventas";
permisos($modulo, $cadena_permisos);
//Finaliza Control de Permisos
$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != null) ? $_REQUEST['action'] : '';
if ($action == 'ajax') {
    // escaping, additionally removing everything that could be (html/javascript-) code
    $q      = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
    $sTable = "facturas_ventas, clientes, users, comprobantes_sri";
    $sWhere = "";
    $sWhere .= " WHERE facturas_ventas.id_cliente=clientes.id_cliente and facturas_ventas.id_vendedor=users.id_users and comprobantes_sri.tipo = 'FACTURA'";
    if ($_GET['q'] != "") {
        $sWhere .= " and  (clientes.nombre_cliente like '%$q%' or facturas_ventas.numero_factura like '%$q%')";

    }

    $sWhere .= " order by facturas_ventas.id_factura desc";
    include 'pagination.php'; //include pagination file
    //pagination variables
    $page      = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $per_page  = 10; //how much records you want to show
    $adjacents = 4; //gap between pages after number of adjacents
    $offset    = ($page - 1) * $per_page;
    //Count the total number of row in your table*/
    //$sql2 = "SELECT count(*) AS numrows FROM $sTable  $sWhere";
    $sql2 = "SELECT COUNT(*) as 'numrows' FROM facturas_ventas INNER JOIN  clientes ON facturas_ventas.id_cliente=clientes.id_cliente 
            INNER JOIN users ON facturas_ventas.id_vendedor=users.id_users
            LEFT JOIN comprobantes_sri ON facturas_ventas.id_factura = comprobantes_sri.id_factura
            
            ORDER BY facturas_ventas.id_factura DESC";
            
    $count_query = mysqli_query($conexion, $sql2);
    
    $row         = mysqli_fetch_array($count_query);
    
    $numrows     = $row['numrows'];
    $total_pages = ceil($numrows / $per_page);
    $reload      = '../reportes/facturas.php';
    //main query to fetch the data
    $sql   = "SELECT  facturas_ventas.id_factura,facturas_ventas.numero_factura,facturas_ventas.fecha_factura,clientes.nombre_cliente,clientes.telefono_cliente,
    clientes.email_cliente,users.nombre_users,facturas_ventas.estado_factura,comprobantes_sri.Estado,facturas_ventas.monto_factura,users.apellido_users,comprobantes_sri.claveAcceso,comprobantes_sri.Mensaje  
    FROM facturas_ventas INNER JOIN  clientes ON facturas_ventas.id_cliente=clientes.id_cliente 
    INNER JOIN users ON facturas_ventas.id_vendedor=users.id_users
    LEFT JOIN comprobantes_sri ON facturas_ventas.id_factura = comprobantes_sri.id_factura
    group BY facturas_ventas.id_factura
    ORDER BY facturas_ventas.id_factura DESC LIMIT $offset,$per_page";
    
    $query = mysqli_query($conexion, $sql);
    //loop through fetched data
    if ($numrows > 0) {
        echo mysqli_error($conexion);
        ?>
        <div class="table-responsive">
          <table class="table table-sm table-striped">
             <tr  class="info">
                <th># Factura</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Estado</th>
                <th>Estado SRI</th>
                <th class='text-center'>Total</th>
                <th class='text-center'>Acciones</th>

            </tr>
            <?php
while ($row = mysqli_fetch_array($query)) {
            $id_factura       = $row['id_factura'];
            $numero_factura   = $row['numero_factura'];
            $fecha            = date("d/m/Y", strtotime($row['fecha_factura']));
            $nombre_cliente   = $row['nombre_cliente'];
            $telefono_cliente = $row['telefono_cliente'];
            $email_cliente    = $row['email_cliente'];
            $nombre_vendedor  = $row['nombre_users'] . " " . $row['apellido_users'];
            $estado_factura   = $row['estado_factura'];
            $estado_sri       = $row['Estado'];
            $claveAcceso = strval($row['claveAcceso']);
            $mensajesri       = $row['Mensaje'];
            $tituloswilf = 'Hubo un error en la autorizacion del comprobante';
            $textowilf   = $mensajesri;
            $Nrocomprobante = $numero_factura;
            $mostrarmensajes = '';
            
            if ($estado_sri == 'RECIBIDA') {
                $label_classsri = 'badge-warning';
                $estado_sri = 'RECIBIDA';
                $pdf = '';
                $xml = '';
                $reenviaremail ='';
                $mostrarmensajes = 'onclick="visualizarmensajesSRI(\'' . $tituloswilf . '\',\''.$textowilf.'\',\''.$Nrocomprobante.'\')" style="cursor: pointer"';
            }elseif ($estado_sri == 'AUTORIZADO') {
                $estado_sri = 'AUTORIZADO';
                $label_classsri = 'badge-success';
                $pdf = '<a class="dropdown-item" href="../assets/comprobantes/autorizados/Factura_'.$claveAcceso.'.pdf" download="'.$claveAcceso.'.pdf"><i class="fa fa-file-pdf-o"></i> Descargar PDF</a>';
                $xml = '<a class="dropdown-item" href="../assets/comprobantes/autorizados/'.$claveAcceso.'.xml" download="'.$claveAcceso.'.xml"><i class="fa fa-download"></i> Descargar XML</a>';
                $reenviaremail = '<a class="dropdown-item" data-toggle="modal" data-target="#reenviarEmail" ><i class="fa fa-share-square-o"></i> Reenviar Email <input type="hidden" id="obtenerclaveaccceso" name="obtenerclaveaccceso" value="'.$claveAcceso.'"/></a>';
            }elseif ($estado_sri == 'DEVUELTA') {
                $estado_sri = 'DEVUELTA';
                $label_classsri = 'badge-danger';
                $pdf = '';
                $xml = '';
                $reenviaremail ='';
                $mostrarmensajes = 'onclick="visualizarmensajesSRI(\'' . $tituloswilf . '\',\''.$textowilf.'\',\''.$Nrocomprobante.'\')" style="cursor: pointer"';
            }else{
                $estado_sri = 'Sin Envio';
                $label_classsri = 'badge-warning';
                $pdf = '';
                $xml = '';
                $reenviaremail ='';
            }
            if ($estado_factura == 0) {
                $text_estado = "Anulada";
                $label_class = 'badge-warning';
                
            }
            if ($estado_factura == 1) {
                $text_estado = "Pagada";
                $label_class = 'badge-success';
                
            } 
             if ($estado_factura == 2) {
                $text_estado = "Pendiente";
                $label_class = 'badge-danger';
                
            }
            $total_venta    = $row['monto_factura'];
            $simbolo_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);
            ?>
                        <tr >
                         <td <?php echo $mostrarmensajes; ?>><label class='badge badge-purple'><?php echo $numero_factura; ?></label></td>
                         <td <?php echo $mostrarmensajes; ?>><?php echo $fecha; ?></td>
                         <td <?php echo $mostrarmensajes; ?>><?php echo $nombre_cliente; ?></td>
                         <td <?php echo $mostrarmensajes; ?>><?php echo $nombre_vendedor; ?></td>
                         <td <?php echo $mostrarmensajes; ?>><span class="badge <?php echo $label_class; ?>"><?php echo $text_estado; ?></span></td>
                         <td <?php echo $mostrarmensajes; ?>><span class="badge <?php echo $label_classsri; ?>"><?php echo $estado_sri; ?></span></td>
                         <td class='text-left' <?php echo $mostrarmensajes; ?>><b><?php echo $simbolo_moneda . '' . number_format($total_venta, 2); ?></b></td>
                         <td class="text-center">
                          <div class="btn-group dropdown">
                            <button type="button" class="btn btn-warning btn-sm dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"> <i class='fa fa-cog'></i> <i class="caret"></i> </button>
                            <div class="dropdown-menu dropdown-menu-right">
                               <?php if ($permisos_editar == 1) {?>
                               <a class="dropdown-item" href="editar_venta.php?id_factura=<?php echo $id_factura; ?>"><i class='fa fa-edit'></i> Editar</a>
                               <a class="dropdown-item" href="#" onclick="print_ticket('<?php echo $id_factura; ?>')"><i class='fa fa-print'></i> Imprimir Ticket</a>
                               <a class="dropdown-item" href="#" onclick="imprimir_factura('<?php echo $id_factura; ?>');"><i class='fa fa-print'></i> Imprimir Factura</a>
                               <a class="dropdown-item" href="#" onclick="generarXML('<?php echo $id_factura; ?>');"><i class='fa fa-paper-plane'></i> Enviar SRI</a>
                               <?php echo $pdf; ?>
                               <?php echo $xml; ?>
                               <?php echo $reenviaremail; ?>
                               <?php }
            if ($permisos_eliminar == 1) {?>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#dataDelete" data-id="<?php echo $row['id_factura']; ?>"><i class='fa fa-trash'></i> Anular Factura</a>
                               <!--<a class="dropdown-item" href="#" data-toggle="modal" data-target="#dataDelete" data-id="<?php echo $row['id_factura']; ?>"><i class='fa fa-trash'></i> Eliminar</a>-->
                               <?php }?>


                           </div>
                       </div>

                   </td>

               </tr>
               <?php
}
        ?>
           <tr>
              <td colspan=7><span class="pull-right"><?php
echo paginate($reload, $page, $total_pages, $adjacents);
        ?></span></td>
            </tr>
        </table>
    </div>
    <?php
}
//Este else Fue agregado de Prueba de prodria Quitar
    else {
        ?>
    <div class="alert alert-warning alert-dismissible" role="alert" align="center">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>Aviso!</strong> No hay Registro de Facturas
  </div>
  <?php
}
// fin else
}
?>