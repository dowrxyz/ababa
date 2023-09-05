<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\AportesSocio;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SociosExport;
use App\Models\Historico;
use Illuminate\Support\Facades\Storage;

class HistoricoController extends Controller
{
    public function index()
    {
        if (!isset(Auth::user()->name)) {
            return redirect()->route("login");
        }
        return view("historico");
    }

    public function socios_tipo($valor)
    {
        $socio = Auth::user();
        $socio->tipo_socio = $valor;
        $socio->save();

        return redirect()->route("historico");
    }

    // public function store(Request $request)
    // {
    //     //verificar que el socio no se duplique
    //     $consultaSocio = Socio::where("documento", $request->documento)->get();
    //     if (count($consultaSocio) == 0) {
    //         $banco = Banco::find($request->banco);
    //         $socio = new Socio();
    //         $socio->tipo_documento = $request->tipo_documento;
    //         $socio->documento = $request->documento;
    //         $socio->nombres = $request->nombres;
    //         $socio->direccion = $request->direccion;
    //         $socio->telefono = $request->telefono;
    //         $socio->movil = $request->movil;
    //         $socio->correo = $request->correo;
    //         $socio->foto = $request->perfil;
    //         $socio->nombres_referencia = $request->nombre_referencia;
    //         $socio->movil_referencia = $request->movil_referencia;
    //         $socio->fuerza = $request->fuerza;
    //         $socio->estado = $request->estado;
    //         $socio->observacion = $request->observaciones;
    //         $socio->mies = $request->mies;
    //         $socio->mt = $request->mt;
    //         $socio->user_id = $request->user_id;
    //         $socio->cuenta = $request->cuenta;
    //         $socio->documento_pdf = $request->pdf;
    //         $socio->convenio = $banco->convenio;
    //         $socio->banco_id = $request->banco;
    //         $nombre = "socio_" . date("YmdHis") . "_ficha.pdf";
    //         $socio->ficha_pdf = $nombre;
    //         $socio->pago = 2;
    //
    //         if (empty($request->perfil)) {
    //             return "Campo foto del socio esta vacio";
    //         } else {
    //             $socio->save();
    //             $this->storePdf($socio, $nombre);
    //             User::create([
    //                 "email" => $request->correo,
    //                 "name" => $request->nombres,
    //                 "user" => $request->nombres,
    //                 "password" => sha1("Sistemameta2022"),
    //                 "type_user" => 5,
    //                 "status_user" => $request->estado,
    //                 "socio_id" => $socio->id,
    //             ]);
    //             //enviar correo
    //             //$mailable = new WelcomeCallReceived('kewing', 'martes');
    //             //Mail::to("kewingjarquin@gmail.com")->send($mailable);
    //             return $socio;
    //         }
    //     } else {
    //         return "existe";
    //     }
    // }

    public function show(Request $request)
    {
        $socio = [];
        //por convenio
        if (Auth::user()->tipo_socio == 1) {
            $socio = Historico::with("banco", "creador")
                ->where("convenio", 1)
                ->where("estado", 1)
                ->orderBy("id", "DESC")
                ->paginate(25);
        }

        //otros bancos
        if (Auth::user()->tipo_socio == 2) {
            $socio = Historico::with("banco", "creador")
                ->where("convenio", 2)
                ->where("estado", 1)
                ->orderBy("id", "DESC")
                ->paginate(25);
        }

        //inactivos
        if (Auth::user()->tipo_socio == 3) {
            $socio = Historico::with("banco", "creador")
                ->where("estado", 2)
                ->orderBy("updated_at", "DESC")
                ->paginate(25);
        }

        //pendientes
        if (Auth::user()->tipo_socio == 4) {
            $socio = Historico::with("banco", "creador")
                ->where("estado", "!=", 3)
                ->where("pago", 2)
                ->orderBy("id", "DESC")
                ->paginate(25);
        }

        //activos
        if (Auth::user()->tipo_socio == 5) {
            $socio = Historico::with("banco", "creador")
                ->where("estado", 1)
                ->orderBy("id", "DESC")
                ->paginate(25);
        }

        //total socios
        if (Auth::user()->tipo_socio == 6) {
            $socio = Historico::with("banco", "creador")
                ->orderBy("id", "DESC")
                ->paginate(25);
        }

        //BLOQUEADOS
        if (Auth::user()->tipo_socio == 7) {
            $socio = Historico::with("banco", "creador")
                ->where("estado", 3)
                ->orderBy("id", "DESC")
                ->paginate(25);
        }

        //Fallecidos
        if (Auth::user()->tipo_socio == 8) {
            $socio = Historico::with("banco", "creador")
                ->where("estado", 4)
                ->orderBy("id", "DESC")
                ->paginate(25);
        }

        return [
            "pagination" => [
                "total" => $socio->total(),
                "current_page" => $socio->currentPage(),
                "per_page" => $socio->perPage(),
                "last_page" => $socio->lastPage(),
                "from" => $socio->firstItem(),
                "to" => $socio->lastPage(),
            ],
            "socio" => $socio,
        ];
    }

