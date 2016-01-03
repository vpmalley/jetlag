@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.user">
@endsection

@section('head')
@endsection

@section('content')
<div id="jl_user" class="container">
	<div class="user_header row">
		<div class="user_profile col-sm-3">
		lol
		</div>
		<div class="user_ban col-sm-9">
		lol
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">{{$name}}'s public information</div>
				<div class="panel-body">

          <div class="form-horizontal">
            <div class="form-group">
              <label class="col-md-4 control-label">Public name</label>
              <div class="col-md-6">
                <label class="col-md-4 control-label">{{$name}}</label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label">Country</label>
              <div class="col-md-6">
                <label class="col-md-4 control-label">{{$country}}</label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label">City</label>
              <div class="col-md-6">
                <label class="col-md-4 control-label">{{$city}}</label>
              </div>
            </div>
          </div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="//localhost/jetlag/Laravel/public/js/user.js"></script>
@endsection
