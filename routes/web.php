<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Models\Devs;

Route::get('/', function () {
    $a = request('a');
    $a = request()->only(['a', 'b', 'd', 'c']);
    dd($a);

    //return 'hello world!!!';
});


Route::group(['prefix' => 'admin'], function () {
    Route::get('users/{id}/{nome}', function ($id, $nome) {
        return $id . ' - ' . $nome;
    })->where(['id' => '[0-9]+', 'nome' => '[A-Za-z]+']);


    Route::match(['get', 'post'], 'devs', function () {
        return 'devs';
    });
});

//Route::resource('dev', 'DevController', ['as' => 'route_n']);


Route::resource('dev', 'DevController')->parameters([
    'dev' => 'id'
]);

Route::resource('post', 'PostController')->parameters([
    'post' => 'id'
]);

Route::post('devs', function () {
    $json = request()->json()->all();
    $messages = [
        'github_username.required' => 'Campo obrigatório',
        'github_username.unique' => 'username já cadastrado por outro usuário',
        'github_username.max' => 'O campo deverá conter no máximo :max',
        'nome.required' => 'Campo obrigatório'
    ];


    $validacao = Validator::make($json, [
        'github_username' => 'required|unique:devs|max:191',
        'nome' => 'required',
    ], $messages);

    if ($validacao->fails()) {
        return response()->json($validacao->errors(), 400);
    }

    DB::enableQueryLog();
    $devs = Devs::create($json);

    $devs->save();
    dd(DB::getQueryLog());
});

Route::get('devs', function () {
    $devs = Devs::all();

    //$devs->makeHidden(['nome']);
    $devs->makeVisible(['created_at']);

    return $devs;
});

Route::put('devs/{id}', function ($id) {
    $json = request()->only(['nome', 'github_username']);

    $messages = [
        'github_username.required' => 'Campo obrigatório',
        'github_username.unique' => 'username já cadastrado por outro usuário',
        'github_username.max' => 'O campo deverá conter no máximo :max',
        'nome.required' => 'Campo obrigatório'
    ];


    $validacao = Validator::make($json, [
        'github_username' => 'required|unique:devs,github_username,' . $id . '|max:191',
        'nome' => 'required',
    ], $messages);

    if ($validacao->fails()) {
        return response()->json($validacao->errors(), 400);
    }

    //dd($json);
    //return;
    $devs = Devs::find($id);

    if (!$devs) {
        return response()->json(['error' => 'No Exists'], 404);
    }


    $devs->update($json);
    $devs->save();

    return response()->json($devs);
    //return $devs;
});


Route::delete('devs/{id}', function ($id) {

    //dd($json);
    //return;
    $devs = Devs::find($id);

    if (!$devs) {
        return response()->json(['error' => 'No Exists'], 404);
    }


    $devs->delete();
    return response()->json(['ok' => 'removido']);
    //$devs->save();

    //return response()->json($devs);
    //return $devs;
});

Route::resource('dev-tech', 'DevTechsController')->parameters([
    'dev-tech' => 'id'
])->middleware('dev-tech');


// Query params ?para=para
// Route params meusite.com.br/admin/produto/1/
// body params
