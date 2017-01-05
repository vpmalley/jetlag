@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.user">
@endsection

@section('head')
<link href="{{ asset('/css/user.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="container-fluid" ng-controller="UserController as userCtrl">
	<div class="row">
    
        <ul class="nav nav-pills" role="tablist">
          <li role="presentation" class="active"><a href="#travelbooks" aria-controls="travelbooks" role="tab" data-toggle="pill">Travelbooks</a></li>
          <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="pill">Profile</a></li>
        </ul>
        
        <div class="col-md-12 tab-content">
            <div class="row tab-pane active" role="tabpanel" id="travelbooks">
                
                <div class="col-sm-4 home-thumbnail big" ng-repeat="article in userCtrl.articles"
                ng-style="{'background-image': 'url('+article.descriptionMedia.smallUrl+')'}">
                    <a ng-href="@{{'/article/#/'+article.id}}" class="book-overview">
                        <div class="book-overview-content">
                            <div class="sun-line"></div>
                            <div class="book-overview-content-inner">
                                <div class="line">@{{article.title}}</div>
                            </div>
                        </div>
                    </a>
                </div>
            
            </div>
            
            <div class="row tab-pane" role="tabpanel" id="profile">
                <div class="col-md-12">
                    <h2 class="section-title">Public details</h2>
                            
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
                    
                    <h2 class="section-title">Private details</h2>
                    
                    <form class="form-horizontal" role="form" method="POST" action="#">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Pseudo</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="Pseudo">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Email address</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="Email address">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">New password</br> 
                            (leave blank if you don't want to change your password)</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="New password">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Old password<br>
                            (only if you want to change your password)</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="Old password">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Confirm old password<br>
                            (only if you want to change your password)</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="Confirm password">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="jl-btn" ng-disabled="true">
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