    public function buscarSocio(Request $request)
    {
        $documento = $request->documento;
        $nombre = $request->nombre;
        $cuentaBancaria = $request->cuentaBancaria;
        $telefono = $request->telefono;
        $usuario = $request->usuario;
        $pago = $request->pago;
        $socio = [];

        //por convenio
        if (Auth::user()->tipo_socio == 1) {
            $query = Historico::with("banco", "creador")
                ->where("convenio", 1)
                ->where("estado", 1);

            //parametros de busqueda
            if (!empty($documento)) {
                $query = $query->where(
                    "documento",
                    "like",
                    "%" . $documento . "%"
                );
            }
            if (!empty($nombre)) {
                $query = $query->where("nombres", "like", "%" . $nombre . "%");
            }
            if (!empty($cuentaBancaria)) {
                $query = $query->where(
                    "cuenta",
                    "like",
                    "%" . $cuentaBancaria . "%"
                );
            }
            if (!empty($telefono)) {
                $query = $query->where(
                    "telefono",
                    "like",
                    "%" . $telefono . "%"
                );
            }
            if (!empty($usuario)) {
                $query = $query->where("user_id", "=", $usuario);
            }
            if (!empty($pago)) {
                $query = $query->where("pago", "=", $pago);
            }
            $query = $query->orderBy("id", "DESC")->paginate(25);

            $socio = $query;
        }

        //otros bancos
        if (Auth::user()->tipo_socio == 2) {
            $query = Historico::with("banco", "creador")
                ->where("convenio", 2)
                ->where("estado", 1);

            //parametros de busqueda
            if (!empty($documento)) {
                $query = $query->where(
                    "documento",
                    "like",
                    "%" . $documento . "%"
                );
            }
            if (!empty($nombre)) {
                $query = $query->where("nombres", "like", "%" . $nombre . "%");
            }
            if (!empty($cuentaBancaria)) {
                $query = $query->where(
                    "cuenta",
                    "like",
                    "%" . $cuentaBancaria . "%"
                );
            }
            if (!empty($telefono)) {
                $query = $query->where(
                    "telefono",
                    "like",
                    "%" . $telefono . "%"
                );
            }
            if (!empty($usuario)) {
                $query = $query->where("user_id", "=", $usuario);
            }
            if (!empty($pago)) {
                $query = $query->where("pago", "=", $pago);
            }
            $query = $query->orderBy("id", "DESC")->paginate(25);

            $socio = $query;
        }

        //inactivos
        if (Auth::user()->tipo_socio == 3) {
            $query = Historico::with("banco", "creador")->where("estado", 2);

            //parametros de busqueda
            if (!empty($documento)) {
                $query = $query->where(
                    "documento",
                    "like",
                    "%" . $documento . "%"
                );
            }
            if (!empty($nombre)) {
                $query = $query->where("nombres", "like", "%" . $nombre . "%");
            }
            if (!empty($cuentaBancaria)) {
                $query = $query->where(
                    "cuenta",
                    "like",
                    "%" . $cuentaBancaria . "%"
                );
            }
            if (!empty($telefono)) {
                $query = $query->where(
                    "telefono",
                    "like",
                    "%" . $telefono . "%"
                );
            }
            if (!empty($usuario)) {
                $query = $query->where("user_id", "=", $usuario);
            }
            if (!empty($pago)) {
                $query = $query->where("pago", "=", $pago);
            }
            $query = $query->orderBy("updated_at", "DESC")->paginate(25);

            $socio = $query;
        }

        //pendientes
        if (Auth::user()->tipo_socio == 4) {
            $query = Historico::with("banco", "creador")
                ->where("estado", "!=", 3)
                ->where("pago", 2);

            //parametros de busqueda
            if (!empty($documento)) {
                $query = $query->where(
                    "documento",
                    "like",
                    "%" . $documento . "%"
                );
            }
            if (!empty($nombre)) {
                $query = $query->where("nombres", "like", "%" . $nombre . "%");
            }
            if (!empty($cuentaBancaria)) {
                $query = $query->where(
                    "cuenta",
                    "like",
                    "%" . $cuentaBancaria . "%"
                );
            }
            if (!empty($telefono)) {
                $query = $query->where(
                    "telefono",
                    "like",
                    "%" . $telefono . "%"
                );
            }
            if (!empty($usuario)) {
                $query = $query->where("user_id", "=", $usuario);
            }
            if (!empty($pago)) {
                $query = $query->where("pago", "=", $pago);
            }
            $query = $query->orderBy("id", "DESC")->paginate(25);

            $socio = $query;
        }

        //activos
        if (Auth::user()->tipo_socio == 5) {
            $query = Historico::with("banco", "creador")->where("estado", 1);

            //parametros de busqueda
            if (!empty($documento)) {
                $query = $query->where(
                    "documento",
                    "like",
                    "%" . $documento . "%"
                );
            }
            if (!empty($nombre)) {
                $query = $query->where("nombres", "like", "%" . $nombre . "%");
            }
            if (!empty($cuentaBancaria)) {
                $query = $query->where(
                    "cuenta",
                    "like",
                    "%" . $cuentaBancaria . "%"
                );
            }
            if (!empty($telefono)) {
                $query = $query->where(
                    "telefono",
                    "like",
                    "%" . $telefono . "%"
                );
            }
            if (!empty($usuario)) {
                $query = $query->where("user_id", "=", $usuario);
            }
            if (!empty($pago)) {
                $query = $query->where("pago", "=", $pago);
            }
            $query = $query->orderBy("id", "DESC")->paginate(25);

            $socio = $query;
        }

        //total socios
        if (Auth::user()->tipo_socio == 6) {
            $query = Historico::with("banco", "creador");
            if (!empty($documento)) {
                $query = $query->where(
                    "documento",
                    "like",
                    "%" . $documento . "%"
                );
            }
            if (!empty($nombre)) {
                $query = $query->where("nombres", "like", "%" . $nombre . "%");
            }
            if (!empty($cuentaBancaria)) {
                $query = $query->where(
                    "cuenta",
                    "like",
                    "%" . $cuentaBancaria . "%"
                );
            }
            if (!empty($telefono)) {
                $query = $query->where(
                    "telefono",
                    "like",
                    "%" . $telefono . "%"
                );
            }
            if (!empty($usuario)) {
                $query = $query->where("user_id", "=", $usuario);
            }
            if (!empty($pago)) {
                $query = $query->where("pago", "=", $pago);
            }
            $query = $query->orderBy("id", "DESC")->paginate(25);

            $socio = $query;
        }

        //BLOQUEADOS
        if (Auth::user()->tipo_socio == 7) {
            $query = Historico::with("banco", "creador")->where("estado", 3);

            //parametros de busqueda
            if (!empty($documento)) {
                $query = $query->where(
                    "documento",
                    "like",
                    "%" . $documento . "%"
                );
            }
            if (!empty($nombre)) {
                $query = $query->where("nombres", "like", "%" . $nombre . "%");
            }
            if (!empty($cuentaBancaria)) {
                $query = $query->where(
                    "cuenta",
                    "like",
                    "%" . $cuentaBancaria . "%"
                );
            }
            if (!empty($telefono)) {
                $query = $query->where(
                    "telefono",
                    "like",
                    "%" . $telefono . "%"
                );
            }
            if (!empty($usuario)) {
                $query = $query->where("user_id", "=", $usuario);
            }
            if (!empty($pago)) {
                $query = $query->where("pago", "=", $pago);
            }
            $query = $query->orderBy("id", "DESC")->paginate(25);

            $socio = $query;
        }

        //Fallecidos
        if (Auth::user()->tipo_socio == 8) {
            $query = Historico::with("banco", "creador")->where("estado", 4);

            //parametros de busqueda
            if (!empty($documento)) {
                $query = $query->where(
                    "documento",
                    "like",
                    "%" . $documento . "%"
                );
            }
            if (!empty($nombre)) {
                $query = $query->where("nombres", "like", "%" . $nombre . "%");
            }
            if (!empty($cuentaBancaria)) {
                $query = $query->where(
                    "cuenta",
                    "like",
                    "%" . $cuentaBancaria . "%"
                );
            }
            if (!empty($telefono)) {
                $query = $query->where(
                    "telefono",
                    "like",
                    "%" . $telefono . "%"
                );
            }
            if (!empty($usuario)) {
                $query = $query->where("user_id", "=", $usuario);
            }
            if (!empty($pago)) {
                $query = $query->where("pago", "=", $pago);
            }
            $query = $query->orderBy("id", "DESC")->paginate(25);

            $socio = $query;
        }

        return [
            "pagination" => [
                "total" => $socio->total(),
                "current_page" => $socio->currentPage(),
                "per_page" => $socio->perPage(),
                "last_page" => $socio->lastPage(),
                "from" => $socio->firstItem(),
                "to" => $socio->lastPage(),
            ],
            "socio" => $socio,
        ];
    }

