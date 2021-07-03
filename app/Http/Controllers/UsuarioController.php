<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use App\FileUpload;
use Intervention\Image\Facades\Image as Image;
use App\Aeconomica;
use App\CDian;
use App\Tcontribuyente;
use App\Tpersona;
use App\zona;
use App\Tercero;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if($request->wantsJson()){


            try{

                    $usuarios = DB::table('usuarios as U')
                    ->join('tipo_documentos as T', 'U.id_tipo_documento', '=', 'T.id')
                    ->join('estados as E', 'U.id_estado', '=', 'E.id')
                    ->join('roles as R', 'U.id_role', '=', 'R.id')
                    ->select('U.*', 'T.nombre as tipo_documento', 'E.nombre as nombre_estado', 'R.nombre as nombre_role')
                    ->where('U.id_estado','=',1)
                    //->where('U.id_role','!=',3)
                    ->orderBy('U.id','DESC')
                    ->get();

            }catch(QueryException $queryException){

                return $queryException->getMessage();
            }

            return $usuarios;


        }else{
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if($request->wantsJson()){

            $this->validate($request, [
                'id_tipo_documento' => 'required',
                'numero_documento' => 'required',
                'nombre_completo' => 'required',
                'telefono' => 'required',
                'direccion' => 'required',
            ]);

            if($request->get('foto'))
            {
               $foto = $request->get('foto');
               $name = time().'.' . explode('/', explode(':', substr($foto, 0, strpos($foto, ';')))[1])[1];
               Image::make($request->get('foto'))->save(public_path('fotos/').$name);


             }

        $usuario = new Usuario();

        $validar_documento = Usuario::where('numero_documento', '=', $request->numero_documento)->first();

        $validar_correo = Usuario::where('email', '=', $request->email)->first();


        /*if (  $validar_correo !== null ) {

            return response()->json([
                'status' => 'Ocurrio un error!',
                'msg' => 'El correo ya se encuentra registrado',
                'code' => 1
            ],400);
        }*/


        if ( ($request->id_role == 2)  && (!empty($validar_documento) && $validar_documento->id_role == 2) ) {

            return response()->json([
                'status' => 'Ocurrio un error!',
                'msg' => 'El tercero se encuentra registrado',
                'code' => 1
            ],400);
        }


        if ( ($request->id_role != 2)  && (!empty($validar_documento) && $validar_documento->id_role != 2) ) {

            return response()->json([
                'status' => 'Ocurrio un error!',
                'msg' => 'El usuario se encuentra registrado',
                'code' => 1
            ],400);
        }



            if ($request->id_role == 2){
                //guardar en wispro
                // set post fields
                $post = [
                    'name' => $request->nombre_completo,
                    'email' => $request->email,
                    'phone_mobile' => $request->telefono,
                    'national_identification_number' => $request->numero_documento,
                    'address' => $request->direccion
                    
                ];

                $ch = curl_init('https://www.cloud.wispro.co/api/v1/clients/');
                $headers = array(
                    'Authorization: 98035524-6f86-4b89-abe4-92d05b25b631'
                    );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

                // execute!
                $response = curl_exec($ch);
                curl_close($ch);
                $data = json_decode($response);
                $datos = $data->data;
                $id_wispro = $datos->id;
                //si ok guardar en db 
                if ($id_wispro != ""){

                    $usuario->id_tipo_documento = $request->id_tipo_documento;
                    $usuario->numero_documento = $request->numero_documento;
                    $usuario->nombre_completo = $request->nombre_completo;
                    $usuario->telefono = $request->telefono;
                    $usuario->direccion = $request->direccion;
                    $usuario->id_estado = $request->id_estado;
                    $usuario->id_role = $request->id_role;
                    $usuario->email = $request->email;
                    $usuario->password = bcrypt($request->password);
                    $usuario->foto = 'cliente.svg';

                    $usuario->save();
    
    
                    //guardar tabla tercero
                    $tercero = new Tercero();
                    $tercero->id_usuario = $usuario->id;
                    $tercero->id_wispro = $id_wispro;
                    $tercero->fecha_nacimiento = date("Y/m/d", strtotime($request->fecha_nacimiento));
                    $tercero->segundo_apellido = $request->segundo_apellido;
                    $tercero->segundo_nombre = $request->segundo_nombre;
                    $tercero->id_zona = $request->newZone;
                    $tercero->id_tipo_persona = $request->newtpersona;
                    $tercero->id_tipo_contribuyente = $request->newtcontribuyente;
                    $tercero->id_clasificacion_dian = $request->newClasificacion;
                    $tercero->id_actividad_economica = $request->newAeconomica;
                    $tercero->codigo_postal = $request->cpostal;
                    $tercero->barrio = $request->barrio;
                    $tercero->contacto = $request->contacto;
                    $tercero->numero_contacto = $request->ncontacto;
                    $tercero->save();
                    //
               
                    return response()->json([
                        'status' => 'Operacion concretada!',
                        'msg' => 'Usuario creado satisfactoriamente',
                        'code' => 2
                    ],201);
                }
                else{
                    //error

                    return response()->json([
                        'status' => 'Ocurrio un error!',
                        'msg' => 'Ocurrio un error al crear el Usuario',
                        'code' => 3
                    ],400);
                }
                            

                 

            }else{
          
            $usuario->id_tipo_documento = $request->id_tipo_documento;
            $usuario->numero_documento = $request->numero_documento;
            $usuario->nombre_completo = $request->nombre_completo;
            $usuario->telefono = $request->telefono;
            $usuario->direccion = $request->direccion;
            $usuario->id_estado = $request->id_estado;
            $usuario->id_role = $request->id_role;
            $usuario->email = $request->email;
            $usuario->password = bcrypt($request->password);

            if(($request->foto == null) && ($request->id_role == 1) ){
                $usuario->foto = 'admin.svg';
            
            }else if(($request->foto == null) && ($request->id_role == 2) ){
                    $usuario->foto = 'cliente.svg';
            
            }else if(($request->foto == null) && ($request->id_role == 3) ){
                $usuario->foto = 'contabilidad.svg';
            
            }else if(($request->foto == null) && ($request->id_role == 4) ){
                $usuario->foto = 'call-center.svg';

            }else if(($request->foto == null) && ($request->id_role == 5) ){
                $usuario->foto = 'inventario.svg';
                 
            }else if(($request->foto == null) && ($request->id_role == 6) ){
                $usuario->foto = 'tecnico.svg';

             
            }else{
                $usuario->foto = $name;
            }
            $usuario->save();

        
            return response()->json([
                'status' => 'Operacion concretada!',
                'msg' => 'Usuario creado satisfactoriamente',
                'code' => 2
            ],201);
        }
        


        }else{
            return redirect('/');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->wantsJson()){

            $usuarios = Usuario::findOrFail($id);

            return $usuarios;

        }else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->wantsJson()){

            $this->validate($request, [
                'id_tipo_documento' => 'required',
                'numero_documento' => 'required',
                'nombre_completo' => 'required',
                'telefono' => 'required',
                'direccion' => 'required',
            ]);


                $usuario = Usuario::find($id);

                $usuario->id_tipo_documento = $request->id_tipo_documento;
                $usuario->numero_documento = $request->numero_documento;
                $usuario->nombre_completo = $request->nombre_completo;
                $usuario->telefono = $request->telefono;
                $usuario->direccion = $request->direccion;
                $usuario->id_role = $request->id_role;
                $usuario->email= $request->email;

                if($request->foto == !null){

                    $foto = $request->get('foto');
                        $name = time().'.' . explode('/', explode(':', substr($foto, 0, strpos($foto, ';')))[1])[1];
                        Image::make($request->get('foto'))->save(public_path('fotos/').$name);

                        $usuario->foto = $name;
                    }

                if (empty($request->password)) {
                    $usuario->password = $usuario->password;
                }else{
                    $usuario->password = bcrypt($request->password);
                }


                $usuario->save();

                return;

            }else{
                return redirect('/');
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        if($request->wantsJson()){

            $usuario = Usuario::find($id);
            $usuario->id_estado = 2;

            $usuario->save();

        }else{
            return redirect('/');
        }
    }


    public function getUsuario(Request $request)
    {
        if($request->wantsJson()){

            $usuarios = DB::table('usuarios as U')
            ->where('U.id_estado', 1)
            ->where('U.id_role','!=' ,2)
            ->select('U.*')
            ->get();

            return $usuarios;

        }else{
            return redirect('/');
        }
    }

    public function getTecnicos(Request $request)
    {
        if($request->wantsJson()){

            $tecnicos = DB::table('usuarios as U')
            ->where('U.id_estado', 1)
            ->where('U.id_role', 6)
            ->select('U.*')
            ->get();

            return $tecnicos;

        }else{
            return redirect('/');
        }
    }

    //GESTION DE USUARIOS
