<?php

use \Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;


Route::get('/', function () {
    return view('form');
})->name('homepage');


Route::get('/form', function () {
    return \App\Models\Form::all();
})->name('form.index');


Route::get('/file/{file}', function (Request $request, \App\Models\File $file) {
    return response()->streamDownload(function () use ($file) {
        echo $file->data;
    }, $file->filename, [
        'Content-type' => [$file->mime],
        'Content-Disposition' => 'filename=' . $file->filename,
    ]);
})->name('file.get');


Route::get('/form/{form}', function (Request $request, \App\Models\Form $form) {
    return $form->loadMissing('files');
})->name('form.view');


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

    $form = \Illuminate\Support\Facades\DB::transaction(function () use ($name, $files) {
        $form = new \App\Models\Form();
        $form->name = $name;
        $form->save();
        foreach ($files as $file) {
            /** @var \Illuminate\Http\UploadedFile $file */
            $form->files()->create([
                'data' => $file->get(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'filename' => $file->getClientOriginalName(),
            ]);
        }
        return $form;
    });
    return $form;
})->name('form.create');
