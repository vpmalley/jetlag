@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.user">
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default" style="margin-top: 20px">
				<div class="panel-heading">You can edit your public information</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
          
					@if ($saved)
						<div class="alert alert-success">
							Your changes have been saved.
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/traveller/' . $id . '/edit') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Public name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{$name}}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Country</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="country" value="{{$country}}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">City</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="city" value="{{$city}}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Profile picture url</label>
							<div class="col-md-6">
								<input type="url" class="form-control" name="Profile picture">
							</div>
						</div>
            
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="jl-btn">
									Save
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/user.js') }}"></script>
@endsection
