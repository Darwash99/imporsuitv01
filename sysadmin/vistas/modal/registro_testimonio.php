<?php
if (isset($conexion)) {
    ?>
	<div id="nuevoTestimonio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class='fa fa-edit'></i> Nueva Linea</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" method="post" id="guardar_testimonio" name="guardar_testimonio">
						<div id="resultados_ajax"></div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="nombre" class="control-label">Autor:</label>
									<input type="text" class="form-control " id="nombre" name="nombre"  autocomplete="off" required>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="descripcion" class="control-label">Testimonio:</label>
									<textarea class="form-control "  id="descripcion" name="descripcion" maxlength="255" autocomplete="off"></textarea>
								</div>
							</div>
						</div>
                                                
                                                
                                                 
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="estado" class="control-label">Estado:</label>
									<select class="form-control" id="estado" name="estado" required>
										<option value="">-- Selecciona --</option>
										<option value="1" selected>Activo</option>
										<option value="0">Inactivo</option>
									</select>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary waves-effect waves-light" id="guardar_datos">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div><!-- /.modal -->
	<?php
}
?>