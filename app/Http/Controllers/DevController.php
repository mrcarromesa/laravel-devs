<?php

namespace App\Http\Controllers;

use App\Http\Models\Devs;
use App\Http\Requests\ValidarDev;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DevController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$devs = Devs::select()->with(['posts'])->get();
        $devs = Devs::paginate(5);
        return $devs;
        //$devs = Devs::all();
        //DB::enableQueryLog();

        //$devs = Devs::select('github_username', DB::raw('count(*) as total'))->groupBy('github_username')->get();

        //dd(DB::getQueryLog());
        //$devs->makeHidden(['nome']);
        //$devs->makeVisible(['created_at']);
        //return $devs;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.github.com/users/mrcarromesa",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,

            CURLOPT_USERAGENT => 'Awesome-Octocat-App',

            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        echo $httpcode;
        return;
        return json_decode($response, true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(ValidarDev $request)
    {
        //dd($request->json()->all());
        //$json = $request->json()->all();
        $json = $request->only(['github_username', 'nome']);
        $json_post = $request->only(['post']);
        //dd($json_post['post']);


        $devs = Devs::create($json);
        $devs->posts()->createMany($json_post['post']);

        $devs->save();

        return $devs;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ValidarDev $request, $id)
    {
        $json = $request->only(['nome', 'github_username']);


        //DB::enableQueryLog();
        $devs = Devs::find($id);
        //dd(DB::getQueryLog());

        if (!$devs) {
            return response()->json(['error' => 'No Exists'], 404);
        }


        $devs->posts()->createMany([
            ['titulo' => 'titulo alt1', 'descricao' => 'descricao1'],
            ['titulo' => 'titulo alt2', 'descricao' => '-'],
        ]);

        $devs->update($json);
        $devs->save();

        return $devs;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $devs = Devs::find($id);

        if (!$devs) {
            return response()->json(['error' => 'No Exists'], 404);
        }




        try {
            $devs->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::alert($e->getMessage());
            //Log::notice($e->getFile() . '; Linha ' . $e->getLine());
            //Log::critical($e);
            Log::critical($e->getTraceAsString());
            return response()->json(['error' => 'Não foi possível remover o registro'], 500);
        }

        return response()->json(['ok' => 'removido']);
    }
}
