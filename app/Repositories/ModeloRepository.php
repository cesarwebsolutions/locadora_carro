<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class ModeloRepository extends AbstractRepository {

    // public function __construct(Model $model)
    // {
    //     $this->model = $model;
    // }


    // public function selectAtributosRegistrosRelacionados($atributos){
    //     $this->model = $this->model->with($atributos);
    // }

    // public function filtro($filtros){
    //     $filtros = explode(';', $filtros);
    //     foreach ($filtros as  $key => $condicao) {
    //         $condicaoRecuperada = explode(':', $condicao);
    //         $this->model = $this->model->where($condicaoRecuperada[0], $condicaoRecuperada[1], $condicaoRecuperada[2]);
    //     }
    // }

    // public function selectAtributos($atributos){
    //     $this->model  = $this->model->selectRaw($atributos);
    // }

    // public function getResultado(){
    //     return $this->model->get();
    // }
}
