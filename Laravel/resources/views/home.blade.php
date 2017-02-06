@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.home">
@endsection

@section('head')
<link href="{{ asset('/css/home.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="homepage" ng-controller="HomepageController as homepageCtrl">
    <!-- mobile searchbar -->
	<div class="row visible-xs">
		<div class="col-xs-12 searchbar">
            <form novalidate>
                <div class="input-group input-group-lg">
                    <span ng-click="homepageCtrl.search()" class="input-group-addon"><i class="fa fa-search fa-fw clickable"></i></span>
                    <input class="form-control" type="text" name="searchInput" ng-model="homepageCtrl.searchInput" placeholder="Search...">
                    <input type="submit" ng-show="false" ng-click="homepageCtrl.search()">
                </div>
            </form>
		</div>
	</div>
    <!-- /mobile searchbar -->
    
    <!-- first row of articles -->
	<div class="row hidden-xs">
		
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(0) !== null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(0).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(0).id}}" class="book-overview">
				<div class="book-overview-content">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(0).title}}</div>
                    </div>
				</div>
			</a>
		</div>
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(0) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
		
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(1) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(1).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(1).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(1).title}}</div>
                    </div>
                </div>
			</a>
		</div>
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(1) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
        
        
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(2) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(2).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(2).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(2).title}}</div>
                    </div>
                </div>
			</a>
		</div>
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(2) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
        
	</div>
    <!-- /first row of articles -->
    
    <!-- first mobile row of articles -->
	<div class="row visible-xs">
    
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(0) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(0).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(0).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(0).title}}</div>
                    </div>
                </div>
			</a>
		</div>
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(0) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
        
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(1) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(1).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(1).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(1).title}}</div>
                    </div>
                </div>
			</a>
		</div>
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(1) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
        
	</div>
    <!-- /first mobile row of articles -->
    
    <!-- searchbar -->
	<div class="row hidden-xs">
		<div class="col-sm-offset-3 col-sm-6 searchbar">
            <form novalidate>
                <div class="input-group input-group-lg">
                    <span ng-if="!homepageCtrl.isSearching" ng-click="homepageCtrl.search()" class="input-group-addon"><i class="fa fa-search fa-fw clickable"></i></span>
                    <span ng-if="homepageCtrl.isSearching" class="input-group-addon"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i></span>
                    <input class="form-control" type="text" name="searchInput" ng-disabled="homepageCtrl.isSearching" ng-model="homepageCtrl.searchInput" placeholder="Search...">
                    <input type="submit" ng-show="false" ng-click="homepageCtrl.search()">
                </div>
            </form>
		</div>
	</div>
    <!-- /searchbar -->
    
    <!-- second row of articles -->
    <div class="row hidden-xs">
		
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(3) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(3).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(3).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(3).title}}</div>
                    </div>
				</div>
			</a>
		</div>
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(3) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
				</div>
			</a>
		</div>
		
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(4) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(4).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(4).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(4).title}}</div>
                    </div>
				</div>
			</a>
		</div>
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(4) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
        
        
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(5) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(5).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(5).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(5).title}}</div>
                    </div>
				</div>
			</a>
		</div>
        <div class="col-sm-4 home-thumbnail big" ng-if="homepageCtrl.articleAt(5) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
        
	</div>
    <!-- /second row of articles -->
    
    <!-- second mobile row of articles -->
	<div class="row visible-xs">
    
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(2) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(2).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(2).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(2).title}}</div>
                    </div>
				</div>
			</a>
		</div>
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(2) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
        
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(3) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(3).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(3).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(3).title}}</div>
                    </div>
				</div>
			</a>
		</div>
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(3) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
        
	</div>
    <!-- /second mobile row of articles -->
    
    <!-- third mobile row of articles -->
	<div class="row visible-xs">
    
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(4) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(4).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(4).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(4).title}}</div>
                    </div>
				</div>
			</a>
		</div>
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(4) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
        
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(5) != null"
		ng-style="{'background-image': 'url('+homepageCtrl.articleAt(5).descriptionMedia.smallUrl+')'}">
			<a ng-href="@{{'/article/'+homepageCtrl.articleAt(5).id}}" class="book-overview">
				<div class="book-overview-content">
					<div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">@{{homepageCtrl.articleAt(5).title}}</div>
                    </div>
				</div>
			</a>
		</div>
        <div class="col-xs-6 home-thumbnail big" ng-if="homepageCtrl.articleAt(5) == null"
		style="background-image: url('/images/generic.png')">
			<a href="/auth/login" class="book-overview">
				<div class="book-overview-content fake">
                    <div class="sun-line">
                    </div>
                    <div class="book-overview-content-inner">
                        <div class="line">Partagez vos récits en <span class="fake-link">vous connectant</span> ou en <span class="fake-link">vous inscrivant</span>.</div>
                    </div>
                </div>
			</a>
		</div>
        
	</div>
    <!-- /third mobile row of articles -->
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/home.js') }}"></script>
@endsection
