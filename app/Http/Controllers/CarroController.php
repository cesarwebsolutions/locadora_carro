<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // $marcas = Marca::all();
        $carroRepository = new CarroRepository($this->carro);

        if ($request->has('atributos_modelo')) {
            $atributos_modelo = 'modelo:id,' . $request->atributos_modelo;

            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        } else {
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        if ($request->has('filtro')) {
            $carroRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $carroRepository->selectAtributos($request->atributos);
        }

        /**
         * sem repository
         */
        // $marcas = array();

        // if($request->has('atributos_modelos')){
        //     $atributos_modelos = $request->atributos_modelos;
        //     $marcas = $this->marca->with('modelos:id,'.$atributos_modelos);
        // } else {
        //     $marcas = $this->marca->with('modelos');
        // }

        // if($request->has('filtro')){
        //     $filtros = explode(';', $request->filtro);
        //     foreach($filtros as  $key => $condicao){
        //         $condicaoRecuperada = explode(':', $condicao);
        //         $marcas = $marcas->where($condicaoRecuperada[0], $condicaoRecuperada[1], $condicaoRecuperada[2]);
        //     }
        // }

        // if($request->has('atributos')){
        //     $atributos = $request->atributos;
        //     $marcas = $marcas->selectRaw($atributos)->get();
        // } else {
        //     $marcas = $marcas->get();
        // }

        // $marcas = $this->marca->with('modelos')->get();
        return response()->json($carroRepository->getResultado(), 200);
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
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //


        $request->validate($this->carro->rules());


        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km,
        ]);


        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $carro = $this->carro->with('modelo')->find($id);
        if ($carro === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return response()->json($carro, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function edit(Carro $carro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        // $marca->update($request->all());
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(['erro' => 'Não foi possivel realizar o ajuste'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            // percorrendo todas as regras definidas no Model
            foreach ($carro->rules() as $input => $regra) {
                //coletar apenas as regras aplicaveis aos parâmetros da requisição PATCH
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($carro->rules());
        }
        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(['erro' => 'Não foi possível realizar a exclisão'], 404);
        }
        $carro->delete();
        return response()->json(['msg' => 'A carro foi removida com sucesso!'], 200);
    }
}
