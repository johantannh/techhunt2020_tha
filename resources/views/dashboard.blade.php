@extends('layouts.main')

@section('head')
@section('title')
<title>Employee Dashboard</title>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
@endsection

@section('content')
<button id="new-item" type="submit" class="btn btn-success">New Employee</button>

<hr />
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

<!-- Edit Employee Modal -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit-modal-label">Edit Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="attachment-body-content">
        <form id="edit-form" class="form-horizontal" method="POST" action="#">
			@csrf
			<input name="_method" type="hidden" value="PATCH">
          <div class="card text-white bg-dark mb-0">
            <div class="card-header">
              <h2 class="m-0">Edit</h2>
            </div>
            <div class="card-body">
              <!-- id -->
              <div class="form-group">
                <label class="col-form-label" for="modal-input-id">Id</label>
                <input type="text" name="edit-id" class="form-control" id="modal-input-id" required>
              </div>
              <!-- /login -->
              <!-- name -->
              <div class="form-group">
                <label class="col-form-label" for="modal-input-login">Login</label>
                <input type="text" name="login" class="form-control" id="modal-input-login" required autofocus>
              </div>
              <!-- /login -->
              <!-- name -->
              <div class="form-group">
                <label class="col-form-label" for="modal-input-name">Name</label>
                <input type="text" name="name" class="form-control" id="modal-input-name" required>
              </div>
              <!-- /name -->
			  <!-- salary -->
              <div class="form-group">
                <label class="col-form-label" for="modal-input-salary">Salary</label>
                <input type="text" name="salary" class="form-control" id="modal-input-salary" required>
              </div>
              <!-- /salary -->
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button id="updateBtn" type="button" class="btn btn-primary" data-dismiss="modal">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- /Edit Employee Modal -->

