<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ProcesoSocio;
use Symfony\Component\HttpFoundation\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProcesoSocioExport;
use App\Models\Socio;

class ProcesoSocioController extends Controller
{
  public function index()
  {
    if (!isset(Auth::user()->name)) {
      return redirect()->route("login");
    }
    return view("procesos");
  }

  public function store(Request $request)
  {
    $socio = Socio::find($request->socio);
    if ($socio->pago == 2) {
      $proceso = new ProcesoSocio();
      $proceso->socio_id = $request->socio;
      $proceso->lugar = $request->lugar;
      $proceso->detalle = $request->detalle;
      $proceso->valor = $request->valor;
      $proceso->creador_id = $request->user_id;
      $proceso->save();

      return $proceso;
    } else {
      return "pendiente";
    }
  }

  public function show(Request $request)
  {
    $type_user = $request->type_user;
    $user_id = $request->usuario_id;

    $procesos = ProcesoSocio::query();
    $procesos->with(["socio", "creador"]);

    if ($type_user != 1) {
      if ($type_user != 2) {
        if ($type_user != 4) {
          $procesos->where("creador_id", $user_id);
        }
      }
    }

    $procesos = $procesos->orderBy("id", "DESC")->paginate(2);

    return [
      "pagination" => [
        "total" => $procesos->total(),
        "current_page" => $procesos->currentPage(),
        "per_page" => $procesos->perPage(),
        "last_page" => $procesos->lastPage(),
        "from" => $procesos->firstItem(),
        "to" => $procesos->lastPage(),
      ],
      "proceso" => $procesos,
    ];
  }

  public function procesosSocioApp(Request $request)
  {
    $response = Auth::guard("api")->user();
    if ($response == null) {
      return Error::createLocatedError("Unauthorized");
    }
    $socio_id = $response->id;

    $procesos = \DB::table("proceso_socios")
      ->join("users", "proceso_socios.creador_id", "=", "users.id")
      ->join("socios", "proceso_socios.socio_id", "=", "socios.id")
      ->where("proceso_socios.socio_id", "=", $socio_id)
      ->select(
        "proceso_socios.id",
        "proceso_socios.socio_id",
        "lugar",
        "detalle",
        "valor",
        "name",
        "proceso_socios.created_at"
      )
      ->get();
    return [
      "procesos" => $procesos,
    ];
  }

  public function buscarProceso(Request $request)
  {
    $nombre = $request->nombre;
    $lugar = $request->lugar;
    $usuario = $request->usuario;
    $type_user = $request->type_user;
    $user_id = $request->usuario_id;

    //
    // $procesos = ProcesoSocio::query();
    // $procesos->with(['socio', 'creador'])
    // ->join('socios', 'socios.id', '=', 'proceso_socios.socio_id');

    $procesos = ProcesoSocio::select(
      "proceso_socios.lugar",
      "proceso_socios.detalle",
      "proceso_socios.valor",
      "proceso_socios.created_at",
      "proceso_socios.creador_id",
      "socios.nombres",
      "users.user"
    )
      ->join("socios", "socios.id", "=", "proceso_socios.socio_id")
      ->join("users", "users.id", "=", "proceso_socios.creador_id");

    if ($type_user != 1) {
      if ($type_user != 2) {
        if ($type_user != 4) {
          $procesos->where("creador_id", $user_id);
        }
      }
    }

    //filtros de busqueda
    if (!empty($nombre)) {
      $procesos = $procesos->where("nombres", "like", "%" . $nombre . "%");
    }
    if (!empty($lugar)) {
      $procesos = $procesos->where("lugar", "like", "%" . $lugar . "%");
    }
    if (!empty($usuario)) {
      $procesos = $procesos->where("creador_id", "=", $usuario);
    }

    $procesos = $procesos->orderBy("proceso_socios.id", "DESC")->paginate(20);

    return [
      "pagination" => [
        "total" => $procesos->total(),
        "current_page" => $procesos->currentPage(),
        "per_page" => $procesos->perPage(),
        "last_page" => $procesos->lastPage(),
        "from" => $procesos->firstItem(),
        "to" => $procesos->lastPage(),
      ],
      "proceso" => $procesos,
    ];
  }

  public function update(Request $request)
  {
    $proceso = ProcesoSocio::find($request->id);
    $proceso->socio_id = $request->socio;
    $proceso->lugar = $request->lugar;
    $proceso->detalle = $request->detalle;
    $proceso->valor = $request->valor;
    $proceso->save();

    return $proceso;
  }

  public function export()
  {
    $nombre = date("YmdHis") . "_procesos.xlsx";
    Excel::store(new ProcesoSocioExport(), "/procesos_excels/" . $nombre);

    return $nombre;
  }

  public function mis_procesos()
  {
    if (!isset(Auth::user()->name)) {
      return redirect()->route("login");
    }

    $procesos = ProcesoSocio::where("socio_id", Auth::user()->id)->get();

    return view("mis_procesos", ["procesos" => $procesos]);
  }
}
