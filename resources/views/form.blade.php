@extends('layout')

@section('content')
    @parent

    @include('form_popup')

    <div id="app">
        <form-list></form-list>
    </div>

@endsection