<!-- New Employee Modal -->
<div class="modal fade" id="new-modal" tabindex="-1" role="dialog" aria-labelledby="new-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="new-modal-label">New Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="attachment-body-content">
        <form id="new-form" class="form-horizontal" method="POST" action="#">
			@csrf
          <div class="card text-white bg-dark mb-0">
            <div class="card-body">
              <div class="form-group">
                <label class="col-form-label" for="new-id">Id</label>
                <input type="text" name="id" class="form-control" id="new-id" required>
              </div>
              <div class="form-group">
                <label class="col-form-label" for="new-login">Login</label>
                <input type="text" name="login" class="form-control" id="new-login" required autofocus>
              </div>
              <div class="form-group">
                <label class="col-form-label" for="new-name">Name</label>
                <input type="text" name="name" class="form-control" id="new-name" required>
              </div>
              <div class="form-group">
                <label class="col-form-label" for="new-salary">Salary</label>
                <input type="text" name="salary" class="form-control" id="new-salary" required>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button id="newBtn" type="button" class="btn btn-primary" data-dismiss="modal">Create</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- /New Employee Modal -->
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
			{ "data": "id",
			render: function(data, type) {
				var editItem = '<a id="edit-item" data-item-id="'+ data
								+ '" href="#"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>';

				var deleteItem = '<a id="delete-item" data-item-id="'+ data
								+ '" href="#"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>';
				
				return editItem+'&nbsp&nbsp'+deleteItem;
				} 
			}
		],
		autofill: true,
		lengthChange: false,
		order: [[0, 'asc']],
		pageLength: 30,
		paging: true,
		responsive: true,
		searching: false,
	});

	$(document).on('click', "#new-item", function() {
		$(this).addClass('new-item-trigger-clicked'); 
		var options = {
		'backdrop': 'static'
		};
		$('#new-modal').modal(options)
	});

	// on modal hide
	$('#new-modal').on('hide.bs.modal', function() {
		//$('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked')
		$("#new-form").trigger("reset");
	});

	$("#newBtn").click(function(e){
		e.preventDefault();

		//$("#edit-form").attr('action', "/users/"+emp_id).submit();
		$("#new-form").submit();
	});

	$("#new-form").submit(function(e){
		e.preventDefault();
		var form_data = $("#new-form").serializeArray();
		var emp_id = "";
		form_data.forEach(item => {
			if(item.name === "id"){
				emp_id = item.value;
			}
		});
	
		$.ajax({
			url: "/users/"+emp_id, 
			type: "POST",
			data: form_data,
			success: function(result){
				$('div.flash-message').html(
					'<div class="alert alert-success alert-block">' +
						'<button type="button" class="close" data-dismiss="alert">×</button>' +
						'<strong>Employee '+emp_id+' created successfully</strong>' +
					'</div>'
				);
				table.ajax.url( "/users/getdashboarddata?minSalary="+minSal+"&maxSalary="+maxSal+"&offset=0&limit=30&sort=-name" ).load();
			},
			failure: function(result){
				$('div.flash-message').html(
					'<div class="alert alert-error alert-block">' +
						'<button type="button" class="close" data-dismiss="alert">×</button>' +
						'<strong>Employee '+emp_id+' creation failed</strong>' +
					'</div>'
				);
				table.ajax.url( "/users/getdashboarddata?minSalary="+minSal+"&maxSalary="+maxSal+"&offset=0&limit=30&sort=-name" ).load();
			}
		});
	});

	$( "#salRangeSelection" ).submit(function( event ) {
		minSal = $( "#minSalInput" ).val();
		maxSal = $( "#maxSalInput" ).val();
		table.ajax.url( "/users/getdashboarddata?minSalary="+minSal+"&maxSalary="+maxSal+"&offset=0&limit=30&sort=-name" ).load();
		event.preventDefault();
	});

	$(document).on('click', "#edit-item", function() {
		$(this).addClass('edit-item-trigger-clicked'); 
		var options = {
		'backdrop': 'static'
		};
		$('#edit-modal').modal(options)
	});

	// on modal show
	$('#edit-modal').on('show.bs.modal', function() {
		var el = $(".edit-item-trigger-clicked");
		var row = el.closest("tr");

		// get the data
		var id = el.data('item-id');
		var login = row.children("td").eq(1).text();
		var name = row.children("td").eq(2).text();
		var salary = row.children("td").eq(3).text();

		// fill the data in the input fields
		$("#modal-input-id").val(id);
		$("#modal-input-login").val(login);
		$("#modal-input-name").val(name);
		$("#modal-input-salary").val(salary);

	});

	// on modal hide
	$('#edit-modal').on('hide.bs.modal', function() {
		$('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked')
		$("#edit-form").trigger("reset");
	});

	$("#updateBtn").click(function(e){
		e.preventDefault();

		//$("#edit-form").attr('action', "/users/"+emp_id).submit();
		$("#edit-form").submit();
	});

	$("#edit-form").submit(function(e){
		e.preventDefault();
		var form_data = $("#edit-form").serializeArray();
		var emp_id = "";
		form_data.forEach(item => {
			if(item.name === "edit-id"){
				emp_id = item.value;
			}
		});
	
		$.ajax({
			url: "/users/"+emp_id, 
			type: "POST",
			data: form_data,
			success: function(result){
				$('div.flash-message').html(
					'<div class="alert alert-success alert-block">' +
						'<button type="button" class="close" data-dismiss="alert">×</button>' +
						'<strong>Employee '+emp_id+' updated successfully</strong>' +
					'</div>'
				);
				table.ajax.url( "/users/getdashboarddata?minSalary="+minSal+"&maxSalary="+maxSal+"&offset=0&limit=30&sort=-name" ).load();
			}
		});
	});

	$(document).on('click', "#delete-item", function() {
		emp_id = $(this).data('item-id');
		deleteConfirm = confirm('Are you sure you want to delete Employee '+emp_id+'?');
		if(deleteConfirm){
			$.ajax({
				url: "/users/"+emp_id, 
				type: "POST",
				data: {
					"_token":"{{csrf_token()}}",
					"_method": "DELETE"
				},
				success: function(result){
					$('div.flash-message').html(
						'<div class="alert alert-success alert-block">' +
							'<button type="button" class="close" data-dismiss="alert">×</button>' +
							'<strong>Employee '+emp_id+' has been deleted</strong>' +
						'</div>'
					);
					table.ajax.url( "/users/getdashboarddata?minSalary="+minSal+"&maxSalary="+maxSal+"&offset=0&limit=30&sort=-name" ).load();
				}
			});
		} else {
			return false;
		}
	});
});
</script>
@endsection