<?php
$hoy = date("Y-m-d");
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Menuoption;
use App\OperacionMenu;
use App\Detallepagos;
?>

@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<div class="table-responsive">
<table id="example1" class="table table-bordered table-hover" style="font-size: 13px;">
	<thead>
		<tr class="success" style="height: 35px;">
			@foreach($cabecera as $key => $value)
				<th @if((int)$value["numero"] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<?php
			$pagos = Detallepagos::where('pedido_id', $value->id)
			->leftjoin('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')                       
			->where('movimiento.estado', 1)
			->get();
		?>
		@if($value->estado == 1)
			<tr style ="background-color: #ffffff !important">
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["detalle"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
			@if(date("Y-m-d",strtotime($value->fecha)) == $hoy )
				@if( count( $pagos) >= 2)
					<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloEliminar.'\', this);', 'disabled', 'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-remove')) !!}</td>
				@else
					<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloEliminar.'\', this);', 'class' => 'btn btn-sm btn-danger glyphicon glyphicon-remove')) !!}</td>
				@endif
			@else
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloEliminar.'\', this);', 'disabled', 'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-remove')) !!}</td>
			@endif
		@elseif($value->estado == 0)
			<tr style ="background-color: #ffc8cb !important">
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["detalle"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloEliminar.'\', this);', 'disabled', 'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-remove')) !!}</td>
		@endif
			<td align="center">{{ $fechaformato = date("d/m/Y h:i:s a",strtotime($value->fecha))}}</td>	
			<td>{{ $value->persona->razon_social }}</td>
			<td>{{ $value->tipodocumento->abreviatura . '' .$value->num_compra }}</td>
			<!--td>{{ $value->trabajador->nombres .' '. $value->trabajador->apellido_pat .' '. $value->trabajador->apellido_mat}}</td-->
			@if($value->estado == 1)
				@if (!is_null($value->comentario))
					<td> {{ $value->comentario }} </td>
				@else
					<td align="center"> - </td>
				@endif
			@elseif($value->estado == 0)
				<td> {{ $value->comentario }} | Anulado por: {{ $value->comentario_anulado }} </td>
			@endif

			@if($value->balon_a_cuenta == 1)
				<td align="center"> SI </td>
			@else
				<td align="center"> NO </td>
			@endif
			<td align="center">{{ $value->total }}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif
<script>
	$(document).ready(function () {
		if($(".btnEditar").attr('activo')=== 'no'){
			$('.btnEditar').attr("disabled", true);
		}
		if($(".btnEliminar").attr('activo')=== 'no'){
			$('.btnEliminar').attr("disabled", true);
		}
	});
</script>
