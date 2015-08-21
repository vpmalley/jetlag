@extends('app')

@section('content')
<div class="container">
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
</div>
@endsection
