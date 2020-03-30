<?php
$ahora = date("d/m/Y");
$fecha_generacion = $ahora;
$fecha_notificacion = $ahora;
$fecha_cita = $ahora;
$fecha_vencimiento = $ahora;
$fecha_limite_tolerancia = $ahora;
if(!is_null($esquela)){
	$fecha_generacion = $esquela->fecha_generacion;
	$fecha_notificacion = $esquela->fecha_notificacion;
	$fecha_cita = $esquela->fecha_cita;
	$fecha_vencimiento = $esquela->fecha_vencimiento;
	$fecha_limite_tolerancia = $esquela->fecha_limite_tolerancia;
}
?>

<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($esquela, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}

<div class="row">
	
	<div class="col-lg-6 col-md-6 col-sm-6">
			
		<div class="form-group">
			{!! Form::label('numero', 'Número:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::text('numero', null, array('class' => 'form-control input-xs', 'id' => 'numero', 'placeholder' => 'Ingrese número de esquela')) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('estado', 'Estado:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::select('estado', $cboEstado, null, array('class' => 'form-control input-sm', 'id' => 'estado')) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('fecha_generacion', 'Fecha de Generación:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8 input-prepend input-group" style="padding-left: 10px; padding-top: 3px;">
				<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
				{!! Form::text('fecha_generacion', $fecha_generacion, array('class' => 'form-control input-xs', 'id' => 'fecha_generacion', 'style' => 'width: 150px;' , 'autocomplete' => 'off', 'data-provide' => 'datepicker', 'data-date-format' => 'dd/mm/yyyy' )) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('fecha_notificacion', 'Fecha de Notificación:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8 input-prepend input-group" style="padding-left: 10px; padding-top: 3px;">
				<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
				{!! Form::text('fecha_notificacion', $fecha_notificacion, array('class' => 'form-control input-xs', 'id' => 'fecha_notificacion', 'style' => 'width: 150px;' , 'autocomplete' => 'off', 'data-provide' => 'datepicker', 'data-date-format' => 'dd/mm/yyyy' )) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('fecha_cita', 'Fecha de Cita:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8 input-prepend input-group" style="padding-left: 10px; padding-top: 3px;">
				<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
				{!! Form::text('fecha_cita', $fecha_cita, array('class' => 'form-control input-xs', 'id' => 'fecha_cita', 'style' => 'width: 150px;' , 'autocomplete' => 'off', 'data-provide' => 'datepicker', 'data-date-format' => 'dd/mm/yyyy' )) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('verificador', 'Verificador:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::text('verificador', null, array('class' => 'form-control input-xs', 'id' => 'verificador', 'placeholder' => 'Ingrese verificador')) !!}
				{!! Form::hidden('verificador_id', null, array('id' => 'verificador_id')) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('supervisor', 'Supervisor:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::text('supervisor', null, array('class' => 'form-control input-xs', 'id' => 'supervisor', 'placeholder' => 'Ingrese supervisor')) !!}
				{!! Form::hidden('supervisor_id', null, array('id' => 'supervisor_id')) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('fecha_vencimiento', 'Fecha de Vencimiento:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8 input-prepend input-group" style="padding-left: 10px; padding-top: 3px;">
				<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
				{!! Form::text('fecha_vencimiento', $fecha_vencimiento, array('class' => 'form-control input-xs', 'id' => 'fecha_vencimiento', 'style' => 'width: 150px;' , 'autocomplete' => 'off', 'data-provide' => 'datepicker', 'data-date-format' => 'dd/mm/yyyy' )) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('fecha_limite_tolerancia', 'Fecha límite de tolerancia:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8 input-prepend input-group" style="padding-left: 10px; padding-top: 3px;">
				<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
				{!! Form::text('fecha_limite_tolerancia', $fecha_limite_tolerancia, array('class' => 'form-control input-xs', 'id' => 'fecha_limite_tolerancia', 'style' => 'width: 150px;' , 'autocomplete' => 'off', 'data-provide' => 'datepicker', 'data-date-format' => 'dd/mm/yyyy' )) !!}
			</div>
		</div>

	</div>

	<div class="col-lg-6 col-md-6 col-sm-6">
		
		<div class="form-group">
			{!! Form::label('asistencia', '¿Asistió a la cita?:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
			@if(is_null($esquela))
				<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 5px;">
					<input name="asistencia" type="radio" id="asistencia" value="1">  SI    
					<input name="asistencia" type="radio" id="asistencia" value="0" checked>  NO
				</div>
			@else
				<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 5px;">
					@if($esquela->asistencia_invitacion == 0)
						<input name="asistencia" type="radio" id="asistencia" value="1">  SI    
						<input name="asistencia" type="radio" id="asistencia" value="0" checked>  NO
					@else
						<input name="asistencia" type="radio" id="asistencia" value="1" checked>  SI    
						<input name="asistencia" type="radio" id="asistencia" value="0">  NO
					@endif
				</div>
			@endif
		</div>

		<div class="form-group">
			{!! Form::label('regularizacion', '¿Se regularizó?:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
			@if(is_null($esquela))
				<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 5px;">
					<input name="regularizacion" type="radio" id="regularizacion" value="1">  SI    
					<input name="regularizacion" type="radio" id="regularizacion" value="0" checked>  NO
				</div>
			@else
				<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 5px;">
					@if($esquela->regularizacion == 0)
						<input name="regularizacion" type="radio" id="regularizacion" value="1">  SI    
						<input name="regularizacion" type="radio" id="regularizacion" value="0" checked>  NO
					@else
						<input name="regularizacion" type="radio" id="regularizacion" value="1" checked>  SI    
						<input name="regularizacion" type="radio" id="regularizacion" value="0">  NO
					@endif
				</div>
			@endif
		</div>

		<div class="form-group">
			{!! Form::label('inconsistencia', '¿Se levantó incosistencia?:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
			@if(is_null($esquela))
				<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 5px;">
					<input name="inconsistencia" type="radio" id="inconsistencia" value="1">  SI    
					<input name="inconsistencia" type="radio" id="inconsistencia" value="0" checked>  NO
				</div>
			@else
				<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 5px;">
					@if($esquela->levanta_incosistencia == 0)
						<input name="inconsistencia" type="radio" id="inconsistencia" value="1">  SI    
						<input name="inconsistencia" type="radio" id="inconsistencia" value="0" checked>  NO
					@else
						<input name="inconsistencia" type="radio" id="inconsistencia" value="1" checked>  SI    
						<input name="inconsistencia" type="radio" id="inconsistencia" value="0">  NO
					@endif
				</div>
			@endif
		</div>

		<div class="form-group">
			{!! Form::label('pago', '¿Realizó pago?:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
			@if(is_null($esquela))
				<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 5px;">
					<input name="pago" type="radio" id="pago" value="1">  SI    
					<input name="pago" type="radio" id="pago" value="0" checked>  NO
				</div>
			@else
				<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 5px;">
					@if($esquela->realiza_pago == 0)
						<input name="pago" type="radio" id="pago" value="1">  SI    
						<input name="pago" type="radio" id="pago" value="0" checked>  NO
					@else
						<input name="pago" type="radio" id="pago" value="1" checked>  SI    
						<input name="pago" type="radio" id="pago" value="0">  NO
					@endif
				</div>
			@endif
		</div>

		<div class="form-group">
			{!! Form::label('resultados', 'Detalles de resultados:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::textarea('resultados', null, array('class' => 'form-control input-xs', 'id' => 'resultados', 'rows' => '3', 'placeholder' => 'Ingrese detalles de resultados')) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('observaciones', 'Observaciones:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::textarea('observaciones', null, array('class' => 'form-control input-xs', 'id' => 'observaciones', 'rows' => '3', 'placeholder' => 'Ingrese observaciones')) !!}
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('infraccion_id', 'Infracción:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::select('infraccion_id', $cboInfraccion, null, array('class' => 'form-control input-sm', 'id' => 'infraccion_id')) !!}
			</div>
		</div>

	</div>

</div>




<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="glyphicon glyphicon-floppy-disk"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		&nbsp;
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>
</div>
{!! Form::close() !!}


<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="numero1"]').focus();
		configurarAnchoModal('1100');

		var supervisors = new Bloodhound({
			datumTokenizer: function (d) {
				return Bloodhound.tokenizers.whitespace(d.value);
			},
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: 'personal/supervisorautocompleting/%QUERY',
				filter: function (supervisors) {
					return $.map(supervisors, function (supervisor) {
						return {
							value: supervisor.value,
							id: supervisor.id
						};
					});
				}
			}
		});
		supervisors.initialize();
		$('#supervisor').typeahead(null,{
			displayKey: 'value',
			source: supervisors.ttAdapter()
		}).on('typeahead:selected', function (object, datum) {
			$('#supervisor_id').val(datum.id);
		});
		var verificadors = new Bloodhound({
			datumTokenizer: function (d) {
				return Bloodhound.tokenizers.whitespace(d.value);
			},
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: 'personal/verificadorautocompleting/%QUERY',
				filter: function (verificadors) {
					return $.map(verificadors, function (verificador) {
						return {
							value: verificador.value,
							id: verificador.id
						};
					});
				}
			}
		});
		verificadors.initialize();
		$('#verificador').typeahead(null,{
			displayKey: 'value',
			source: verificadors.ttAdapter()
		}).on('typeahead:selected', function (object, datum) {
			$('#verificador_id').val(datum.id);
		});
	}); 

	$(document).ready(function(){
		$('input').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});
	});
</script>