<?php

namespace App\Http\Controllers;

use App\Tipo_documento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->wantsJson()){

            $documentos = Tipo_documento::select('id', 'nombre')
            ->orderBy('id', 'asc')->get();

            return ['documentos' => $documentos];
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tipo_documento  $tipo_documento
     * @return \Illuminate\Http\Response
     */
    public function show(Tipo_documento $tipo_documento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tipo_documento  $tipo_documento
     * @return \Illuminate\Http\Response
     */
    public function edit(Tipo_documento $tipo_documento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tipo_documento  $tipo_documento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipo_documento $tipo_documento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tipo_documento  $tipo_documento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipo_documento $tipo_documento)
    {
        //
    }
}