    // public function update(Request $request)
    // {
    //     $banco = $request->banco;
    //
    //     if (is_array($request->banco)) {
    //         $convenio = $banco["convenio"];
    //         $id = $banco["id"];
    //     } else {
    //         $banco = Banco::find($request->banco);
    //         $convenio = $banco->convenio;
    //         $id = $request->banco;
    //     }
    //
    //     if (is_array($request->fuerza)) {
    //         $fuerza = $request->fuerza["id"];
    //     } else {
    //         $fuerza = $request->fuerza;
    //     }
    //
    //     if (is_array($request->estado)) {
    //         $estado = $request->estado["id"];
    //     } else {
    //         $estado = $request->estado;
    //     }
    //
    //     if (is_array($request->pago)) {
    //         $pago = $request->pago["id"];
    //     } else {
    //         $pago = $request->pago;
    //     }
    //
    //     $socio = Historico::find($request->id);
    //     $socio->tipo_documento = $request->tipo_documento;
    //     $socio->documento = $request->documento;
    //     $socio->nombres = $request->nombres;
    //     $socio->direccion = $request->direccion;
    //     $socio->telefono = $request->telefono;
    //     $socio->movil = $request->movil;
    //     $socio->correo = $request->correo;
    //     if ($request->perfil != null) {
    //         $socio->foto = $request->perfil;
    //     }
    //     $socio->nombres_referencia = $request->nombre_referencia;
    //     $socio->movil_referencia = $request->movil_referencia;
    //     $socio->fuerza = $fuerza;
    //     $socio->estado = $estado;
    //     $socio->observacion = $request->observacion;
    //     $socio->mies = $request->mies;
    //     $socio->mt = $request->mt;
    //     $socio->cuenta = $request->cuenta;
    //     if ($request->pdf != null) {
    //         $socio->documento_pdf = $request->pdf;
    //     }
    //     $socio->convenio = $convenio;
    //     $socio->banco_id = $id;
    //     $nombre = "socio_" . date("YmdHis") . "_ficha.pdf";
    //     $socio->ficha_pdf = $nombre;
    //     $socio->pago = $pago;
    //     $socio->save();
    //
    //     $this->storePdf($socio, $nombre);
    //
    //     return $socio;
    // }

