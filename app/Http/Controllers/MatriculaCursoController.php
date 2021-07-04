<?php

namespace App\Http\Controllers;

use App\Detalle_matricula_curso;
use App\Matricula_curso;
use Illuminate\Http\Request;
use Auth;
use DB;

class MatriculaCursoController extends Controller
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

                    $role_user = Auth::user()->id_role;

                    $id_user = Auth::user()->id;

                    if($role_user == 1){
                        
                        $matriculas = DB::table('matricula_cursos as M')
                            ->join('detalle_matricula_cursos as D', 'D.id_matricula', '=', 'M.id')
                            ->join('usuarios as U', 'D.id_usuario', '=', 'U.id')
                            ->join('cursos as C', 'D.id_curso', '=', 'C.id')
                            ->select('M.*', 'U.nombre_completo as nombre_alumno', 'C.nombre as nombre_curso', 'C.intensidad_horaria as intensidad')
                            ->where('M.id_estado','=',1)
                            ->orderBy('M.id','DESC')
                            ->get();

                    }else{

                        
                        $matriculas = DB::table('matricula_cursos as M')
                            ->join('detalle_matricula_cursos as D', 'D.id_matricula', '=', 'M.id')
                            ->join('usuarios as U', 'D.id_usuario', '=', 'U.id')
                            ->join('cursos as C', 'D.id_curso', '=', 'C.id')
                            ->select('M.*', 'U.nombre_completo as nombre_alumno', 'C.nombre as nombre_curso', 'C.intensidad_horaria as intensidad')
                            ->where('D.id_usuario','=',$id_user)
                            ->where('M.id_estado','=',1)
                            ->orderBy('M.id','DESC')
                            ->get();
                    }


            }catch(QueryException $queryException){

                return $queryException->getMessage();
            }

                    return $matriculas;


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
                'arrayNewMatricula' => 'required',
                
            ]);     

                    $now = new \DateTime();

                    $date = $now->format('Y.m.d');


                    $matricula = new Matricula_curso();

                    $matricula->fecha = $date;
                    $matricula->id_estado = 1;

                    $matricula->save();

                $arrayNewMatricula = $request->arrayNewMatricula;

                foreach ($arrayNewMatricula as $value)
                {
                    $detalle_matricula = new Detalle_matricula_curso();

                    $detalle_matricula->id_matricula = $matricula->id;
                    $detalle_matricula->id_usuario = $request->id_alumno;
                    $detalle_matricula->id_curso = $value['id_curso'];

                    $detalle_matricula->save();
                        
                }   
            
    
        
                return response()->json([
                    'status' => 'Operacion concretada!',
                    'msg' => 'matricula creada satisfactoriamente',
                    'code' => 1
                ],201);
        
    

        }else{
            return redirect('/');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Matricula_curso  $matricula_curso
     * @return \Illuminate\Http\Response
     */
    public function show(Matricula_curso $matricula_curso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Matricula_curso  $matricula_curso
     * @return \Illuminate\Http\Response
     */
    public function edit(Matricula_curso $matricula_curso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Matricula_curso  $matricula_curso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Matricula_curso $matricula_curso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Matricula_curso  $matricula_curso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->wantsJson()){

            $matricula = Matricula_curso::find($id);
            $matricula->id_estado = 2;

            $matricula->save();

        }else{
            return redirect('/');
        }
    }
}
