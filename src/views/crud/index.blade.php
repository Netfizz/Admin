@extends('admin::layout.main')

@section('content')

<h1>My entity</h1>
<p>hey paf !!</p>

{{ Datatable::table()
->addColumn('id','body', 'author')       // these are the column headings to be shown
->setUrl(route('api.tweets'))   // this is the route where data will be retrieved
->render() }}

@stop