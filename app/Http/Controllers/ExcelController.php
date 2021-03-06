<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Input;
use Excel;
use App\Historia;
use App\Person;
use App\Servicio;
use App\Producto;
use App\Tiposervicio;
use App\Tarifario;
use App\Cie;
use App\Anaquel;
use App\Movimiento;
use App\Detallemovimiento;
use App\Lote;
use App\Kardex;
use App\Stock;
use App\HistoriaClinica;
use Illuminate\Support\Facades\DB;

class ExcelController extends Controller
{
    protected $folderview      = 'app.subirdata';
    protected $tituloAdmin     = 'Cargar Data';
    protected $tituloEliminar  = 'Eliminar Trabajador';
    protected $rutas           = array('create' => 'personal.create', 
            'edit'   => 'personal.edit', 
            'delete' => 'personal.eliminar',
            'search' => 'personal.buscar',
            'index'  => 'personal.index',
        );

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('title', 'ruta'));
    }

    public function downloadExcel($type)
    {
        $data = Item::get()->toArray();
        return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);

            });
        })->download($type);
    }

    public function importHistoriaExcel()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        if(Input::hasFile('import_file')){
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {

            })->get();
            if(!empty($data) && $data->count()){
                $dat=array();
                foreach ($data as $key => $value) {
                    $dni = trim($value->dni);
                    $nom = explode(" ",trim($value->paciente));
                    $value->apellidopaterno = $nom[0];
                    $value->apellidomaterno = $nom[1];
                    $value->nombres = substr(trim($value->paciente),strlen($nom[0]) + strlen($nom[1]) + 1 ,strlen(trim($value->paciente)));

                    $band=true;
                    if($dni!="" && strlen($dni)<=8 && strlen($dni)>=6){
                        $mdlPerson = new Person();
                        $resultado = Person::where('dni','LIKE',$dni);
                        $value2    = $resultado->first();
                        if(count($value2)>0 && strlen(trim($dni))>0){
                            $objHistoria = new Historia();
                            $Historia    = Historia::where('person_id','=',$value2->id)->where('sucursal_id','=',2)->first();
                            if(count($Historia)>0){//SI TIENE HISTORIA
                                echo "Ya tiene historia ".$value->historia." -> ".$dni;
                                $idpersona=0;
                                $dni="";
                                $band=false;
                            }else{//NO TIENE HISTORIA PERO SI ESTA REGISTRADO LA PERSONA COMO PROVEEDOR O PERSONAL
                                $idpersona=$value2->id;
                            }
                        }else{
                            $resultado = Person::where(DB::raw('concat(apellidopaterno,\' \',apellidomaterno,\' \',nombres)'), 'LIKE', '%'.strtoupper(trim($value->apellidopaterno)." ".trim($value->apellidomaterno)." ".trim($value->nombres)).'%');
                            $value2     = $resultado->first();
                            if(count($value2)>0 && strlen(trim($dni))>0){
                                $objHistoria = new Historia();
                                $Historia       = Historia::where('person_id','=',$value2->id)->where('sucursal_id','=',2)->first();
                                if(count($Historia)>0){//SI TIENE HISTORIA
                                    echo "Ya tiene historia ".$value->historia." -> ".$dni;
                                    $idpersona=0;
                                    $dni="";
                                    $band=false;
                                }else{//NO TIENE HISTORIA PERO SI ESTA REGISTRADO LA PERSONA COMO PROVEEDOR O PERSONAL
                                    $idpersona=$value2->id;
                                }
                            }else{
                                $idpersona=0;
                                $Historia=null;
                            }
                        }        
                    }else{
                        $resultado = Person::where(DB::raw('concat(apellidopaterno,\' \',apellidomaterno,\' \',nombres)'), 'LIKE', '%'.strtoupper(trim($value->apellidopaterno)." ".trim($value->apellidomaterno)." ".trim($value->nombres)).'%');
                        $value2     = $resultado->first();
                        if(count($value2)>0){
                            $objHistoria = new Historia();
                            $Historia       = Historia::where('person_id','=',$value2->id)->where('sucursal_id','=',2)->first();
                            if(count($Historia)>0){//SI TIENE HISTORIA
                                echo "Ya tiene historia ".$value->historia." -> ".$dni;
                                $idpersona=0;
                                $dni="";
                                $band=false;
                            }else{//NO TIENE HISTORIA PERO SI ESTA REGISTRADO LA PERSONA COMO PROVEEDOR O PERSONAL
                                $idpersona=$value2->id;
                            }
                        }else{
                            $idpersona=0;
                            $Historia=null;
                        }
                        $dni='';
                    }
                    $error = DB::transaction(function() use($dni,$idpersona,$value,&$dat,$band,$Historia){
                        if($band){
                            $Historia       = new Historia();
                            if($idpersona==0){
                                $person = new Person();
                                $person->dni=$dni;
                                $person->apellidopaterno=strtoupper(trim($value->apellidopaterno));
                                $person->apellidomaterno=strtoupper(trim($value->apellidomaterno));
                                $person->nombres=trim(strtoupper($value->nombres));
                                $person->telefono='-';
                                $person->direccion='-';
                                $person->sexo='M';
                                $person->telefono='-';
                                //if($value->fechanacimiento!="")    $person->fechanacimiento=$value->fechanacimiento->format("Y-m-d");
                                $person->save();
                                $idpersona=$person->id;
                            }else{
                                $person = Person::find($idpersona);
                                $person->dni=$dni;
                                $person->apellidopaterno=strtoupper(trim($value->apellidopaterno));
                                $person->apellidomaterno=strtoupper(trim($value->apellidomaterno));
                                $person->nombres=trim(strtoupper($value->nombres));
                                $person->telefono='-';
                                $person->direccion='-';
                                $person->sexo='M';
                                $person->telefono='-';
                                //if($value->fechanacimiento!="")    $person->fechanacimiento=$value->fechanacimiento->format("Y-m-d");
                                $person->save();
                                $idpersona=$person->id;
                            }
                            $Historia->numero = str_pad($value->historia + 2,8,'0',STR_PAD_LEFT);
                            $Historia->person_id = $idpersona;
                            /*if(trim($value->tipo_paciente)=="HOSPITAL"){
                                $tipopaciente="Hospital";
                            }elseif(trim($value->tipo_paciente)=="PARTICULAR"){*/
                            if($value->convenio==""){
                                $tipopaciente="Particular";
                            }else{
                                $tipopaciente="Convenio";
                            }
                            $Historia->tipopaciente=$tipopaciente;
                            $Historia->fecha="2019-02-28";
                            $Historia->modo="F";
                            $Historia->estadocivil='';
                            if($tipopaciente=="Convenio"){
                                if($value->convenio=="ELECTRORIENTE"){
                                    $Historia->convenio_id=30;
                                }elseif($value->convenio=="FAP"){
                                    $Historia->convenio_id=31;
                                }elseif($value->convenio=="FEBAN"){
                                    $Historia->convenio_id=16;
                                }elseif($value->convenio=="GMMI"){
                                    $Historia->convenio_id=28;
                                }elseif($value->convenio=="PACIFICO"){
                                    $Historia->convenio_id=17;
                                }elseif($value->convenio=="SALUDPOL"){
                                    $Historia->convenio_id=32;
                                }
                                /*$Historia->empresa=$value->empresa;
                                $Historia->carnet=$value->carnet;
                                $Historia->poliza=$value->poliza;
                                $Historia->soat=$value->soat;
                                $Historia->titular=$value->titular;*/
                            }
                            $Historia->detallecategoria='';
                            $Historia->sucursal_id=2;
                            $Historia->save();

                            $dat[]=array("respuesta"=>"OK","id"=>$Historia->id,"paciente"=>$person->apellidopaterno.' '.$person->apellidomaterno.' '.$person->nombres,"historia"=>$Historia->numero,"person_id"=>$Historia->person_id);
                        }            

                        $Historia->antecedentes = '';
                        $Historia->save();
                        /*$HistoriaClinica = new HistoriaClinica();
                        $HistoriaClinica->historia_id = $Historia->id;
                        $HistoriaClinica->fecha_atencion = "2019-10-02";
                        $HistoriaClinica->diagnostico = $value->diagnostico;
                        $HistoriaClinica->sintomas = $value->signo;
                        $HistoriaClinica->motivo = $value->fecha;
                        $HistoriaClinica->save();*/
                    });
                    if(!is_null($error)){
                        print_r($error);die();
                    }
                }
                print_r($dat);
            }
        }
        return view('importHistoria');;

    }

    public function importApellidoExcel()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        if(Input::hasFile('import_file')){
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {

            })->get();
            if(!empty($data) && $data->count()){
                $dat=array();
                foreach ($data as $key => $value) {
                    $error = DB::transaction(function() use($value,&$dat){
                        $Historia       = Historia::where('numero','like',$value->historia)->first();
                        if(count($Historia)>0){//SI TIENE HISTORIA
                            $nom=explode(" ",$value->paciente);
                            $nombres="";
                            if(isset($nom[1])){
                                $person = Person::find($Historia->person_id);
                                $person->apellidomaterno=trim(strtoupper($nom[1]));
                                $person->save();
                                $idpersona=$person->id;
                                $dat[]=array("respuesta"=>"OK","id"=>$Historia->id,"paciente"=>$person->apellidopaterno.' '.$person->apellidomaterno.' '.$person->nombres,"historia"=>$Historia->numero,"person_id"=>$Historia->person_id);
                            }else{
                                echo "No tiene apellido Nro:".$value->historia."|";
                            }
                        }else{
                            echo "No existe historia migrada Nro:".$value->historia."|";
                        }
                    });
                    if(!is_null($error)){
                        print_r($error);die();
                    }
                }
                print_r($dat);
            }
        }
        return view('importHistoria');;

    }

    public function importTarifario()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        if(Input::hasFile('import_file')){
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {

            })->get();
            if(!empty($data) && $data->count()){
                $dat=array();
                foreach ($data as $key => $value) {
                    $error = DB::transaction(function() use($value,&$dat){
                        $plan_id=12;
                        $servicio       = Servicio::join('tarifario','tarifario.id','=','servicio.tarifario_id')
                                            ->where('servicio.plan_id','=',$plan_id)
                                            ->where('servicio.tipopago','like','Convenio')
                                            ->where('tarifario.codigo','like',str_pad($value->codigo,6,'0',STR_PAD_LEFT))
                                            ->select('servicio.*')
                                            ->first();
                        if(count($servicio)>0){
                            $servicio = Servicio::find($servicio->id);
                            $servicio->precio=round($value->plan*1.18,2);
                            $servicio->factor=4.3;
                            $servicio->save();
                            $dat[]=array("respuesta"=>"ACTUALIZADO","id"=>$servicio->id,"descripcion"=>$servicio->nombre);
                        }else{
                            $tarifario = Tarifario::where('codigo','like',str_pad($value->codigo,6,'0',STR_PAD_LEFT))->first();
                            if(count($tarifario)>0){
                                $servicio = new Servicio();
                                $servicio->precio=round($value->plan*1.18,2);
                                $servicio->plan_id=$plan_id;
                                $servicio->tipopago='Convenio';
                                $servicio->pagohospital=round($value->plan*1.18,2);
                                $servicio->pagodoctor=0;
                                $servicio->modo='Monto';
                                $servicio->tarifario_id=$tarifario->id;
                                $servicio->nombre = $tarifario->nombre;
                                $servicio->factor=4.3;
                                $tipo = Servicio::join('tarifario','tarifario.id','=','servicio.tarifario_id')
                                                ->where('servicio.tipopago','like','Convenio')
                                                ->where('tarifario.codigo','like',str_pad($value->codigo,6,'0',STR_PAD_LEFT))
                                                ->first();
                                $servicio->tiposervicio_id=$tipo->tiposervicio_id;
                                $servicio->save();
                                $dat[]=array("respuesta"=>"NUEVO","id"=>$servicio->id,"descripcion"=>$servicio->nombre);
                            }else{
                                $dat[]=array("respuesta"=>"NO EXISTE","id"=>0,"descripcion"=>$value->codigo);
                            }
                        }
                    });
                    if(!is_null($error)){
                        print_r($error);die();
                    }
                }
                print_r($dat);
            }else{
                print_r("No tiene datos");
            }
        }
        return view('importHistoria');;

    }

    public function importCie()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        if(Input::hasFile('import_file')){
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {

            })->get();
            if(!empty($data) && $data->count()){
                $dat=array();
                foreach ($data as $key => $value) {
                    $error = DB::transaction(function() use($value,&$dat){
                        $Cie       = Cie::where('codigo','like',str_replace('.', '', $value->codigo))->first();
                        if(count($Cie)>0){//SI TIENE HISTORIA
                            $Cie->codigo=$value->codigo;
                            $Cie->descripcion=$value->diagnostico;
                            $Cie->save();
                            $dat[]=array("respuesta"=>"ACTUALIZADO","descripcion"=>$value->codigo);
                        }else{
                            $Cie = new Cie();
                            $Cie->codigo=$value->codigo;
                            $Cie->descripcion=$value->diagnostico;
                            $Cie->save();
                            $dat[]=array("respuesta"=>"NUEVO","descripcion"=>$value->codigo);
                        }
                    });
                    if(!is_null($error)){
                        print_r($error);die();
                    }
                }
                print_r($dat);
            }
        }
        return view('importHistoria');;

    }

    public function importServicio()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        if(Input::hasFile('import_file')){
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {

            })->get();
            if(!empty($data) && $data->count()){
                $dat=array();
                foreach ($data as $key => $value) {
                    $error = DB::transaction(function() use($value,&$dat){
                        $servicio = new Servicio();
                        $servicio->nombre = strtoupper($value->servicio);
                        $servicio->tiposervicio_id = $value->tiposervicio_id;
                        $servicio->tipopago = 'Particular';
                        $servicio->precio = str_replace(",","",$value->precio);
                        $servicio->modo = 'Monto';
                        $servicio->pagohospital = str_replace(",","",$value->precio);
                        $servicio->pagodoctor = 0;
                        $servicio->sucursal_id = 2;
                        $servicio->save();
                        $dat[]=array("respuesta"=>"NUEVO","descripcion"=>$value->descripcion);
                    });
                    if(!is_null($error)){
                        print_r($error);die();
                    }
                }
                print_r($dat);
            }
        }
        return view('importHistoria');;
    }

    public function importProducto()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        if(Input::hasFile('import_file')){
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {

            })->get();
            if(!empty($data) && $data->count()){
                $dat=array();
                
                $movimientoalmacen = new Movimiento();
                $movimientoalmacen->tipodocumento_id = 8;
                $movimientoalmacen->tipomovimiento_id  = 5;
                $movimientoalmacen->almacen_id  = 3;
                $movimientoalmacen->sucursal_id  = 2;
                $movimientoalmacen->comentario   = 'Carga inicial de inventario';
                $movimientoalmacen->numero = '00000001';
                $movimientoalmacen->fecha  = date("Y-m-d");
                $movimientoalmacen->total = 0;
                $movimientoalmacen->responsable_id = 1;
                $movimientoalmacen->save();
                
            
                foreach ($data as $key => $value) {
                    $error = DB::transaction(function() use($value,&$dat,$movimientoalmacen){
                        $producto = Producto::where('nombre','like',trim(strtoupper($value->producto)))->first();
                        if(is_null($producto)){
                            $producto       = new Producto();
                            if($value->fechavencimiento!="-"){
                                $producto->lote = 'SI';
                            }else{
                                $producto->lote = 'NO';
                            }
                            $producto->tipo = 'F';
                            
                            $producto->nombre = trim(strtoupper($value->producto));
                            $producto->codigobarra       = '';
                            $producto->afecto       = 'S';
                            $producto->codigo_producto    = '';
                            $producto->registro_sanitario = '';
                            $producto->precioxcaja    = 0;
                            $producto->preciocompra   = 0;
                            $producto->precioventa    = 0;
                            $producto->preciokayros   = 0; 
                            $producto->stockseguridad   = 0; 
                            $producto->categoria_id = null;
                            $producto->laboratorio_id = null;
                            $producto->presentacion_id = 1;
                            $producto->especialidadfarmacia_id = null;
                            $producto->proveedor_id = null;
                            $producto->origen_id = null;

                            /*$anaquel = Anaquel::where('descripcion','like',trim($value->bloque))->first();
                            if(is_null($anaquel)){
                                $anaquel = new Anaquel();
                                $anaquel->descripcion = trim($value->bloque);
                                $anaquel->save();
                            }*/
                            //$producto->anaquel_id = $anaquel->id;
                            $producto->anaquel_id = null;
                            $producto->save();
                        }

                        if(($value->stock + 0) > 0){
                            $cantidad  = $value->stock;
                            $precio    = 0;
                            $subtotal  = 0;
                            $detalleVenta = new Detallemovimiento();
                            $detalleVenta->cantidad = $cantidad;
                            $detalleVenta->precio = $precio;
                            $detalleVenta->subtotal = $subtotal;
                            $detalleVenta->movimiento_id = $movimientoalmacen->id;
                            $detalleVenta->producto_id = $producto->id;
                            $detalleVenta->save();
                            $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $producto->id)->where('movimiento.almacen_id', '=',3)->orderBy('kardex.id', 'DESC')->first();

                            $stock = Stock::where('producto_id','=',$producto->id)->where('almacen_id','=',3)->first();
                            // Creamos el lote para el producto
                            if(trim($value->fechavencimiento)!="-"){
                                $lote = new Lote();
                                $lote->nombre  = $value->lote;
                                $lote->fechavencimiento=$value->fechavencimiento->format("Y-m-d");
                                $lote->cantidad = $cantidad;
                                $lote->queda = $cantidad;
                                $lote->producto_id = $producto->id;
                                $lote->almacen_id = 3;
                                $lote->save();

                                $detalleVenta->lote_id = $lote->id;
                                $detalleVenta->save();
                            }
                    
                            $stockanterior = 0;
                            $stockactual = 0;

                            if ($ultimokardex === NULL) {
                                $stockactual = $cantidad;
                                $kardex = new Kardex();
                                $kardex->tipo = 'I';
                                $kardex->fecha = date("Y-m-d");
                                $kardex->stockanterior = $stockanterior;
                                $kardex->stockactual = $stockactual;
                                $kardex->cantidad = $cantidad;
                                $kardex->preciocompra = $precio;
                                $kardex->almacen_id = 3;
                                $kardex->detallemovimiento_id = $detalleVenta->id;
                                if($value->fechavencimiento!="-"){
                                    $kardex->lote_id = $lote->id;
                                }
                                $kardex->save();
                            }else{
                                $stockanterior = $ultimokardex->stockactual;
                                $stockactual = $ultimokardex->stockactual+$cantidad;
                                $kardex = new Kardex();
                                $kardex->tipo = 'I';
                                $kardex->fecha = date('Y-m-d');
                                $kardex->stockanterior = $stockanterior;
                                $kardex->stockactual = $stockactual;
                                $kardex->cantidad = $cantidad;
                                $kardex->preciocompra = $precio;
                                $kardex->almacen_id = 3;
                                $kardex->detallemovimiento_id = $detalleVenta->id;
                                if($value->fechavencimiento!="-"){
                                    $kardex->lote_id = $lote->id;
                                }
                                $kardex->save();    

                            }   

                            if(is_null($stock)){
                                $stock = new Stock();
                                $stock->producto_id = $producto->id;
                                $stock->cantidad = $stockactual;
                                $stock->almacen_id = 3;
                                $stock->save();
                            }else{
                                $stock->cantidad = $stock->cantidad + $cantidad;
                                $stock->save();
                            }
                        }            

                        $dat[]=array("respuesta"=>"NUEVO","descripcion"=>$value->descripcion);
                    });
                    if(!is_null($error)){
                        print_r($error);die();
                    }
                }
                print_r($dat);
            }
        }
        return view('importHistoria');;

    }

    public function historiasConvenio(){

        $nombre           = "(SALUDPOL)";

        $resultado        = Historia::join('person', 'person.id', '=', 'historia.person_id')
       // ->where('historia.sucursal_id', '=', 1)
        ->where(DB::raw('concat(apellidopaterno,\' \',apellidomaterno,\' \',nombres)'), 'LIKE', '%'.strtoupper($nombre).'%');

        $resultado        = $resultado->select('historia.*')->orderBy('historia.numero', 'ASC');
        
        $lista            = $resultado->get();

        $error = null;

        foreach ($lista as $key => $value) {
            $error = DB::transaction(function() use($nombre, $value){
                $persona = Person::find($value->person_id);
                $sinSaludpol = str_replace($nombre, "", $persona->nombres);     //nombres //apellido materno // apellido paterno
                $persona->nombres = $sinSaludpol;
                $persona->save();

                $historia = Historia::find($value->id);
                $historia->tipopaciente = "Convenio";
                $historia->convenio_id = 32; //SALUDPOL = 32  // FEBAN = 16 // FAP = 31 // PACIFICO = 11
                $historia->save();
            });
        }

        return is_null($error) ? count($lista) : $error;

    }
}

/* 
    (SALUDPOL)
    (SALUDPOLL)
    /SALUDPOLL
    (/SALUDPOL)
    /SALUDPOL
    (SALUD POL)
    /SALUD POL
    / SALUDPOL
    SALUDPOL
    /SALUD POL
    (SALUD POL)
    (SALUDPOD)

    (FEBAN)
    /FEBAN

    (FAP)

    (PACIFICO)
    ( PACIFICO)
    / PACIFICO
*/

