<?php

use \Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;

Route::get('/', function () {
    return view('form');
})->name('form.index');


Route::post('/', function (Request $request) {
    $data = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'name' => ['sometimes', 'nullable', 'string'],
        'file' => ['sometimes', 'array'],
        'file.*' => ['sometimes', 'file'],
    ])->validate();

    $name = $data['name'] ?? null;
    $files = $data['file'] ?? [];

    if (empty($name) && empty($files)) {
        return null;
    }

    $form = \Illuminate\Support\Facades\DB::transaction(function() use ($name, $files) {
        $form = new \App\Models\Form();
        $form->name = $name;
        $form->save();
        foreach ($files as $file) {
            /** @var \Illuminate\Http\UploadedFile $file */
            $form->files()->create([
                'data' => $file->get(),
                //'target_id' target_type
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'filename' => $file->getClientOriginalName(),
            ]);
        }
        return $form;
    });


    return $form;
})->name('form.create');
