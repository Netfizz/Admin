@extends('admin::layout.main')

@section('content')

<h1>My entity</h1>
<p>hey paf !!</p>

<a href="{{ controllerAction('getCreate') }}">Create</a>

{{ $datatable->render() }}

@stop