@extends('layout')

@section('content')
    @parent

    @include('form_popup')

    <div id="app">
        <form-list
                list-url="{{ route('form.index') }}"
        ></form-list>
    </div>

@endsection
