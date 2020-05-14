<?php

namespace App\Http\Controllers;

use App\Caso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CasosController extends Controller
{
    public function index()
    {
        //$casos = Caso::all();
        $casos = DB::table("casos")
        ->join("ciudades", "casos.id_ciudad", "=", "ciudades.id")
        ->join("paises", "ciudades.id_pais", "=", "paises.id")
        ->select("casos.activos","casos.recuperados","casos.muertos",
                "casos.fecha","casos.id","ciudades.nombre_ciudad",
                "paises.nombre_pais")
        ->get();

        $json = array(
            "status"=>200,
            "total_registros"=>count($casos),
            "detalles"=>$casos
        );

        return json_encode($casos, true);
    }

    public function store(Request $request)
    {
        $datos = array("activos" => $request->input("activos"),
                        "recuperados" => $request->input("recuperados"),
                        "muertos" => $request->input("muertos"),
                        "fecha" => $request->input("fecha"),
                        "id_ciudad" => $request->input("id_ciudad")
        );

        $validator = Validator::make($datos,[
            "activos" => "required",
            "recuperados" => "required",
            "muertos" => "required",
            "fecha" => "required",
            "id_ciudad" => "required"
        ]);

        if($validator ->fails())
        {
            $json = array(
                "detalles"=>"registro no validado"
            );
        }
        else
        {
            $activos = $datos["activos"];
            $recuperados = $datos["recuperados"];
            $muertos = $datos["muertos"];
            $fecha = $datos["fecha"];
            $id_ciudad = $datos["id_ciudad"];

            $caso = new Caso();
            $caso->activos = $activos;
            $caso->recuperados = $recuperados;
            $caso->muertos = $muertos;
            $caso->fecha = $fecha;
            $caso->id_ciudad = $id_ciudad;

            $json = array(
                "status"=>200,
                "activos"=>$activos,
                "recuperados"=>$recuperados,
                "muertos"=>$muertos,
                "fecha"=>$fecha,
                "id_ciudad"=>$id_ciudad
            );

            $caso->save();
        }
        return json_encode($json, true);
    }
    
    public function update($id, Request $request)
    {
        $caso = Caso::where("id", $id)->get();

        if(!empty($caso))
        {
            $datos = array("activos" => $request->input("activos"),
                        "recuperados" => $request->input("recuperados"),
                        "muertos" => $request->input("muertos"),
                        "fecha" => $request->input("fecha"),
                        "id_ciudad" => $request->input("id_ciudad")
            );

            $validator = Validator::make($datos,[
                "activos" => "required",
                "recuperados" => "required",
                "muertos" => "required",
                "fecha" => "required|timestamp",
                "id_ciudad" => "required"
            ]);

            $caso = Caso::where("id",$id)->update($datos);

            $json = array(
                "status"=>200,
                "detalle"=>"Caso actualizado"
            );
        }
        else
        {
            $json = array(
                "detalle" => "El id del caso no existe"
            );
        }
        return json_encode($json, true);
    }
    
    /*public function update(Request $request, Pais $pais)
    {
        $pais->update($request->all());
    }*/

    public function show($id, Request $request)
    {
        $caso = Caso::where("id", $id)->get();

        if(!empty($caso))
        {
            $json = array(
                "status"=>200,
                "detalle"=>$caso
            );
        }
        else
        {
            $json = array
            (
                "status"=>200,
                "detalles"=>"No hay caso registrado"
            );
        }
        return json_encode($caso, true);
    }

    public function destroy($id, Request $request)
    {
        $caso = Caso::where("id", $id)->get();

        if(!empty($caso))
        {
            $pais = Caso::where("id", $id)->delete(); 
            $json = array(
                "status"=>200,
                "detalle"=> "Caso eliminada"
            );
        }
        else
        {
            $json = array
            (
                "status"=>200,
                "detalles"=>"No hay casp registrado"
            );
        }
        return json_encode($json, true);
    }
}
