<?php

namespace App\Http\Controllers;

use App\Models\Aporte;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class AporteController extends Controller
{
  public function index()
  {
    if (!isset(Auth::user()->name)) {
      return redirect()->route("login");
    }

    return view("aportes");
  }

  public function store(Request $request)
  {
    $aporte = new Aporte();
    $aporte->id_usuario = $request->usuario_id;
    $aporte->ano = $request->ano;
    $aporte->tipo_aporte = $request->tipo_aporte;
    $aporte->aporte = $request->aporte;
    $aporte->save();

    return $aporte;
  }

  public function show()
  {
    return Aporte::all();
  }

  public function update(Request $request)
  {
    if ($request->usuario_id == 1 || $request->usuario_id == 3) {
      $aporte = Aporte::find($request->id);
      $aporte->ano = $request->ano;
      $aporte->tipo_aporte = $request->tipo_aporte;
      $aporte->aporte = $request->aporte;
      $aporte->save();
    } else {
      return response()->json(
        [
          "message" => "insufficient permissions",
          "code" => 400,
        ],
        400
      );
    }
  }
}
