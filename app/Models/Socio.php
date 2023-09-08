<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Socio extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    public function banco()
    {
        return $this->hasOne(Banco::class, 'id', 'banco_id');
    }

    public function creador()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getEstadoAttribute($value)
    {
        if($value == 1){
            $valor = ['id' => $value, 'value' => 'Activo', 'color' => 'success' ];
        }
        if($value == 2){
            $valor = ['id' => $value, 'value' => 'Inactivo', 'color' => 'danger' ];
        }
        if($value == 3){
            $valor = ['id' => $value, 'value' => 'Bloqueado', 'color' => 'warning' ];
        }
        if($value == 4){
            $valor = ['id' => $value, 'value' => 'Fallecido', 'color' => 'danger' ];
        }
        if($value == 5){
            $valor = ['id' => $value, 'value' => 'Cuenta cancelada', 'color' => 'danger' ];
        }
        if($value == 6){
            $valor = ['id' => $value, 'value' => 'Cuenta inactiva', 'color' => 'danger' ];
        }
        if($value == 7){
            $valor = ['id' => $value, 'value' => 'Cuenta no corresponde', 'color' => 'danger' ];
        }
        if($value == 8){
            $valor = ['id' => $value, 'value' => 'Expuldao', 'color' => 'danger' ];
        }
        if($value == 9){
            $valor = ['id' => $value, 'value' => 'Suspendido', 'color' => 'danger' ];
        }

        return $this->attributes['estado_data'] = $valor;
    }

    public function getPagoAttribute($value)
    {
        if($value == 1){
            $valor = ['id' => $value, 'value' => 'Cancelado', 'color' => 'success' ];
        }
        elseif($value == 2){
            $valor = ['id' => $value, 'value' => 'Pendiente', 'color' => 'danger' ];
        }
        else{
            $valor = null;
        }

        return $this->attributes['pago_data'] = $valor;
    }

    public function getFuerzaAttribute($value)
    {
        if($value == 1){
            $valor = ['id' => $value, 'value' => 'Ejército'];
        }
        if($value == 2){
            $valor = ['id' => $value, 'value' => 'Aerea'];
        }
        if($value == 3){
            $valor = ['id' => $value, 'value' => 'Naval'];
        }
        if($value == 4){
            $valor = ['id' => $value, 'value' => 'Civil'];
        }

        return $this->attributes['pago_data'] = $valor;
    }

    public function getDocumentoPdfAttribute($value)
    {
        if(!is_array($value))
        {
            $valor = ['url' => '/documentos_imagenes/'.$value, 'valor' => $value];

        return $this->attributes['documento_pdf'] = $valor;
        }

    }

    public function getFichaPdfAttribute($value)
    {
        if(!is_array($value))
        {
            $valor = ['url' => '/fichas_socios/'.$value, 'valor' => $value];

        return $this->attributes['ficha_pdf'] = $valor;
        }
    }

    public function getMiesAttribute($value)
    {
        if($value == 1){ return $this->attributes['mies'] = true; }
        else{ return $this->attributes['mies'] = false; }
    }

    public function getMtAttribute($value)
    {
        if($value == 1){ return $this->attributes['mt'] = true; }
        else{ return $this->attributes['mt'] = false; }
    }
}
