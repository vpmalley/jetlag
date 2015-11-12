@extends('app')

@section('content')
<div class="container-fluid homepage">
	<div class="row">
		<div class="col-xs-12">
			<div class="searchbar_banner">
				<div class="searchbar">
					<input type="text" class="form-control" placeholder="Rechercher"/>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
		Favorite 1
		</div>
		<div class="col-xs-6">
		Favorite 2
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3 col-xs-6">
		Random 1
		</div>
		<div class="col-sm-3 col-xs-6">
		Random 2
		</div>
		<div class="col-sm-3 col-xs-6">
		Random 3
		</div>
		<div class="col-sm-3 col-xs-6">
		Random 4
		</div>
	</div>
	<!--
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>
				@if (Auth::guest())
				<div class="panel-body">
					Why not <a href="{{ url('/auth/login') }}">loggin' in</a> or <a href="{{ url('/auth/register') }}">registering</a> ?
				</div>
				@else
				<div class="panel-body">
					You are logged in!
				</div>
				@endif
			</div>
		</div>
	</div>
	-->
</div>
@endsection
