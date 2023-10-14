<?php
/*-------------------------
Autor: Delmar Lopez
Web: softwys.com
Mail: softwysop@gmail.com
---------------------------*/
session_start();
if (!isset($_SESSION['user_login_status']) and $_SESSION['user_login_status'] != 1) {
    header("location: ../../login.php");
    exit;
}

/* Connect To Database*/
require_once "../db.php"; //Contiene las variables de configuracion para conectar a la base de datos
require_once "../php_conexion.php"; //Contiene funcion que conecta a la base de datos
//Inicia Control de Permisos
include "../permisos.php";
$user_id = $_SESSION['id_users'];
get_cadena($user_id);
$modulo = "Configuracion";
permisos($modulo, $cadena_permisos);
$query_empresa = mysqli_query($conexion, "select * from perfil where id_perfil=1");
$row           = mysqli_fetch_array($query_empresa);

?>
<?php require 'includes/header_start.php';?>

<?php require 'includes/header_end.php';?>

<!-- Begin page -->
<div id="wrapper">

	<?php require 'includes/menu.php';?>

	<!-- ============================================================== -->
	<!-- Start right Content here -->
	<!-- ============================================================== -->
	<div class="content-page">
		<!-- Start content -->
		<div class="content">
			<div class="container">
<?php if ($permisos_ver == 1) {
    ?>
				<div class="col-lg-12">
					<div class="portlet">
						<div class="portlet-heading bg-primary">
							<h3 class="portlet-title">
								Datos de Clínica
							</h3>
							<div class="portlet-widgets">
								<a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
								<span class="divider"></span>
								<a data-toggle="collapse" data-parent="#accordion1" href="#bg-primary"><i class="ion-minus-round"></i></a>
								<span class="divider"></span>
								<a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
							</div>
							<div class="clearfix"></div>
						</div>
						<div id="bg-primary" class="panel-collapse collapse show">
							<div class="portlet-body">

							<form class="form-horizontal" role="form" id="perfil" enctype="multipart/form-data">
								<div class="row">
									<div class="col-md-3">
										<div align="center">
											<img src="<?php echo $row['logo_url']; ?>" class="img-responsive" alt="profile-image" width="200px" height="200px">
										</div>
										<div class="form-group">
											<input class="form-control" data-buttonText="Logo" type="file" name="imagefile" id="imagefile" onchange="upload_image();">
										</div>

									</div>
									<!-- end col -->

									<div class="col-md-9">
										<div class="card-box">
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-3  col-form-label">Razón Social:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="nombre_empresa" value="<?php echo $row['nombre_empresa'] ?>" required autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="giro" class="col-sm-3  col-form-label">Giro:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="giro" value="<?php echo $row['giro_empresa'] ?>" required autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="fiscal" class="col-sm-3 col-form-label">RNC/Cedula:</label>
													<div class="col-sm-6">
														<input type="text" class="form-control" required name="fiscal" value="<?php echo $row['fiscal_empresa'] ?>" autocomplete="off" >
													</div>
												</div>
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-3 col-form-label">Teléfono:</label>
													<div class="col-sm-6">
														<input type="text" class="form-control" name="telefono" value="<?php echo $row['telefono'] ?>" required autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Email:</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" name="email" value="<?php echo $row['email'] ?>" autocomplete="off" >
													</div>
												</div>
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-3 col-form-label">Impuesto %:</label>
													<div class="col-sm-4">
														<input type="text" class="form-control" required name="impuesto" value="<?php echo $row['impuesto'] ?>" autocomplete="off" >
													</div>
												</div>
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-3 col-form-label">Nombre Impuesto:</label>
													<div class="col-sm-4">
														<input type="text" class="form-control UpperCase" required name="nom_impuesto" value="<?php echo $row['nom_impuesto'] ?>" autocomplete="off" >
													</div>
												</div>
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-3 col-form-label">Moneda:</label>
													<div class="col-sm-4">
														<select class='form-control input-sm' name="moneda" required>
															<?php
$sql   = "select name, symbol from  currencies group by symbol order by name ";
    $query = mysqli_query($conexion, $sql);
    while ($rw = mysqli_fetch_array($query)) {
        $simbolo = $rw['symbol'];
        $moneda  = $rw['name'];
        if ($row['moneda'] == $simbolo) {
            $selected = "selected";
        } else {
            $selected = "";
        }
        ?>
																<option value="<?php echo $simbolo; ?>" <?php echo $selected; ?>><?php echo ($simbolo); ?></option>
																<?php
}
    ?>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-3 col-form-label">Dirección:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="direccion" value="<?php echo $row["direccion"]; ?>" required autocomplete="off" >
													</div>
												</div>
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-3 col-form-label">Ciudad:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="ciudad" value="<?php echo $row["ciudad"]; ?>">
													</div>
												</div>
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-3 col-form-label">Región/Provincia:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="estado" value="<?php echo $row["estado"]; ?>">
													</div>
												</div>
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-3 col-form-label">Código postal:</label>
													<div class="col-sm-4">
														<input type="text" class="form-control UpperCase" name="codigo_postal" value="<?php echo $row["codigo_postal"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="ambiente" class="col-sm-3 col-form-label">Ambiente:</label>
													<div class="col-sm-9">
														<select name="ambiente" id="ambiente" class="form-control UpperCase">
															<?php if($row["ambiente"] === '1'){
																	?>
																	<option value="1" selected>PRUEBAS</option>
																	<option value="2">PRODUCCI&Oacute;N</option>
																	<?php
																}else{
																	?>
																	<option value="1">PRUEBAS</option>
																	<option value="2" selected>PRODUCCI&Oacute;N</option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="tipoEmision" class="col-sm-3 col-form-label">Tipo de Emisi&oacute;n:</label>
													<div class="col-sm-9">
														<select name="tipoEmision"  class="form-control UpperCase">
															<?php if($row["ambiente"] === '1'){
																	?>
																	<option value="1" selected>NORMAL</option>
																	<option value="2">Indisponibilidad SRI</option>
																	<?php
																}else{
																	?>
																	<option value="1">NORMAL</option>
																	<option value="2" selected>Indisponibilidad SRI</option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="codigo_establecimiento" class="col-sm-3 col-form-label">Codigo Establecimiento:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="codigo_establecimiento" value="<?php echo $row["codigo_establecimiento"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="codigo_punto_emision" class="col-sm-3 col-form-label">Codigo Punto de Emisi&oacute;n:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="codigo_punto_emision" value="<?php echo $row["codigo_punto_emision"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="ruc" class="col-sm-3 col-form-label">RUC:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="ruc" value="<?php echo $row["ruc"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="firma" class="col-sm-3 col-form-label">Firma:</label>
													<div class="col-sm-9">
														<input type="file" class="form-control UpperCase" name="firma" value="<?php echo $row["firma"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="passFirna" class="col-sm-3 col-form-label">Contrase&ntilde;a:</label>
													<div class="col-sm-9">
														<input type="password" class="form-control UpperCase" name="passFirna" value="<?php echo $row["passFirma"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="secuencialfactura" class="col-sm-3 col-form-label">Secuencial Factura:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="secuencialfactura" value="<?php echo $row["secuencialfactura"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="secuencialliquidacion" class="col-sm-3 col-form-label">Secuencial Liquidaci&oacute;n Compra:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="secuencialliquidacion" value="<?php echo $row["secuencialliquidacion"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="secuencialcredito" class="col-sm-3 col-form-label">Secuencial Nota Cr&eacute;dito:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="secuencialcredito" value="<?php echo $row["secuencialcredito"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="secuencialdebito" class="col-sm-3 col-form-label">Secuencial Nota D&eacute;bito:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="secuencialdebito" value="<?php echo $row["secuencialdebito"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="secuencialguia" class="col-sm-3 col-form-label">Secuencial Gu&iacute;a:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="secuencialguia" value="<?php echo $row["secuencialguia"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="secuencialretencion" class="col-sm-3 col-form-label">Secuencial Retenci&oacute;n:</label>
													<div class="col-sm-9">
														<input type="text" class="form-control UpperCase" name="secuencialretencion" value="<?php echo $row["secuencialretencion"]; ?>" autocomplete="off">
													</div>
												</div>
												<div class="form-group row">
													<label for="autofactura" class="col-sm-3 col-form-label">Envio Automatico SRI Factura:</label>
													<div class="col-sm-9">
														<select name="autofactura"  class="form-control UpperCase">
															<?php if($row["autofactura"] === '1'){
																	?>
																	<option value="1" selected>SI</option>
																	<option value="0">NO</option>
																	<?php
																}else{
																	?>
																	<option value="1">SI</option>
																	<option value="0" selected>NO</option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>		
												<div class="form-group row">
													<label for="autoliquidacion" class="col-sm-3 col-form-label">Envio Automatico SRI Liquidaci&oacute;n Compra:</label>
													<div class="col-sm-9">
														<select name="autoliquidacion"  class="form-control UpperCase">
															<?php if($row["autoliquidacion"] === '1'){
																	?>
																	<option value="1" selected>SI</option>
																	<option value="0">NO</option>
																	<?php
																}else{
																	?>
																	<option value="1">SI</option>
																	<option value="0" selected>NO</option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="autocredito" class="col-sm-3 col-form-label">Envio Automatico SRI Nota Cr&eacute;dito:</label>
													<div class="col-sm-9">
														<select name="autocredito"  class="form-control UpperCase">
															<?php if($row["autocredito"] === '1'){
																	?>
																	<option value="1" selected>SI</option>
																	<option value="0">NO</option>
																	<?php
																}else{
																	?>
																	<option value="1">SI</option>
																	<option value="0" selected>NO</option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>		
												<div class="form-group row">
													<label for="autodebito" class="col-sm-3 col-form-label">Envio Automatico SRI Nota D&eacute;bito:</label>
													<div class="col-sm-9">
														<select name="autodebito"  class="form-control UpperCase">
															<?php if($row["autodebito"] === '1'){
																	?>
																	<option value="1" selected>SI</option>
																	<option value="0">NO</option>
																	<?php
																}else{
																	?>
																	<option value="1">SI</option>
																	<option value="0" selected>NO</option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>		
												<div class="form-group row">
													<label for="autoguia" class="col-sm-3 col-form-label">Envio Automatico SRI Guia:</label>
													<div class="col-sm-9">
														<select name="autoguia"  class="form-control UpperCase">
															<?php if($row["autoguia"] === '1'){
																	?>
																	<option value="1" selected>SI</option>
																	<option value="0">NO</option>
																	<?php
																}else{
																	?>
																	<option value="1">SI</option>
																	<option value="0" selected>NO</option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>		
												<div class="form-group row">
													<label for="autoretencion" class="col-sm-3 col-form-label">Envio Automatico SRI Retenci&oacute;n:</label>
													<div class="col-sm-9">
														<select name="autoretencion"  class="form-control UpperCase">
															<?php if($row["autoretencion"] === '1'){
																	?>
																	<option value="1" selected>SI</option>
																	<option value="0">NO</option>
																	<?php
																}else{
																	?>
																	<option value="1">SI</option>
																	<option value="0" selected>NO</option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
												<div class='col-md-12' id="resultados_ajax"></div><!-- Carga los datos ajax -->


												<div class="form-group m-b-0 row">
													<div class="offset-3 col-sm-9">
														<button type="submit" class="btn btn-info waves-effect waves-light"><i class="fa fa-refresh"></i> Actualizar Datos</button>
													</div>
												</div>
											</form>

									</div>

								</div>
								<!-- end row -->


							</div>
                                                        </form>
							<!-- /.box -->


						</div>
						<?php
} else {
    ?>
		<section class="content">
			<div class="alert alert-danger" align="center">
				<h3>Acceso denegado! </h3>
				<p>No cuentas con los permisos necesario para acceder a este módulo.</p>
			</div>
		</section>
		<?php
}
?>


					</div>
				</div>
			</div>
		</div>


	</div>
	<!-- end container -->
</div>
<!-- end content -->

<?php require 'includes/pie.php';?>

</div>
<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->


</div>
<!-- END wrapper -->

<?php require 'includes/footer_start.php'
?>
<!-- ============================================================== -->
	<!-- Todo el codigo js aqui-->
	<!-- ============================================================== -->
<script>
  $( "#perfil" ).submit(function( event ) {
    $('.guardar_datos').attr("disabled", true);
	
	var data = new FormData();
	var form_data = $(this).serializeArray();
	var file_data = $('input[name="firma"]')[0].files;
	for (var i = 0; i < file_data.length; i++) {
    	form_data.push({name: 'firma', value: file_data[i]});
	}

	$.each(form_data, function (key, input) {
    	data.append(input.name, input.value);
	});

    var parametros = data;
	//alert(parametros)
	//console.log(parametros);
    $.ajax({
      type: "POST",
      url: "../ajax/editar_perfil.php",
      data: parametros,
      contentType: false, 
      processData: false,
      beforeSend: function(objeto){
        $("#resultados_ajax").html('<img src="../../img/ajax-loader.gif"> Cargando...');
      },
      success: function(datos){
        $("#resultados_ajax").html(datos);
        $('.guardar_datos').attr("disabled", false);
        //desaparecer la alerta
        $(".alert-success").delay(400).show(10, function() {
                $(this).delay(2000).hide(10, function() {
                  $(this).remove();
                });
              }); // /.alert

      }
    });
    event.preventDefault();
  })



</script>

<script>
  function upload_image(){

    var inputFileImage = document.getElementById("imagefile");
    var file = inputFileImage.files[0];
    if( (typeof file === "object") && (file !== null) )
    {
      $("#load_img").html('<img src="../../img/ajax-loader.gif"> Cargando...');
      var data = new FormData();
      data.append('imagefile',file);


      $.ajax({
            url: "../ajax/imagen_ajax.php",        // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: data,         // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
              $("#load_img").html(data);

            }
          });
    }


  }
</script>
<script>
       $(document).ready( function () {
        $(".UpperCase").on("keypress", function () {
         $input=$(this);
         setTimeout(function () {
          $input.val($input.val().toUpperCase());
         },50);
        })
       })
      </script>

	<?php require 'includes/footer_end.php'
?>

