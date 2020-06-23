@extends('layouts.main')

@section('head')
@section('title')
<title>Techhunt2020</title>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
@endsection

@section('content')
<p>Welcome to this employee homepage.</p>

<p> There are 2 main features </p>
<ul>
<li><a href="{{ url('/users/upload') }}">Upload</a></li>
<li><a href="{{ url('/users/dashboard') }}">Dashboard</a></li>
</ul>
@endsection