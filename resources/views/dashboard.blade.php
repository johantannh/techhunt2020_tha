@extends('layouts.main')

@section('head')
@section('title')
<title>Employee Dashboard</title>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
@endsection

@section('content')
<form id="salRangeSelection">
  <div class="row">
    <div class="col">
		<label class="control-label">Min Salary($): </label>
        <input id="minSalInput" type="text" class="form-control" placeholder="1000">
	</div>
    <div class="col">
		<label class="control-label">Max Salary($): </label>
        <input id="maxSalInput" type="text" class="form-control" placeholder="10000">
	</div>
	<button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>

<hr />

<table id="employees_table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>id</th>
                <th>login</th>
                <th>name</th>
				<th>salary</th>
				<th>actions</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>login</th>
                <th>name</th>
				<th>salary</th>
				<th>actions</th>
            </tr>
        </tfoot>
    </table>
@endsection

@section('scripts')
<script type="text/javascript" charset="utf8" src="{{ asset('js/jquery.dataTables-1.10.21.js') }}"></script>

<script>
$(document).ready( function () {
	var minSal = 0;
	var maxSal = 10000000;
    var table = $('#employees_table').DataTable({
		processing: true,
		serverSide: true,
		
		"ajax": {
			"url": "/users/getdashboarddata?minSalary="+minSal+"&maxSalary="+maxSal+"&offset=0&limit=30&sort=-name",
			"data": function( d ) {
        		return d;
    		}
		},
		
		"columns": [
			{ "data": "id" },
			{ "data": "login" },
			{ "data": "name" },
			{ "data": "salary" },
			{ render: function(data, type) {
				return '<a href=""><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>&nbsp&nbsp<a href=""><i class="fas fa-trash-alt" aria-hidden="true"></i></a>';
				} 
			}
		],
		autofill: true,
		lengthChange: false,
		order: [[0, 'asc']],
		pageLength: 30,
		paging: true,
		responsive: true,
	});

	$( "#salRangeSelection" ).submit(function( event ) {
		minSal = $( "#minSalInput" ).val();
		maxSal = $( "#maxSalInput" ).val();
		table.ajax.url( "/users/getdashboarddata?minSalary="+minSal+"&maxSalary="+maxSal+"&offset=0&limit=30&sort=-name" ).load();
		event.preventDefault();
	});
});
</script>
@endsection