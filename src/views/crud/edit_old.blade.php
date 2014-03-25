@extends('admin::layout.main')

@section('content')

<h1>Edit My entity</h1>
<p>Ho yeah ! paf !!</p>

@if ($errors->any())
<ul>
    {{ implode('', $errors->all('<li class="error">:message</li>')) }}
</ul>
@endif

{{ $form->open }}

@foreach ($form->elements as $element)
    {{ $element }}
@endforeach

<p>
    {{ $form->buttons }}
</p>

{{ $form->close }}

@stop