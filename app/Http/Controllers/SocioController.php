<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSocioRequest;
use App\Http\Requests\UpdateSocioRequest;
use App\Models\Socio;
use App\Models\Banco;
use App\Models\User;
use App\Models\AportesSocio;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SociosExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

use function GuzzleHttp\json_encode;

class SocioController extends Controller
{
  public function index()
  {
    if (!isset(Auth::user()->name)) {
      return redirect()->route("login");
    }
    return view("socios");
  }

  public function socios_tipo($valor)
  {
    $socio = Auth::user();
    $socio->tipo_socio = $valor;
    $socio->save();

    return redirect()->route("socios");
  }

  public function store(Request $request)
  {
    //verificar que el socio no se duplique
    $documentoExiste = Socio::where("documento", $request->documento)->get();
    $nombresExisten = Socio::where(
      "nombres",
      "like",
      strtolower($request->nombres)
    )->get();
    if (count($documentoExiste) == 0 && count($nombresExisten) == 0) {
      $banco = Banco::find($request->banco);
      $socio = new Socio();
      $socio->tipo_documento = $request->tipo_documento;
      $socio->documento = $request->documento;
      $socio->nombres = $request->nombres;
      $socio->direccion = $request->direccion;
      $socio->telefono = $request->telefono;
      $socio->movil = $request->movil;
      $socio->correo = $request->correo;
      $socio->foto = $request->perfil;
      $socio->nombres_referencia = $request->nombre_referencia;
      $socio->movil_referencia = $request->movil_referencia;
      $socio->fuerza = $request->fuerza;
      $socio->estado = $request->estado;
      $socio->tipo = $request->tipo;
      $socio->observacion = $request->observaciones;
      $socio->mies = $request->mies;
      $socio->mt = $request->mt;
      $socio->user_id = $request->user_id;
      $socio->cuenta = $request->cuenta;
      $socio->documento_pdf = $request->pdf;
      if ($socio->convenio != null) {
        $socio->convenio = $banco->convenio;
        $socio->banco_id = $request->banco;
      } else {
        $socio->convenio = 0;
        $socio->banco_id = 17;
      }
      $nombre = "socio_" . date("YmdHis") . "_ficha.pdf";
      $socio->ficha_pdf = $nombre;
      $socio->pago = 2;
      $socio->hospitalizacion = $request->hospitalizacion;
      $socio->abono = $request->abono;
      $socio->abonos = $request->abonos;

      if (empty($request->perfil)) {
        return "Campo foto del socio esta vacio";
      } else {
        $socio->save();
        $this->storePdf($socio, $nombre);
        User::create([
          "email" => $request->correo,
          "name" => $request->nombres,
          "user" => $request->nombres,
          "password" => sha1("Sistemameta2022"),
          "type_user" => 5,
          "status_user" => $request->estado,
          "socio_id" => $socio->id,
        ]);
        Mail::send("mails.newUser", ["nombre" => $request->nombres], function (
          $message
        ) use ($request) {
          $message->to($request->correo);
          $message->subject("Bienvenid@ a asociacionmilitaresnuevavision");
        });
        return $socio;
      }
    } else {
      return "existe";
    }
  }

