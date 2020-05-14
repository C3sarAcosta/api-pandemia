<?php

namespace App\Http\Controllers;

use App\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CiudadesController extends Controller
{
    public function index()
    {
        //$ciudades = Ciudad::all();
        $ciudades = DB::table("ciudades")
        ->join("paises", "ciudades.id_pais", "=", "paises.id")
        ->select("ciudades.id","ciudades.nombre_ciudad","ciudades.habitantes_ciudad",
                "ciudades.id_pais", "paises.nombre_pais")
        ->get();

        $json = array(
            "status"=>200,
            "total_registros"=>count($ciudades),
            "detalles" => $ciudades
        );

        return json_encode($ciudades, true);
    }

    public function store(Request $request)
    {
        //Recoger los datos
        $datos = array("nombre_ciudad" => $request->input("nombre_ciudad"),
                        "habitantes_ciudad" => $request->input("habitantes_ciudad"),
                        "id_pais" => $request->input("id_pais"));

        //Validar datos
        $validator = Validator::make($datos, [
            "nombre_ciudad" => "required|string|max:255",
            "habitantes_ciudad" => "required",
            "id_pais" => "required"
        ]);

        if($validator -> fails())
        {
            $json = array(
                "detalles"=>"registro no validado"
            );

            return json_encode($json, true);
        }
        else
        {
            $nombre_ciudad = $datos["nombre_ciudad"];
            $habitantes_ciudad = $datos["habitantes_ciudad"];
            $id_pais = $datos["id_pais"];

            $ciudad = new Ciudad();
            $ciudad->nombre_ciudad = $nombre_ciudad;
            $ciudad->habitantes_ciudad = $habitantes_ciudad;
            $ciudad->id_pais = $id_pais;

            $json = array(
                "status"=>200,
                "nombre_ciudad"=>$nombre_ciudad,
                "habitantes_ciudad"=>$habitantes_ciudad,
                "id_pais"=>$id_pais
            );

            $ciudad->save();

            return json_encode($json, true);
        }
    }

    public function show($id, Request $request)
    {
        $ciudad = Ciudad::where("id", $id)->get();

        if(!empty($ciudad))
        {
            $json = array(
                "status"=>200,
                "detalle"=>$ciudad
            );
        }
        else
        {
            $json = array
            (
                "status"=>200,
                "detalles"=>"No hay ciudad registrada"
            );
        }
        return json_encode($ciudad, true);
    }

    public function update($id, Request $request)
    {
        $ciudad = Ciudad::where("id", $id)->get();

        if(!empty($ciudad))
        {
            $datos = array("nombre_ciudad"=>$request->input("nombre_ciudad"),
                            "habitantes_ciudad"=>$request->input("habitantes_ciudad"),
                            "id_pais"=>$request->input("id_pais")
            );

            $validator = Validator::make($datos, [
                "nombre_ciudad" => "required|string|max:255",
                "habitantes_ciudad" => "required",
                "id_pais" => "required"
            ]);

            $ciudad = Ciudad::where("id",$id)->update($datos);

            $json = array(
                "status"=>200,
                "detalle"=>"Ciudad actualizada"
            );
        }
        else
        {
            $json = array(
                "detalle" => "El id de la ciudad no existe"
            );
        }
        return json_encode($json, true);
    }

    public function destroy($id, Request $request)
    {
        $ciudad = Ciudad::where("id", $id)->get();

        if(!empty($ciudad))
        {
            $pais = Ciudad::where("id", $id)->delete(); 
            $json = array(
                "status"=>200,
                "detalle"=> "Ciudad eliminada"
            );
        }
        else
        {
            $json = array
            (
                "status"=>200,
                "detalles"=>"No hay ciudad registrada"
            );
        }
        return json_encode($json, true);
    }
}
