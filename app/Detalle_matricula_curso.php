<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_matricula_curso extends Model
{
    /**
     * Tabla asociada con el modelo
     * @var string
     */
    protected $table = "detalle_matricula_cursos";

    /**
     * @var array
     */
    protected $fillable = [
        'id_matricula','id_usuario', 'id_curso'
    ];

    /**
     * @var boolean
     */
    public $timestamps = false;

}
