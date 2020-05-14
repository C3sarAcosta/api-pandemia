<?php

namespace App\Http\Controllers;

use App\Pais;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaisesController extends Controller
{

    public function index()
    {
        //
        //Obtenemos todos los paises
        $paises = Pais::all();

        if(!empty($paises))
        {   
            //Creamos un arreglo con la lista de los paises
            /*$json = array
            (
                "status"=>200,
                "total_registros"=>count($paises),
                "detalles"=>$paises
            );*/
            return $paises = Pais::all();
        }
        else
        {
            //Creamos un arrglo diciendo que no hay paises registradps
            $json = array
            (
                "status"=>200,
                "total_registros"=>0,
                "detalles"=>"No hay paises registrados"
            );
        }

        //Regresamos un json con el arreglo
        return json_encode($json, true);
    }

    public function store(Request $request)
    {
        //obtenemos los datos de un json
        $datos = array("nombre_pais" => $request->input("nombre_pais"),
                        "habitantes_pais" => $request->input("habitantes_pais"));

        //Validamos los datos
        $validator = Validator::make($datos, [
            'nombre_pais' => 'required|string|max:255',
            'habitantes_pais' => 'required',
        ]);

        if($validator -> fails())
        {
            //Si la validacion falla creamos un arreglo diciendolo
            $json = array(
                "detalle" => "registro no valido"
            );
        }
        else
        {
            //Si la validacion es satisfactoria
            $nombre_pais = $datos["nombre_pais"];
            $habitantes_pais = $datos["habitantes_pais"];

            //Creamos un nuevo pais
            $pais = new Pais();
            $pais->nombre_pais = $nombre_pais;
            $pais->habitantes_pais = $habitantes_pais;

            //creamos un arreglo con los datos que se van a guardar
            $json = array(
                "status"=>200,
                "nombre_pais" => $nombre_pais,
                "habitantes_pais" => $habitantes_pais
            );

            //Guardamos el registro en la base de datos
            $pais->save();
        }     

        return json_encode($json, true);
    }

    public function show($id, Request $request)
    {
        $pais = Pais::where("id", $id)->get();

        if(!empty($pais))
        {
            return Pais::where("id", $id)->get();
        }
        else
        {
            $json = array
            (
                "status"=>200,
                "detalles"=>"No hay pais registrado"
            );
        }

        return json_encode($json, true);
    }

    /*public function show(Pais $pais)
    {
        return $pais;
    }*/

    public function update($id, Request $request)
    {
        $pais = Pais::where("id", $id)->get();
        if(!empty($pais))
        {
            $datos = array("nombre_pais"=>$request->input("nombre_pais"),
                            "habitantes_pais"=>$request->input("habitantes_pais")
            );

            $validator = Validator::make($datos, [
                'nombre_pais' => 'required|string|max:255',
                'habitantes_pais' => 'required',
                ]);

            $pais = Pais::where("id",$id)->update($datos);

            $json = array(

                "status"=>200,
                "detalle"=>"Pais actualizado"
            );
        }
        else
        {
            $json = array(
                "detalle" => "El id del pais no existe"
            );
        }

        return json_encode($json, true);
    }

    /*public function update(Request $request, Pais $pais)
    {
        $pais->update($request->all());
    }*/
   
    public function destroy($id, Request $request)
    {
        //Obtenemos el registr
        $pais = Pais::where("id", $id)->get();
        if(!empty($pais))
        {
            $pais = Pais::where("id", $id)->delete(); 

            $json = array(

                "status"=>200,
                "detalle"=>"Pais eliminado"
            );
        }
        else
        {
            $json = array(
                "detalle" => "El id del pais no existe"
            );
        }

        return json_encode($json, true);
    }
}
