@extends('layouts.main')

@section('head')
@section('title')
<title>Employee Dashboard</title>
@endsection
<style>
				
	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}
	th, td {
		padding: 5px;
		text-align: left;    
	}
	
	.total td{
		text-decoration: underline;
	}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
@endsection

@section('content')
<table id="employees_table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>id</th>
                <th>login</th>
                <th>name</th>
                <th>salary</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>login</th>
                <th>name</th>
                <th>salary</th>
            </tr>
        </tfoot>
    </table>
@endsection

@section('scripts')
<script type="text/javascript" charset="utf8" src="{{ asset('js/jquery.dataTables-1.10.21.js') }}"></script>

<script>
$(document).ready( function () {
    $('#employees_table').DataTable({
		//serverSide: true,
		/*
    	"ajax": {
			"url": "/users?minSalary=0&maxSalary=10000000&offset=0&limit=30&sort=-name",
			"dataSrc": "",
			"columns": [
				{"id"},
			]
		},
		*/
		
    	"ajax": {
			"url": "/users?minSalary=0&maxSalary=10000000&offset=0&limit=30&sort=-name",
			"dataSrc": "",
			mDataProp: ""
		},
	
		aoColumns: [
			{ mData: 'id' },
			{ mData: 'login' },
			{ mData: 'name' },
			{ mData: 'salary' }
		],
		autofill: true,
		order: [[0, 'asc']],
		responsive: true,
		buttons: true,
		pageLength: 30,
	
		
	});
} );	
</script>
@endsection