  public function show(Request $request)
  {
    $socio = [];
    //por convenio
    if (Auth::user()->tipo_socio == 1) {
      $socio = Socio::with("banco", "creador")
        ->where("convenio", 1)
        ->where("estado", 1)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //otros bancos
    if (Auth::user()->tipo_socio == 2) {
      $socio = Socio::with("banco", "creador")
        ->where("convenio", 2)
        ->where("estado", 1)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //inactivos
    if (Auth::user()->tipo_socio == 3) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", 2)
        ->orderBy("updated_at", "DESC")
        ->paginate(25);
    }

    //pendientes
    if (Auth::user()->tipo_socio == 4) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", "!=", 3)
        ->where("pago", 2)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //activos
    if (Auth::user()->tipo_socio == 5) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", 1)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //total socios
    if (Auth::user()->tipo_socio == 6) {
      $socio = Socio::with("banco", "creador")
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //BLOQUEADOS
    if (Auth::user()->tipo_socio == 7) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", 3)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //Fallecidos
    if (Auth::user()->tipo_socio == 8) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", 4)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //Cuenta cancelada
    if (Auth::user()->tipo_socio == 9) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", 5)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //Cuenta inactiva
    if (Auth::user()->tipo_socio == 10) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", 6)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //Cuenta no corresponde
    if (Auth::user()->tipo_socio == 11) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", 7)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //Expulsado
    if (Auth::user()->tipo_socio == 12) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", 8)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //Suspendido
    if (Auth::user()->tipo_socio == 13) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", 9)
        ->orderBy("id", "DESC")
        ->paginate(25);
    }

    //No existe cuenta
    if (Auth::user()->tipo_socio == 14) {
      $socio = Socio::with("banco", "creador")
        ->where("estado", 10)
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
      $query = Socio::with("banco", "creador")
        ->where("convenio", 1)
        ->where("estado", 1);

      //parametros de busqueda
      if (!empty($documento)) {
        $query = $query->where("documento", "like", "%" . $documento . "%");
      }
      if (!empty($nombre)) {
        $query = $query->where("nombres", "like", "%" . $nombre . "%");
      }
      if (!empty($cuentaBancaria)) {
        $query = $query->where("cuenta", "like", "%" . $cuentaBancaria . "%");
      }
      if (!empty($telefono)) {
        $query = $query->where("telefono", "like", "%" . $telefono . "%");
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
      $query = Socio::with("banco", "creador")
        ->where("convenio", 2)
        ->where("estado", 1);

      //parametros de busqueda
      if (!empty($documento)) {
        $query = $query->where("documento", "like", "%" . $documento . "%");
      }
      if (!empty($nombre)) {
        $query = $query->where("nombres", "like", "%" . $nombre . "%");
      }
      if (!empty($cuentaBancaria)) {
        $query = $query->where("cuenta", "like", "%" . $cuentaBancaria . "%");
      }
      if (!empty($telefono)) {
        $query = $query->where("telefono", "like", "%" . $telefono . "%");
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
      $query = Socio::with("banco", "creador")->where("estado", 2);

      //parametros de busqueda
      if (!empty($documento)) {
        $query = $query->where("documento", "like", "%" . $documento . "%");
      }
      if (!empty($nombre)) {
        $query = $query->where("nombres", "like", "%" . $nombre . "%");
      }
      if (!empty($cuentaBancaria)) {
        $query = $query->where("cuenta", "like", "%" . $cuentaBancaria . "%");
      }
      if (!empty($telefono)) {
        $query = $query->where("telefono", "like", "%" . $telefono . "%");
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
      $query = Socio::with("banco", "creador")
        ->where("estado", "!=", 3)
        ->where("pago", 2);

      //parametros de busqueda
      if (!empty($documento)) {
        $query = $query->where("documento", "like", "%" . $documento . "%");
      }
      if (!empty($nombre)) {
        $query = $query->where("nombres", "like", "%" . $nombre . "%");
      }
      if (!empty($cuentaBancaria)) {
        $query = $query->where("cuenta", "like", "%" . $cuentaBancaria . "%");
      }
      if (!empty($telefono)) {
        $query = $query->where("telefono", "like", "%" . $telefono . "%");
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
      $query = Socio::with("banco", "creador")->where("estado", 1);

      //parametros de busqueda
      if (!empty($documento)) {
        $query = $query->where("documento", "like", "%" . $documento . "%");
      }
      if (!empty($nombre)) {
        $query = $query->where("nombres", "like", "%" . $nombre . "%");
      }
      if (!empty($cuentaBancaria)) {
        $query = $query->where("cuenta", "like", "%" . $cuentaBancaria . "%");
      }
      if (!empty($telefono)) {
        $query = $query->where("telefono", "like", "%" . $telefono . "%");
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
      $query = Socio::with("banco", "creador");
      if (!empty($documento)) {
        $query = $query->where("documento", "like", "%" . $documento . "%");
      }
      if (!empty($nombre)) {
        $query = $query->where("nombres", "like", "%" . $nombre . "%");
      }
      if (!empty($cuentaBancaria)) {
        $query = $query->where("cuenta", "like", "%" . $cuentaBancaria . "%");
      }
      if (!empty($telefono)) {
        $query = $query->where("telefono", "like", "%" . $telefono . "%");
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
      $query = Socio::with("banco", "creador")->where("estado", 3);

      //parametros de busqueda
      if (!empty($documento)) {
        $query = $query->where("documento", "like", "%" . $documento . "%");
      }
      if (!empty($nombre)) {
        $query = $query->where("nombres", "like", "%" . $nombre . "%");
      }
      if (!empty($cuentaBancaria)) {
        $query = $query->where("cuenta", "like", "%" . $cuentaBancaria . "%");
      }
      if (!empty($telefono)) {
        $query = $query->where("telefono", "like", "%" . $telefono . "%");
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
      $query = Socio::with("banco", "creador")->where("estado", 4);

      //parametros de busqueda
      if (!empty($documento)) {
        $query = $query->where("documento", "like", "%" . $documento . "%");
      }
      if (!empty($nombre)) {
        $query = $query->where("nombres", "like", "%" . $nombre . "%");
      }
      if (!empty($cuentaBancaria)) {
        $query = $query->where("cuenta", "like", "%" . $cuentaBancaria . "%");
      }
      if (!empty($telefono)) {
        $query = $query->where("telefono", "like", "%" . $telefono . "%");
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

  public function update(Request $request)
  {
    $banco = $request->banco;

    if (is_array($request->banco)) {
      $convenio = $banco["convenio"];
      $id = $banco["id"];
    } else {
      $banco = Banco::find($request->banco);
      $convenio = $banco->convenio;
      $id = $request->banco;
    }

    if (is_array($request->fuerza)) {
      $fuerza = $request->fuerza["id"];
    } else {
      $fuerza = $request->fuerza;
    }

    if (is_array($request->estado)) {
      $estado = $request->estado["id"];
    } else {
      $estado = $request->estado;
    }

    if (is_array($request->pago)) {
      $pago = $request->pago["id"];
    } else {
      $pago = $request->pago;
    }

    $socio = Socio::find($request->id);
    $socio->tipo_documento = $request->tipo_documento;
    $socio->documento = $request->documento;
    $socio->nombres = $request->nombres;
    $socio->direccion = $request->direccion;
    $socio->telefono = $request->telefono;
    $socio->movil = $request->movil;
    $socio->correo = $request->correo;
    if ($request->perfil != null) {
      $socio->foto = $request->perfil;
    }
    $socio->nombres_referencia = $request->nombre_referencia;
    $socio->movil_referencia = $request->movil_referencia;
    $socio->fuerza = $fuerza;
    $socio->estado = $estado;
    $socio->tipo = $request->tipo;
    $socio->observacion = $request->observacion;
    $socio->mies = $request->mies;
    $socio->mt = $request->mt;
    $socio->cuenta = $request->cuenta;
    if ($request->pdf != null) {
      $socio->documento_pdf = $request->pdf;
    }
    $socio->convenio = $convenio;
    $socio->banco_id = $id;
    $nombre = "socio_" . date("YmdHis") . "_ficha.pdf";
    $socio->ficha_pdf = $nombre;
    $socio->pago = $pago;
    $socio->hospitalizacion = $request->hospitalizacion;
    $socio->abono = $request->abono;
    $socio->abonos = $request->abonos;
    $socio->save();

    $this->storePdf($socio, $nombre);

    Mail::send("mails.userUpdate", ["nombre" => $request->nombres], function (
      $message
    ) use ($request) {
      $message->to($request->correo);
      $message->subject("Notificacion");
    });

    return $socio;
  }

  public function get_socio($socio)
  {
    return Socio::where("id", $socio)
      ->with("banco")
      ->first();
  }

  public function getSocioApp(Request $request)
  {
    $response = Auth::guard("api")->user();
    if ($response == null) {
      return Error::createLocatedError("Unauthorized");
    }

    $socio = [
      "id" => $response->id,
      "nombres" => $response->nombres,
      "direccion" => $response->direccion,
      "correo" => $response->correo,
      "email" => $response->email,
      "movil" => $response->movil,
      "telefono" => $response->telefono,
      "nombres_referencia" => $response->nombres_referencia,
      "movil_referencia" => $response->movil_referencia,
      "foto_url" => URL::asset("/documentos_imagenes/" . $response->foto),
      "foto_name" => $response->foto,
    ];
    return response()->json(["message" => "success", "socio" => $socio]);
  }

  public function list_all()
  {
    return Socio::all();
  }

  public function upload_imagen(Request $request)
  {
    //obtenemos el campo file definido en el formulario
    $file = $request->file("file");

    //obtenemos el nombre del archivo
    $nombre = $file->getClientOriginalName();
    $extension = explode(".", $nombre);
    $nombre = date("YmdHis") . "." . $extension[1];

    //indicamos que queremos guardar un nuevo archivo en el disco local
    \Storage::disk("local")->put($nombre, \File::get($file));

    return $nombre;
  }

  public function upload_documento(Request $request)
  {
    $file = $request->file("file");

    //obtenemos el nombre del archivo
    $nombre = $file->getClientOriginalName();
    $extension = explode(".", $nombre);
    $nombre = date("YmdHis") . "_documento." . $extension[1];

    \Storage::disk("local")->put($nombre, \File::get($file));

    return $nombre;
  }

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
    $socio = Socio::find($usuario->socio_id);

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

  public function login(Request $request)
  {
    $user = $request->user;
    $password = $request->password;

    $passwordV = Socio::where("documento", $user)
      ->where("documento", $user)
      ->select("password")
      ->get()
      ->first()->password;

    $id = null;
    if ($passwordV == null) {
      $id = Socio::where("documento", $user)
        ->where("documento", $password)
        ->select("id", "nombres")
        ->get()
        ->first()->id;
    } else {
      $socio = Socio::where("documento", $user)
        ->select("id", "nombres", "password")
        ->get()
        ->first();
      if (Hash::check($password, $socio->password)) {
        $id = $socio->id;
      }
    }

    if ($id != null) {
      $user = Socio::find($id);
      $tokenResult = $user->createToken("Personal Access Token");
      return response()->json([
        "message" => "Success",
        "token" => $tokenResult->accessToken,
      ]);
    } else {
      return response()->json(["error" => "Unauthorized"], 401);
    }
  }

  public function updatePassword(Request $request)
  {
    $response = Auth::guard("api")->user();
    if ($response == null) {
      return Error::createLocatedError("Unauthorized");
    }

    $id = $response->id;
    $old_password = $request->oldPassword;
    $new_password = $request->newPassword;

    $socio = Socio::find($id);
    if ($socio->password != null) {
      $contrasena_encriptada = $socio->password;

      if (!Hash::check($old_password, $contrasena_encriptada)) {
        return response()->json(["error" => "Contraseña incorrecta"], 401);
      }
    }
    $socio->password = bcrypt($new_password);
    $socio->save();
    return response()->json([
      "message" => "Success",
    ]);
  }

  public function updateSocio(Request $request)
  {
    $response = Auth::guard("api")->user();
    if ($response == null) {
      return Error::createLocatedError("Unauthorized");
    }

    $id = $response->id;
    $socio_old = json_decode($request->socio);

    $socio = Socio::find($id);
    $socio->nombres_referencia = $socio_old->namesReferences;
    $socio->telefono = $socio_old->tel;
    $socio->movil = $socio_old->cel;
    $socio->movil_referencia = $socio_old->telReference;
    $socio->direccion = $socio_old->address;
    $socio->correo = $socio_old->email;
    $socio->save();

    return response()->json([
      "message" => "Success",
    ]);
  }

  public function logoutSocioApp()
  {
    if (Auth::guard("api")->check()) {
      Auth::guard("api")
        ->user()
        ->token()
        ->revoke();
    }
  }

  public function updateSocioPhoto(Request $request)
  {
    $response = Auth::guard("api")->user();
    if ($response == null) {
      return response()->json(
        [
          "error" => "Unauthorized",
        ],
        400
      );
    }

    $foto_old = $response->foto;
    $file = $request->file("photo");
    if ($file->isValid()) {
      $nombre = $file->getClientOriginalName();
      $extension = explode(".", $nombre);
      $nombre = date("YmdHis") . "." . $extension[1];

      //indicamos que queremos guardar un nuevo archivo en el disco local
      \Storage::disk("local")->put($nombre, \File::get($file));
      $id = $response->id;

      $nombreFicha = "socio_" . date("YmdHis") . "_ficha.pdf";

      $socio = Socio::find($id);
      $socio->foto = $nombre;
      $socio->ficha_pdf = $nombreFicha;
      $socio->save();

      \Storage::disk("local")->delete($foto_old);
      try {
        $this->storePdf($socio, $nombreFicha);
      } catch (\Throwable $th) {
        return response()->json(
          [
            "error" => "Error",
          ],
          400
        );
      }

      return response()->json([
        "message" => "Success",
      ]);
    } else {
      return response()->json(
        [
          "error" => "El archivo no es válido",
        ],
        400
      );
    }
  }
}
