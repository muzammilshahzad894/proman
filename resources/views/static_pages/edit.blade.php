@extends('layouts.default')

@section('title','Edit Static Page')
@section('stylesheets')
	<link rel="stylesheet" href="{{ URL::asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ URL::asset('plugins/datatables/dataTables.bootstrap.css') }}">
@stop

@section('javascripts')
	<!-- FastClick -->
	<script src="{{ URL::asset('plugins/fastclick/fastclick.js') }}"></script>

	<!-- Bootstrap WYSIHTML5 -->
	<script src="{{ URL::asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>

	<script>
		$(function () {
			//bootstrap WYSIHTML5 - text editor
			$(".textarea").wysihtml5();
		});
	</script>

	<!-- DataTables -->
	<script src="{{ URL::asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>

	<!-- SlimScroll -->
	<script src="{{ URL::asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>

	<!-- page script -->
	<script>
		$(function () {
			$("#files-data-table").DataTable();

//          WITH OPTIONS

//			$('#files-data-table').DataTable({
//				"paging": true,
//				"lengthChange": false,
//				"searching": true,
//				"ordering": true,
//				"info": true,
//				"autoWidth": false
//			});
		});
	</script>
@stop

@section('content')
<div class="content-wrapper">
<!-- Main content -->
  <section class="content">

	<div class="row">

		<div style="display:none" class="col-md-8">

			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Uploaded Files</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<table id="files-data-table" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Display order</th>
								<th>Name</th>
								<th>Last modified</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($staticPage->files as $file)
								<tr data-delete="{{ route('static_page.destroy_file', [
										'staticPage' => $staticPage->id,
										'file_id' => $file->id
									])
								}}">
									<td>{{ $file->file_display_order }}</td>
									<td>{{ $file->fullName() }}</td>
									<td>{{ $file->updated_at }}</td>
									<td class="text-center">
										<a data-delete-trigger class="btn btn-danger btn-xs" href="#">Delete</a>
									</td>
								</tr>
							@endforeach
						</tbody>
						{{--<tfoot>--}}
							{{--<tr>--}}
								{{--<th>Rendering engine</th>--}}
								{{--<th>Browser</th>--}}
								{{--<th>Platform(s)</th>--}}
								{{--<th>Engine version</th>--}}
								{{--<th>CSS grade</th>--}}
							{{--</tr>--}}
						{{--</tfoot>--}}
					</table>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>

		<form action="{{ route('static_page.update', $staticPage) }}" method="post" enctype="multipart/form-data">

			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="PATCH">

			{{--<!-- text input -->--}}
			{{--<div class="box">--}}
				{{--<div class="box-header">--}}
					{{--<h3 class="box-title">Page Title</h3>--}}
				{{--</div>--}}
				{{--<div class="box-body">--}}
					{{--<input type="text" class="form-control input-lg" placeholder="Title">--}}
				{{--</div>--}}
			{{--</div>--}}

			<!-- general form elements -->
			<div  style="display:none"  class="col-md-4">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">File Upload</h3>
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					<div class="box-body">
						<div class="form-group {{ $errors->has('file') ? 'has-error' : '' }}">
							<label for="exampleInputFile">File input</label>
							<input name="file" type="file" accept="{{ allowedImageTypes() }}" id="exampleInputFile">

							@foreach($errors->get('file') as $message)
								<span class="help-block text-danger">{{ $message }}</span>
							@endforeach
						</div>

						<div class="form-group {{ $errors->has('file_name') ? 'has-error' : '' }}">
							<label for="file_name">Filename</label>
							<input name="file_name" id="file_name" type="text" class="form-control"
									placeholder="Banner, Apple, etc.."
									value="{{ old('file_name') }}">

							@foreach($errors->get('file_name') as $message)
								<span class="help-block">{{ $message }}</span>
							@endforeach
						</div>

						<div class="form-group {{ $errors->has('file_display_order') ? 'has-error' : '' }}">
							<label for="file_display_order">Display order</label>
							<input name="file_display_order" id="display_order" type="number" class="form-control"
									placeholder="1, 5, 8, etc.."
									value="{{ old('file_display_order') }}">

							@foreach($errors->get('file_display_order') as $message)
								<span class="help-block">{{ $message }}</span>
							@endforeach
						</div>
					</div>
					<!-- /.box-body -->
				</div>

			</div>

			<div class="col-md-12">

				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Page Content: {{$staticPage['title']}}</h3>
						<!-- tools box -->
						{{--<div class="pull-right box-tools">--}}
							{{--<button type="button" class="btn btn-default btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">--}}
								{{--<i class="fa fa-minus"></i></button>--}}
							{{--<button type="button" class="btn btn-default btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">--}}
								{{--<i class="fa fa-times"></i></button>--}}
						{{--</div>--}}
						<!-- /. tools -->
					</div>
					<!-- /.box-header -->


					<div class="box-body pad">
						{{--<div class="form-group">--}}
							{{--<input type="text" class="form-control input-lg" placeholder="Title">--}}
						{{--</div>--}}

						<div class="form-group">
							<textarea name="content" class="mceEditor" placeholder="Place some text here"
									style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
							>{{ old('content') ? old('content') : $staticPage['content'] }}</textarea>
						</div>

					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div style="display: none;" class="box">
					<div class="box-header">
						<h3 class="box-title">Meta Information
							<small>This information is needed for Google and other search engines</small>
						</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label for="static-meta-title">Title</label>
							<input name="meta_title" id="static-meta-title"
									type="text" class="form-control" placeholder="Title"
									value="{{ old('meta_title') ? old('meta_title') : $staticPage['meta_title'] }}">
						</div>

						<div class="form-group">
							<label for="static-meta-description">Description</label>
							<input name="meta_description" id="static-meta-description"
									type="text" class="form-control" placeholder="Description"
									value="{{ old('meta_description') ? old('meta_description') : $staticPage['meta_description'] }}">
						</div>

						<div class="form-group">
							<label for="static-meta-keywords">Keywords</label>
							<input name="meta_keywords" id="static-meta-keywords"
									type="text" class="form-control" placeholder="Keywords"
									value="{{ old('meta_keywords') ? old('meta_keywords') : $staticPage['meta_keywords'] }}">
						</div>
					</div>
				</div>

				{{--<div class="box-footer">--}}
				<button type="submit" class="btn btn-primary">Save</button>
				{{--</div>--}}
			</div>

		</form>

	</div>
</section>
</div>
	<!-- ./row -->
@stop
