<?php
use \Illuminate\Support\Facades\Route;


Route::get('/', function () {

    return view('welcome');


})->name('form.index');


Route::post('/', function () {

})->name('form.create');
