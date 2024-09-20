@extends('layouts.default')

@section('title')
	Manage Admins
	@parent
@stop

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">

	<div class="row">
		<div class="col-lg-6 col-md-8">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Manage Admins</h3>
				</div>

				
				
				<div class="box-body table-responsive ">

					{{-- @include('shared.errors') --}}

					<table class="table table-striped table-hover">
						<tr>
							
							<th>Full Name</th>
							<th>Email</th>
							<th>Type</th>
							
							<th class="text-center">Action</th>
						</tr>
						@foreach($users as $admin)
							
								<tr data-delete="{{ route('admin.users.destroy', $admin) }}">

									<td>{{ $admin->name }}</td>
									<td>{{ $admin->email }}</td>
									<td>{{ ucfirst($admin->type) }}</td>
									<td class="text-center">
										{{-- @if(strtolower($admin->type)=='admin') --}}
										<span class="">
											<a href="{{ route('admin.users.edit', $admin) }}"  data-toggle="tooltip" data-html="true"  data-placement="top" title="Edit" class="mr-2" ><i class="fa fa-edit"></i></a>
											<a href="#"  data-toggle="tooltip" data-html="true"  data-placement="top" title="Delete"  data-delete-trigger><i class="fa fa-trash"></i></a>
											
										</span>
										{{-- @endif --}}
									</td>
								</tr>
							
						@endforeach
					</table>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
	</div>

	<a class="btn btn-primary" href="{{ route('admin.users.create') }}">Add Admin</a>

		</div>
	</div>

@stop
