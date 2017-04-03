<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="/"></base>
	<title>Test de design - Jetlag</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('/css/jetlag.directives.css') }}" rel="stylesheet" type="text/css">

	<!-- External CSS files -->
	<link href="{{ asset('/css/leaflet.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/angular-moment-picker.css') }}" rel="stylesheet" type="text/css">

	<!-- Fonts -->
	<link href="{{ asset('/css/font-awesome.css') }}" rel="stylesheet" type="text/css">
	<link href="//fonts.googleapis.com/css?family=Roboto:400,300" rel="stylesheet" type="text/css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
	    .test {
	        margin: 20px 20px;
	    }

	    .thumbnail-container {
	        width: 33.33%;
	        height: 245px;
	    }

	    .article-thumbnail {
            width: 100%;
            height: 100%;
            font-size: 1.6rem;
            line-height: 2.5rem;
            font-family: "Lucida Grande","Lucida Sans Unicode","Lucida Sans",Geneva,Arial,sans-serif;
            color: white;
	    }

	    .thumbnail-photo {
	        width: 100%;
	        height: calc(100% - 20px);
            position: relative;
            overflow: hidden;
            background-image: url(https://maptia-production.s3.amazonaws.com/stories/16286/1465075614.jpg);
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
	    }

	    .thumbnail-location {
            width: 100%;
            height: 4.8rem;
            background-color: black;
            line-height: 4.8rem;
            padding: 0 1.6rem;
            text-transform: uppercase;
            text-decoration: none;
            letter-spacing: .5px;
            text-overflow: ellipsis;
            white-space: nowrap;
            word-break: break-word;
            overflow: hidden;
	    }

        .thumbnail-details {
            width: 100%;
            bottom: -100%;
            position: absolute;
            transition: all 1s;
            overflow: hidden;
            background-color: rgba(0, 0, 0, 0.9);
            padding: 1.6rem;
            padding-bottom: 4.8rem;
        }

	    .article-thumbnail:hover .thumbnail-details {
            top: initial;
            bottom: 0px;
            max-height: 100%;
	    }

	    .thumbnail-title {
            font-size: 2.8rem;
            line-height: 5rem;
            text-decoration: none;
            letter-spacing: .5px;
            font-family: "Publico Headline Web",Georgia,Times,"Times New Roman",serif!important;
            text-overflow: ellipsis;
            white-space: nowrap;
            word-break: break-word;
            overflow: hidden;
            max-height: 10rem;
	    }

	    .thumbnail-authors::before {
            content: '-';
	    }

	    .thumbnail-date {
	        position: absolute;
	        bottom: 0;
	    }
	</style>
</head>
<body>
<div class="test">
    <div class="jl-btn">Primary</div>
</div>
<div class="test">
    <div class="jl-btn jl-btn-disabled">Primary disabled</div>
</div>
<div class="test">
    <div class="jl-btn jl-btn-empty">Secondary</div>
</div>
<div class="test">
    <div class="jl-btn jl-btn-empty jl-btn-disabled">Secondary disabled</div>
</div>
<div class="test">
    <h1>Headline 1</h1>
    <h2>Headline 2</h2>
    <p>
        Body of the paragraph which has several<br>
        levels of headline, and all of that stands<br>
        beautifully.
    </p>
</div>
<div class="test">
    <div class="thumbnail-container">
        <div class="article-thumbnail">
            <div class="thumbnail-photo">
                <div class="thumbnail-details details-reduced">
                    <div class="thumbnail-title">
                        Sous le soleil de PapeteeSous le soleil de Papetee, Sous le soleil de Papetee, Sous le soleil de Papetee,Sous le soleil de Papetee
                    </div>
                    <div class="thumbnail-authors">
                        Thibault et ...
                    </div>
                    <div class="thumbnail-date">
                        <i class="fa fa-fw fa-calendar"></i>
                        12/10/2017
                    </div>
                </div>
            </div>
            <div class="thumbnail-location">
                <i class="fa fa-fw fa-map-marker"></i>
                Papetee, Papetee, Papetee, Papetee, Papetee, Papetee
            </div>
        </div>
    </div>
</div>
</body>
</html>
