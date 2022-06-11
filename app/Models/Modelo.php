<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares','air_bag', 'abs'];

    public function rules()
    {
        return
            [
                'marca_id' => 'exists:marcas,id',
                'nome' => 'required|unique:marcas,nome,' . $this->id . '|min:3',
                'imagem' => 'required|file|mines:png,docx,xlsx,pdf,ppt,jpeg,mp3',
                'numero_portas' => 'required|interger|digits_between:1,5',
                'lugares' => 'required|interger|digits_between:1,5',
                'air_bag' => 'required|boolean',
                'abs' => 'required|boolean'
            ];
    }

    public function marca(){
        // Um modelo pertence a uma marca
        return $this->belongsTo(Marca::class);
    }
}