    public function get_socio($socio)
    {
        return Historico::where("id", $socio)
            ->with("banco")
            ->first();
    }

    public function list_all()
    {
        return Historico::all();
    }

    // public function upload_imagen(Request $request)
    // {
    //     //obtenemos el campo file definido en el formulario
    //     $file = $request->file("file");
    //
    //     //obtenemos el nombre del archivo
    //     $nombre = $file->getClientOriginalName();
    //     $extension = explode(".", $nombre);
    //     $nombre = date("YmdHis") . "." . $extension[1];
    //
    //     //indicamos que queremos guardar un nuevo archivo en el disco local
    //     Storage::disk("local")->put($nombre, \File::get($file));
    //
    //     return $nombre;
    // }
    //
    // public function upload_documento(Request $request)
    // {
    //     $file = $request->file("file");
    //
    //     //obtenemos el nombre del archivo
    //     $nombre = $file->getClientOriginalName();
    //     $extension = explode(".", $nombre);
    //     $nombre = date("YmdHis") . "_documento." . $extension[1];
    //
    //     Storage::disk("local")->put($nombre, \File::get($file));
    //
    //     return $nombre;
    // }

    public function storePdf($array, $nombre)
    {
        $data = PDF::loadView("ficha_socio", ["socio" => $array])->save(
            public_path("fichas_socios/") . $nombre
        );
    }

    public function mis_aportes()
    {
        if (!isset(Auth::user()->name)) {
            return redirect()->route("login");
        }

        $aportes = AportesSocio::where("socio_id", Auth::user()->socio_id)
            ->with(["banco"])
            ->get();
        //return $aportes;
        return view("mis_aportes", [
            "aportes" => $aportes,
        ]);
    }

    public function perfil()
    {
        $usuario = Auth::user();
        $socio = Historico::find($usuario->socio_id);

        //return $socio;
        return view("perfil", [
            "usuario" => $usuario,
            "socio" => $socio,
        ]);
    }

    public function export(Request $request)
    {
        $documento = $request->documento;
        $nombreSocio = $request->nombre;
        $cuentaBancaria = $request->cuentaBancaria;
        $telefono = $request->telefono;
        $usuario = $request->usuario;
        $pago = $request->pago;

        $nombre = date("YmdHis") . "_socios.xlsx";
        Excel::store(
            new SociosExport(
                $documento,
                $nombreSocio,
                $cuentaBancaria,
                $telefono,
                $usuario,
                $pago
            ),
            "/socios_Excels/" . $nombre
        );

        return $nombre;
    }
}
