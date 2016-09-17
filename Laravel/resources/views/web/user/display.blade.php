@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.user">
@endsection

@section('content')
<div class="container-fluid">
    
	<div class="row">
        <ul class="nav nav-pills" role="tablist">
          <li role="presentation" class="active"><a href="#travelbooks" aria-controls="travelbooks" role="tab" data-toggle="pill">Travelbooks</a></li>
          <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="pill">Profile</a></li>
        </ul>
    
        <div class="col-md-12 tab-content">
            <div class="row tab-pane" role="tabpanel" id="travelbooks">
            </div>
            
            <div class="row tab-pane" role="tabpanel" id="profile">
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
	</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/user.js') }}"></script>
@endsection
