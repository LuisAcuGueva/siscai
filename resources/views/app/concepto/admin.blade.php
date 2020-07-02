<div class="row">
	<div class="col-lg-1 col-md-1"></div>
	<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="margin-bottom: 45px;">
		<div class="x_panel">
			<div class="x_title">
				<h2><i class="fa fa-gears"></i> {{ $title }}</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
				{!! Form::hidden('page', 1, array('id' => 'page')) !!}
				{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
				<div class="form-group">
					{!! Form::label('concepto', 'Concepto:') !!}
					{!! Form::text('concepto', '', array('class' => 'form-control input-sm', 'id' => 'concepto')) !!}
				</div>
				<div class="form-group">
						{!! Form::label('tipo', 'Tipo:') !!}
						<select id="tipob" name="tipo" class="form-control input-xs">
							<option value="">TODOS</option>
							<option value="0">INGRESO</option>
							<option value="1">EGRESO</option>
						</select>
					</div>
				<div class="form-group">
					{!! Form::label('filas', 'Filas a mostrar:')!!}
					{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
				</div>
				{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnBuscar', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
				{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Nuevo', array('class' => 'btn btn-primary waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
				{!! Form::close() !!}
				
				<div id="listado{{ $entidad }}"></div>

			</div>
		</div>
	</div>
	<div class="col-lg-1 col-md-1"></div>
</div>

<script>
	$(document).ready(function () {
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="concepto"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
		$("#tipob").change(function () {
			buscar('{{ $entidad }}');
		});
	});
</script>