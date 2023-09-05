<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use App\Models\Socio;

class SociosExport implements FromView
{
    private $documento;
    private $nombreSocio;
    private $cuentaBancaria;
    private $telefono;
    private $usuario;
    private $pago;

    public function __construct($documento = '', $nombreSocio = '', $cuentaBancaria = '', $telefono = '', $usuario = '', $pago = '')
    {
        $this->documento = $documento;
        $this->nombreSocio  = $nombreSocio;
        $this->cuentaBancaria = $cuentaBancaria;
        $this->telefono  = $telefono;
        $this->usuario = $usuario;
        $this->pago  = $pago;
    }

    public function view(): View
    {
        $documento = $this->documento;
        $nombre  = $this->nombreSocio;
        $cuentaBancaria = $this->cuentaBancaria;
        $telefono  = $this->telefono;
        $usuario = $this->usuario;
        $pago  = $this->pago;

        //por convenio
        if(Auth::user()->tipo_socio == 1)
        {
            $query = Socio::with('banco', 'creador')
            ->where('convenio', 1)
            ->where('estado', 1);

            //parametros de busqueda
            if(!empty($documento)){
                $query = $query->where('documento', 'like', '%'.$documento.'%');
            }
            if(!empty($nombre)){
                $query = $query->where('nombres', 'like', '%'.$nombre.'%');
            }
            if(!empty($cuentaBancaria)){
                $query = $query->where('cuenta', 'like', '%'.$cuentaBancaria.'%');
            }
            if(!empty($telefono)){
                $query = $query->where('telefono', 'like', '%'.$telefono.'%');
            }
            if(!empty($usuario)){
                $query = $query->where('user_id', '=', $usuario);
            }
            if(!empty($pago)){
                $query = $query->where('pago', '=', $pago);
            }
            $query = $query->orderBy('id', 'DESC')->get();

            $socios = $query;
        }

        //otros bancos
        if(Auth::user()->tipo_socio == 2)
        {
            $query = Socio::with('banco', 'creador')
            ->where('convenio', 2)
            ->where('estado', 1);

            //parametros de busqueda
            if(!empty($documento)){
                $query = $query->where('documento', 'like', '%'.$documento.'%');
            }
            if(!empty($nombre)){
                $query = $query->where('nombres', 'like', '%'.$nombre.'%');
            }
            if(!empty($cuentaBancaria)){
                $query = $query->where('cuenta', 'like', '%'.$cuentaBancaria.'%');
            }
            if(!empty($telefono)){
                $query = $query->where('telefono', 'like', '%'.$telefono.'%');
            }
            if(!empty($usuario)){
                $query = $query->where('user_id', '=', $usuario);
            }
            if(!empty($pago)){
                $query = $query->where('pago', '=', $pago);
            }
            $query = $query->orderBy('id', 'DESC')->get();

            $socios = $query;
        }

        //inactivos
        if(Auth::user()->tipo_socio == 3)
        {
            $query = Socio::with('banco', 'creador')
            ->where('estado', 2);

            //parametros de busqueda
            if(!empty($documento)){
                $query = $query->where('documento', 'like', '%'.$documento.'%');
            }
            if(!empty($nombre)){
                $query = $query->where('nombres', 'like', '%'.$nombre.'%');
            }
            if(!empty($cuentaBancaria)){
                $query = $query->where('cuenta', 'like', '%'.$cuentaBancaria.'%');
            }
            if(!empty($telefono)){
                $query = $query->where('telefono', 'like', '%'.$telefono.'%');
            }
            if(!empty($usuario)){
                $query = $query->where('user_id', '=', $usuario);
            }
            if(!empty($pago)){
                $query = $query->where('pago', '=', $pago);
            }
            $query = $query->orderBy('updated_at', 'DESC')->get();

            $socios = $query;
        }

         //pendientes
         if(Auth::user()->tipo_socio == 4)
         {
             $query = Socio::with('banco', 'creador')
             ->where('estado', '!=', 3)
             ->where('pago', 2);

             //parametros de busqueda
             if(!empty($documento)){
                 $query = $query->where('documento', 'like', '%'.$documento.'%');
             }
             if(!empty($nombre)){
                 $query = $query->where('nombres', 'like', '%'.$nombre.'%');
             }
             if(!empty($cuentaBancaria)){
                 $query = $query->where('cuenta', 'like', '%'.$cuentaBancaria.'%');
             }
             if(!empty($telefono)){
                 $query = $query->where('telefono', 'like', '%'.$telefono.'%');
             }
             if(!empty($usuario)){
                 $query = $query->where('user_id', '=', $usuario);
             }
             if(!empty($pago)){
                 $query = $query->where('pago', '=', $pago);
             }
             $query = $query->orderBy('id', 'DESC')->get();

             $socios = $query;
         }

         //activos
         if(Auth::user()->tipo_socio == 5)
         {
             $query = Socio::with('banco', 'creador')
             ->where('estado', 1);

             //parametros de busqueda
             if(!empty($documento)){
                 $query = $query->where('documento', 'like', '%'.$documento.'%');
             }
             if(!empty($nombre)){
                 $query = $query->where('nombres', 'like', '%'.$nombre.'%');
             }
             if(!empty($cuentaBancaria)){
                 $query = $query->where('cuenta', 'like', '%'.$cuentaBancaria.'%');
             }
             if(!empty($telefono)){
                 $query = $query->where('telefono', 'like', '%'.$telefono.'%');
             }
             if(!empty($usuario)){
                 $query = $query->where('user_id', '=', $usuario);
             }
             if(!empty($pago)){
                 $query = $query->where('pago', '=', $pago);
             }
             $query = $query->orderBy('id', 'DESC')->get();

             $socios = $query;
         }

         //total socios
         if(Auth::user()->tipo_socio == 6)
         {
             $query = Socio::with('banco', 'creador');
             if(!empty($documento)){
                 $query = $query->where('documento', 'like', '%'.$documento.'%');
             }
             if(!empty($nombre)){
                 $query = $query->where('nombres', 'like', '%'.$nombre.'%');
             }
             if(!empty($cuentaBancaria)){
                 $query = $query->where('cuenta', 'like', '%'.$cuentaBancaria.'%');
             }
             if(!empty($telefono)){
                 $query = $query->where('telefono', 'like', '%'.$telefono.'%');
             }
             if(!empty($usuario)){
                 $query = $query->where('user_id', '=', $usuario);
             }
             if(!empty($pago)){
                 $query = $query->where('pago', '=', $pago);
             }
             $query = $query->orderBy('id', 'DESC')->get();

             $socios = $query;
         }

         //BLOQUEADOS
         if(Auth::user()->tipo_socio == 7)
         {
             $query = Socio::with('banco', 'creador')
             ->where('estado', 3);

             //parametros de busqueda
             if(!empty($documento)){
                 $query = $query->where('documento', 'like', '%'.$documento.'%');
             }
             if(!empty($nombre)){
                 $query = $query->where('nombres', 'like', '%'.$nombre.'%');
             }
             if(!empty($cuentaBancaria)){
                 $query = $query->where('cuenta', 'like', '%'.$cuentaBancaria.'%');
             }
             if(!empty($telefono)){
                 $query = $query->where('telefono', 'like', '%'.$telefono.'%');
             }
             if(!empty($usuario)){
                 $query = $query->where('user_id', '=', $usuario);
             }
             if(!empty($pago)){
                 $query = $query->where('pago', '=', $pago);
             }
             $query = $query->orderBy('id', 'DESC')->get();

             $socios = $query;
         }

         //Fallecidos
         if(Auth::user()->tipo_socio == 8)
         {
             $query = Socio::with('banco', 'creador')
             ->where('estado', 4);

             //parametros de busqueda
             if(!empty($documento)){
                 $query = $query->where('documento', 'like', '%'.$documento.'%');
             }
             if(!empty($nombre)){
                 $query = $query->where('nombres', 'like', '%'.$nombre.'%');
             }
             if(!empty($cuentaBancaria)){
                 $query = $query->where('cuenta', 'like', '%'.$cuentaBancaria.'%');
             }
             if(!empty($telefono)){
                 $query = $query->where('telefono', 'like', '%'.$telefono.'%');
             }
             if(!empty($usuario)){
                 $query = $query->where('user_id', '=', $usuario);
             }
             if(!empty($pago)){
                 $query = $query->where('pago', '=', $pago);
             }
             $query = $query->orderBy('id', 'DESC')->get();

             $socios = $query;
         }


        return view('exports.socios', ['socios' => $socios]);
    }
}