//AEconomica
    public function createAEconomicas(Request $request)
    {
        if($request->wantsJson()){

           


                $aEconomica = new Aeconomica();

                $aEconomica->nombre = $request->nombre;
                $aEconomica->descripcion = $request->descripcion;
               
                $aEconomica->save();

                return;

            }else{
                return redirect('/');
            }
    }

    public function getAEconomicas(Request $request)
    {
        if($request->wantsJson()){

            $ae = DB::table('actividad_economicas as ae')
           
            ->select('ae.*')
            ->get();

            return $ae;

            }else{
                return redirect('/');
            }
    }
    public function updateActividad(Request $request)
    {
       
        if($request->wantsJson()){

            $aeconomica = Aeconomica::find($request->id);
           
            $aeconomica->nombre = $request->nombre;
            $aeconomica->descripcion = $request->descripcion;

            $aeconomica->save();
             
            }else{
                return redirect('/');
            }
    }
    public function deleteActividad(Request $request)
    {
       
        if($request->wantsJson()){

            $aeconomica = Aeconomica::find($request->id);
            $aeconomica->delete();
             
            }else{
                return redirect('/');
            }
    }

    //CDian
    public function createClasificaciones(Request $request)
    {
        if($request->wantsJson()){

                $cDian = new CDian();

                $cDian->nombre = $request->nombre;
                $cDian->descripcion = $request->descripcion;
               
                $cDian->save();

                return;

            }else{
                return redirect('/');
            }
    }
    public function getClasificaciones(Request $request)
    {
        if($request->wantsJson()){

            $cd = DB::table('clasificacion_dians as cd')
           
            ->select('cd.*')
            ->get();

            return $cd;

            }else{
                return redirect('/');
            }
    }
    public function editClasificaciones(Request $request)
    {
       
        if($request->wantsJson()){

            $cDian = CDian::find($request->id);
           
            $cDian->nombre = $request->nombre;
            $cDian->descripcion = $request->descripcion;

            $cDian->save();
             
            }else{
                return redirect('/');
            }
    }
    public function deleteClasificacion(Request $request)
    {
       
        if($request->wantsJson()){

            $cDian = CDian::find($request->id);
            $cDian->delete();
             
            }else{
                return redirect('/');
            }
    }

     //Tcontribuyente
     public function createTcontribuyente(Request $request)
     {
         if($request->wantsJson()){
 
                 $tcontribuyente = new Tcontribuyente();
 
                 $tcontribuyente->nombre = $request->nombre;
                 $tcontribuyente->descripcion = $request->descripcion;
                
                 $tcontribuyente->save();
 
                 return;
 
             }else{
                 return redirect('/');
             }
     }
     public function getTcontribuyente(Request $request)
     {
         if($request->wantsJson()){
 
             $tc = DB::table('tipo_contribuyentes as tc')
            
             ->select('tc.*')
             ->get();
 
             return $tc;
 
             }else{
                 return redirect('/');
             }
     }
     public function editTcontribuyente(Request $request)
     {
        
         if($request->wantsJson()){
 
             $tcontribuyente = Tcontribuyente::find($request->id);
            
             $tcontribuyente->nombre = $request->nombre;
             $tcontribuyente->descripcion = $request->descripcion;
 
             $tcontribuyente->save();
              
             }else{
                 return redirect('/');
             }
     }
     public function deleteTcontribuyente(Request $request)
     {
         
         if($request->wantsJson()){
 
             $tcontribuyente = Tcontribuyente::find($request->id);
             $tcontribuyente->delete();
              
             }else{
                 return redirect('/');
             }
     }

     //Tpersona
     public function createTpersona(Request $request)
     {
         if($request->wantsJson()){
 
                 $tpersona = new Tpersona();
 
                 $tpersona->nombre = $request->nombre;
                 $tpersona->descripcion = $request->descripcion;
                
                 $tpersona->save();
 
                 return;
 
             }else{
                 return redirect('/');
             }
     }
     public function getTpersona(Request $request)
     {
         if($request->wantsJson()){
 
             $ae = DB::table('tipo_personas as tc')
            
             ->select('tc.*')
             ->get();
 
             return $ae;
 
             }else{
                 return redirect('/');
             }
     }
     public function editTpersona(Request $request)
     {
        
         if($request->wantsJson()){
 
             $tpersona = Tpersona::find($request->id);
            
             $tpersona->nombre = $request->nombre;
             $tpersona->descripcion = $request->descripcion;
 
             $tpersona->save();
              
             }else{
                 return redirect('/');
             }
     }
     public function deleteTpersona(Request $request)
     {
         
         if($request->wantsJson()){
 
             $tpersona = Tpersona::find($request->id);
             $tpersona->delete();
              
             }else{
                 return redirect('/');
             }
     }

      //zona
      public function createzona(Request $request)
      {
          if($request->wantsJson()){
  
                  $zona = new zona();
  
                  $zona->nombre = $request->nombre;
                  $zona->descripcion = $request->descripcion;
                 
                  $zona->save();
  
                  return;
  
              }else{
                  return redirect('/');
              }
      }
      public function getzona(Request $request)
      {
          if($request->wantsJson()){
  
              $ae = DB::table('zonas as z')
             
              ->select('z.*')
              ->get();
  
              return $ae;
  
              }else{
                  return redirect('/');
              }
      }
      public function editzona(Request $request)
      {
         
          if($request->wantsJson()){
  
              $zona = zona::find($request->id);
             
              $zona->nombre = $request->nombre;
              $zona->descripcion = $request->descripcion;
  
              $zona->save();
               
              }else{
                  return redirect('/');
              }
      }
      public function deletezona(Request $request)
      {
          
          if($request->wantsJson()){
  
              $zona = zona::find($request->id);
              $zona->delete();
               
              }else{
                  return redirect('/');
              }
      }


      // WISPRO

      //SINC ALL USERS
      public function sinc_users_wispro(Request $request){

                $ch = curl_init('https://www.cloud.wispro.co/api/v1/clients?page=1&per_page=100');

                $headers = array(
                'Authorization: 98035524-6f86-4b89-abe4-92d05b25b631'
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                $response = curl_exec($ch);
           
                curl_close($ch);
                if (!$response) {
               //error
                } else {
             
                    $data = json_decode($response);

                    $status = $data->status;
                    $total_pages = $data->meta->pagination->total_pages;
                    $datos = $data->data;
                  
                  
                    foreach ($datos as $user) {
                         
                           // dd($user->name); 
                            $this->guardarUsuarioSinc($user);
                           
                } 

                for ($i = 2; $i <= $total_pages; $i++) {
                        $ch = curl_init('https://www.cloud.wispro.co/api/v1/clients?page='.$i.'&per_page=100');

                        $headers = array(
                        'Authorization: 98035524-6f86-4b89-abe4-92d05b25b631'
                        );
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                        $response = curl_exec($ch);
                    
                        curl_close($ch);
                        if (!$response) {
                    //error
                        } else {
                    
                            $data = json_decode($response);

                            $datos = $data->data;
                        
                            foreach ($datos as $user) {
                              
                                    $this->guardarUsuarioSinc($user);
                                  
                        } 
                }

                       
            }

                }
        }


        public function guardarUsuarioSinc($dataUser){

         
             $numero_documento = $dataUser->national_identification_number;
             $id_wispro = $dataUser->id;

             if ($numero_documento > 0){
                        $usuarios = DB::table('usuarios as U')
                        ->select('U.*')
                        ->where('U.numero_documento','=',$numero_documento)
                        ->get();

                        if (count($usuarios) > 0){
                            
                            echo($usuarios);
                            echo "<br>";
                        }
                       else{
                           
                           $usuario = new Usuario();
                           $usuario->id_tipo_documento = 1;
                           $usuario->numero_documento = $numero_documento;
                           $usuario->nombre_completo = $dataUser->name;
                           $usuario->telefono = $dataUser->phone_mobile;
                           $usuario->direccion = $dataUser->address;
                           $usuario->id_estado = 1;
                           $usuario->id_role = 2;
                           $usuario->email = $dataUser->email;
                           $usuario->password = bcrypt($numero_documento);
                           $usuario->foto = 'cliente.svg';
                           $usuario->save();

                           $tercero = new Tercero();
                           $tercero->id_usuario = $usuario->id;
                           $tercero->id_wispro = $id_wispro;
                           $tercero->save();
                       }

                 }
                          

        }




    }
