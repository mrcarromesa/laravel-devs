<?php

namespace App\Http\Controllers;

use App\Http\Models\Devs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DevTechsController extends Controller
{

    private function getDevsTechsPorStatus($status)
    {
        $devs = Devs::whereHas('techs', function ($query) use ($status) {
            return $query->where('status', $status);
        })->get();

        return $devs;
    }

    private function getDevsTechsPorNotStatus($status)
    {
        DB::enableQueryLog();


        $devs = Devs::whereDoesntHave('techs', function ($query) use ($status) {
            return $query->where('status', $status);
        })->get();

        Log::alert('Select ', DB::getQueryLog());

        return $devs;
    }

    private function getComplexoDevTechsPorDev(Devs $dev, $status)
    {
        DB::enableQueryLog();
        $dev_tech = $dev->techs()->wherePivot('status', $status)->withPivot('status')->get();
        Log::alert('Select ', DB::getQueryLog());

        return $dev_tech;
    }

    private function getDevsComTechs()
    {
        $devs = Devs::with(['techs', 'posts'])->get();
        return $devs;
    }

    private function getDevsComTechsFiltro($status)
    {

        DB::enableQueryLog();
        $devs = Devs::whereHas('techs', function ($techs) use ($status) {
            return $techs->where('status', $status);
        })->with(['techs' => function ($techs) use ($status) {
            return $techs->wherePivot('status', $status)->withPivot('status')->orderBy('nome', 'desc');
        }, 'posts'])->get();
        Log::alert('Select ', DB::getQueryLog());

        return $devs;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = request('status');
        $id = request('id');
        //return $this->getDevsTechsPorStatus($status);
        // return $this->getDevsTechsPorNotStatus($status);

        //$dev = Devs::find($id);
        //return $this->getComplexoDevTechsPorDev($dev, $status);

        return $this->getDevsComTechsFiltro($status);
        // return $this->getDevsComTechs();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = $request->only(['nome', 'github_username']);
        $json_techs = $request->input('techs');

        $dev = Devs::create($json);
        $dev->techs()->attach($json_techs);
        $dev->save();

        return $dev;
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
    public function update(Request $request, $id)
    {
        // []
        $json = $request->input('techs');
        $dev = Devs::find($id);

        if (!$dev) {
            return response()->json(['error' => 'Not found.'], 404);
        }

        $dev->techs()->syncWithoutDetaching($json);

        /*
        foreach ($json as $key => $tech) {
            $dev->techs()->updateExistingPivot($key, $tech);
        }
        */
        //$dev->save();

        return $dev;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $json = request()->input('techs');

        $dev = Devs::find($id);

        if (!$dev) {
            return response()->json(['erro' => 'Not found'], 400);
        }

        $dev->techs()->detach($json);
        $dev->save();

        return response()->json(['ok', 'Techs removidas']);
    }
}
