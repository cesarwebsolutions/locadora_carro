<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $marcas = Marca::all();
        $marcas = $this->marca->with('modelos')->get();
        return $marcas;
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


        $request->validate($this->marca->rules(), $this->marca->feedback());
        // $marca = Marca::create($request->all());
        // dd($request->nome);
        // dd($request->get('nome'));
        // dd($request->input('nome'));

        // dd($request->imagem);
        // dd($request->file('imagem'));
        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens/x/y/z', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        // ou
        // $marca->nome = $request->nome;
        // $marca->imagem = $imagem_urn;
        // $marca->save();

        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return response()->json($marca, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        // $marca->update($request->all());
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Não foi possivel realizar o ajuste'], 404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            // percorrendo todas as regras definidas no Model
            foreach($marca->rules() as $input => $regra){
                //coletar apenas as regras aplicaveis aos parâmetros da requisição PATCH
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }
        $marca->fill($request->all());
        // remove o arquivo antigo caso um novo tenha sido enviado na request
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
            $image = $request->file('imagem');
            // dd($request);
            $imagem_urn = $image->store('imagens', 'public');
            $marca->imagem = $imagem_urn;
        }
        $marca->save();
        // $marca->update([
        //     'nome' => $request->nome,
        //     'imagem' => $imagem_urn
        // ]);
        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // $marca->delete();
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Não foi possível realizar a exclisão'], 404);
        }

        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}
