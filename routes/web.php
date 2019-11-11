<?php
use \Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;

Route::get('/', function () {

    return view('form');


})->name('form.index');


Route::post('/', function (Request $request) {
    $c = $request->all();
    $c = 1;
})->name('form.create');
