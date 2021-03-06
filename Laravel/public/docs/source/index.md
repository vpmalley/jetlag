---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---

# Info

Welcome to the generated API reference.

# Available routes
## Display a listing of the resource for the logged in user.

> Example request:

```bash
curl "http://homestead.app/api/0.1/articles" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://homestead.app/api/0.1/articles",
    "method": "GET",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

    > Example response:

    ```json
    <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="robots" content="noindex,nofollow" />
        <style>
            /* Copyright (c) 2010, Yahoo! Inc. All rights reserved. Code licensed under the BSD License: http://developer.yahoo.com/yui/license.html */
            html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:text-top;}sub{vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}input,textarea,select{*font-size:100%;}legend{color:#000;}

            html { background: #eee; padding: 10px }
            img { border: 0; }
            #sf-resetcontent { width:970px; margin:0 auto; }
                        .sf-reset { font: 11px Verdana, Arial, sans-serif; color: #333 }
            .sf-reset .clear { clear:both; height:0; font-size:0; line-height:0; }
            .sf-reset .clear_fix:after { display:block; height:0; clear:both; visibility:hidden; }
            .sf-reset .clear_fix { display:inline-block; }
            .sf-reset * html .clear_fix { height:1%; }
            .sf-reset .clear_fix { display:block; }
            .sf-reset, .sf-reset .block { margin: auto }
            .sf-reset abbr { border-bottom: 1px dotted #000; cursor: help; }
            .sf-reset p { font-size:14px; line-height:20px; color:#868686; padding-bottom:20px }
            .sf-reset strong { font-weight:bold; }
            .sf-reset a { color:#6c6159; cursor: default; }
            .sf-reset a img { border:none; }
            .sf-reset a:hover { text-decoration:underline; }
            .sf-reset em { font-style:italic; }
            .sf-reset h1, .sf-reset h2 { font: 20px Georgia, "Times New Roman", Times, serif }
            .sf-reset .exception_counter { background-color: #fff; color: #333; padding: 6px; float: left; margin-right: 10px; float: left; display: block; }
            .sf-reset .exception_title { margin-left: 3em; margin-bottom: 0.7em; display: block; }
            .sf-reset .exception_message { margin-left: 3em; display: block; }
            .sf-reset .traces li { font-size:12px; padding: 2px 4px; list-style-type:decimal; margin-left:20px; }
            .sf-reset .block { background-color:#FFFFFF; padding:10px 28px; margin-bottom:20px;
                -webkit-border-bottom-right-radius: 16px;
                -webkit-border-bottom-left-radius: 16px;
                -moz-border-radius-bottomright: 16px;
                -moz-border-radius-bottomleft: 16px;
                border-bottom-right-radius: 16px;
                border-bottom-left-radius: 16px;
                border-bottom:1px solid #ccc;
                border-right:1px solid #ccc;
                border-left:1px solid #ccc;
            }
            .sf-reset .block_exception { background-color:#ddd; color: #333; padding:20px;
                -webkit-border-top-left-radius: 16px;
                -webkit-border-top-right-radius: 16px;
                -moz-border-radius-topleft: 16px;
                -moz-border-radius-topright: 16px;
                border-top-left-radius: 16px;
                border-top-right-radius: 16px;
                border-top:1px solid #ccc;
                border-right:1px solid #ccc;
                border-left:1px solid #ccc;
                overflow: hidden;
                word-wrap: break-word;
            }
            .sf-reset a { background:none; color:#868686; text-decoration:none; }
            .sf-reset a:hover { background:none; color:#313131; text-decoration:underline; }
            .sf-reset ol { padding: 10px 0; }
            .sf-reset h1 { background-color:#FFFFFF; padding: 15px 28px; margin-bottom: 20px;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                border: 1px solid #ccc;
            }
        </style>
    </head>
    <body>
                    <div id="sf-resetcontent" class="sf-reset">
                <h1>Whoops, looks like something went wrong.</h1>
                                        <h2 class="block_exception clear_fix">
                            <span class="exception_counter">1/1</span>
                            <span class="exception_title"><abbr title="ErrorException">ErrorException</abbr> in <a title="/home/vince/workspace/jetlag/Laravel/app/Http/Controllers/Rest/RestArticleController.php line 39" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">RestArticleController.php line 39</a>:</span>
                            <span class="exception_message">Trying to get property of non-object</span>
                        </h2>
                        <div class="block">
                            <ol class="traces list_exception">
       <li> in <a title="/home/vince/workspace/jetlag/Laravel/app/Http/Controllers/Rest/RestArticleController.php line 39" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">RestArticleController.php line 39</a></li>
       <li>at <abbr title="Illuminate\Foundation\Bootstrap\HandleExceptions">HandleExceptions</abbr>->handleError('8', 'Trying to get property of non-object', '/home/vince/workspace/jetlag/Laravel/app/Http/Controllers/Rest/RestArticleController.php', '39', <em>array</em>()) in <a title="/home/vince/workspace/jetlag/Laravel/app/Http/Controllers/Rest/RestArticleController.php line 39" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">RestArticleController.php line 39</a></li>
       <li>at <abbr title="Jetlag\Http\Controllers\Rest\RestArticleController">RestArticleController</abbr>->index()</li>
       <li>at <abbr title=""></abbr>call_user_func_array(<em>array</em>(<em>object</em>(<abbr title="Jetlag\Http\Controllers\Rest\RestArticleController">RestArticleController</abbr>), 'index'), <em>array</em>()) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Controller.php line 256" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Controller.php line 256</a></li>
       <li>at <abbr title="Illuminate\Routing\Controller">Controller</abbr>->callAction('index', <em>array</em>()) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php line 164" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ControllerDispatcher.php line 164</a></li>
       <li>at <abbr title="Illuminate\Routing\ControllerDispatcher">ControllerDispatcher</abbr>->call(<em>object</em>(<abbr title="Jetlag\Http\Controllers\Rest\RestArticleController">RestArticleController</abbr>), <em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>), 'index') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php line 112" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ControllerDispatcher.php line 112</a></li>
       <li>at <abbr title="Illuminate\Routing\ControllerDispatcher">ControllerDispatcher</abbr>->Illuminate\Routing\{closure}(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php line 139" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Pipeline.php line 139</a></li>
       <li>at <abbr title="Illuminate\Pipeline\Pipeline">Pipeline</abbr>->Illuminate\Pipeline\{closure}(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php line 103" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Pipeline.php line 103</a></li>
       <li>at <abbr title="Illuminate\Pipeline\Pipeline">Pipeline</abbr>->then(<em>object</em>(<abbr title="Closure">Closure</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php line 114" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ControllerDispatcher.php line 114</a></li>
       <li>at <abbr title="Illuminate\Routing\ControllerDispatcher">ControllerDispatcher</abbr>->callWithinStack(<em>object</em>(<abbr title="Jetlag\Http\Controllers\Rest\RestArticleController">RestArticleController</abbr>), <em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>), 'index') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php line 69" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ControllerDispatcher.php line 69</a></li>
       <li>at <abbr title="Illuminate\Routing\ControllerDispatcher">ControllerDispatcher</abbr>->dispatch(<em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>), 'Jetlag\Http\Controllers\Rest\RestArticleController', 'index') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Route.php line 203" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Route.php line 203</a></li>
       <li>at <abbr title="Illuminate\Routing\Route">Route</abbr>->runWithCustomDispatcher(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Route.php line 134" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Route.php line 134</a></li>
       <li>at <abbr title="Illuminate\Routing\Route">Route</abbr>->run(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 708" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 708</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->Illuminate\Routing\{closure}(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php line 139" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Pipeline.php line 139</a></li>
       <li>at <abbr title="Illuminate\Pipeline\Pipeline">Pipeline</abbr>->Illuminate\Pipeline\{closure}(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php line 103" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Pipeline.php line 103</a></li>
       <li>at <abbr title="Illuminate\Pipeline\Pipeline">Pipeline</abbr>->then(<em>object</em>(<abbr title="Closure">Closure</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 710" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 710</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->runRouteWithinStack(<em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 675" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 675</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->dispatchToRoute(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 635" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 635</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->dispatch(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php line 236" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 236</a></li>
       <li>at <abbr title="Illuminate\Foundation\Http\Kernel">Kernel</abbr>->Illuminate\Foundation\Http\{closure}(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php line 139" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Pipeline.php line 139</a></li>
       <li>at <abbr title="Illuminate\Pipeline\Pipeline">Pipeline</abbr>->Illuminate\Pipeline\{closure}(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php line 103" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Pipeline.php line 103</a></li>
       <li>at <abbr title="Illuminate\Pipeline\Pipeline">Pipeline</abbr>->then(<em>object</em>(<abbr title="Closure">Closure</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php line 122" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 122</a></li>
       <li>at <abbr title="Illuminate\Foundation\Http\Kernel">Kernel</abbr>->sendRequestThroughRouter(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php line 87" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 87</a></li>
       <li>at <abbr title="Illuminate\Foundation\Http\Kernel">Kernel</abbr>->handle(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/ApiDocGenerator.php line 325" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ApiDocGenerator.php line 325</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\ApiDocGenerator">ApiDocGenerator</abbr>->callRoute('GET', 'api/0.1/articles') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/ApiDocGenerator.php line 61" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ApiDocGenerator.php line 61</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\ApiDocGenerator">ApiDocGenerator</abbr>->getRouteResponse(<em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/ApiDocGenerator.php line 25" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ApiDocGenerator.php line 25</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\ApiDocGenerator">ApiDocGenerator</abbr>->processRoute(<em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/Commands/GenerateDocumentation.php line 79" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">GenerateDocumentation.php line 79</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>->handle()</li>
       <li>at <abbr title=""></abbr>call_user_func_array(<em>array</em>(<em>object</em>(<abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>), 'handle'), <em>array</em>()) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Container/Container.php line 507" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Container.php line 507</a></li>
       <li>at <abbr title="Illuminate\Container\Container">Container</abbr>->call(<em>array</em>(<em>object</em>(<abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>), 'handle')) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Console/Command.php line 150" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Command.php line 150</a></li>
       <li>at <abbr title="Illuminate\Console\Command">Command</abbr>->execute(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Command/Command.php line 256" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Command.php line 256</a></li>
       <li>at <abbr title="Symfony\Component\Console\Command\Command">Command</abbr>->run(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Console/Command.php line 136" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Command.php line 136</a></li>
       <li>at <abbr title="Illuminate\Console\Command">Command</abbr>->run(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Application.php line 841" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Application.php line 841</a></li>
       <li>at <abbr title="Symfony\Component\Console\Application">Application</abbr>->doRunCommand(<em>object</em>(<abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Application.php line 189" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Application.php line 189</a></li>
       <li>at <abbr title="Symfony\Component\Console\Application">Application</abbr>->doRun(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Application.php line 120" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Application.php line 120</a></li>
       <li>at <abbr title="Symfony\Component\Console\Application">Application</abbr>->run(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php line 107" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 107</a></li>
       <li>at <abbr title="Illuminate\Foundation\Console\Kernel">Kernel</abbr>->handle(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/artisan line 36" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">artisan line 36</a></li>
    </ol>
</div>

            </div>
    </body>
</html>
    ```

### HTTP Request
`GET api/0.1/articles`

`HEAD api/0.1/articles`


## Store a newly created resource in storage.

> Example request:

```bash
curl "http://homestead.app/api/0.1/articles" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://homestead.app/api/0.1/articles",
    "method": "POST",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```


### HTTP Request
`POST api/0.1/articles`


## Display the specified resource.

> Example request:

```bash
curl "http://homestead.app/api/0.1/articles/{articles}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://homestead.app/api/0.1/articles/{articles}",
    "method": "GET",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

    > Example response:

    ```json
    <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="robots" content="noindex,nofollow" />
        <style>
            /* Copyright (c) 2010, Yahoo! Inc. All rights reserved. Code licensed under the BSD License: http://developer.yahoo.com/yui/license.html */
            html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:text-top;}sub{vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}input,textarea,select{*font-size:100%;}legend{color:#000;}

            html { background: #eee; padding: 10px }
            img { border: 0; }
            #sf-resetcontent { width:970px; margin:0 auto; }
                        .sf-reset { font: 11px Verdana, Arial, sans-serif; color: #333 }
            .sf-reset .clear { clear:both; height:0; font-size:0; line-height:0; }
            .sf-reset .clear_fix:after { display:block; height:0; clear:both; visibility:hidden; }
            .sf-reset .clear_fix { display:inline-block; }
            .sf-reset * html .clear_fix { height:1%; }
            .sf-reset .clear_fix { display:block; }
            .sf-reset, .sf-reset .block { margin: auto }
            .sf-reset abbr { border-bottom: 1px dotted #000; cursor: help; }
            .sf-reset p { font-size:14px; line-height:20px; color:#868686; padding-bottom:20px }
            .sf-reset strong { font-weight:bold; }
            .sf-reset a { color:#6c6159; cursor: default; }
            .sf-reset a img { border:none; }
            .sf-reset a:hover { text-decoration:underline; }
            .sf-reset em { font-style:italic; }
            .sf-reset h1, .sf-reset h2 { font: 20px Georgia, "Times New Roman", Times, serif }
            .sf-reset .exception_counter { background-color: #fff; color: #333; padding: 6px; float: left; margin-right: 10px; float: left; display: block; }
            .sf-reset .exception_title { margin-left: 3em; margin-bottom: 0.7em; display: block; }
            .sf-reset .exception_message { margin-left: 3em; display: block; }
            .sf-reset .traces li { font-size:12px; padding: 2px 4px; list-style-type:decimal; margin-left:20px; }
            .sf-reset .block { background-color:#FFFFFF; padding:10px 28px; margin-bottom:20px;
                -webkit-border-bottom-right-radius: 16px;
                -webkit-border-bottom-left-radius: 16px;
                -moz-border-radius-bottomright: 16px;
                -moz-border-radius-bottomleft: 16px;
                border-bottom-right-radius: 16px;
                border-bottom-left-radius: 16px;
                border-bottom:1px solid #ccc;
                border-right:1px solid #ccc;
                border-left:1px solid #ccc;
            }
            .sf-reset .block_exception { background-color:#ddd; color: #333; padding:20px;
                -webkit-border-top-left-radius: 16px;
                -webkit-border-top-right-radius: 16px;
                -moz-border-radius-topleft: 16px;
                -moz-border-radius-topright: 16px;
                border-top-left-radius: 16px;
                border-top-right-radius: 16px;
                border-top:1px solid #ccc;
                border-right:1px solid #ccc;
                border-left:1px solid #ccc;
                overflow: hidden;
                word-wrap: break-word;
            }
            .sf-reset a { background:none; color:#868686; text-decoration:none; }
            .sf-reset a:hover { background:none; color:#313131; text-decoration:underline; }
            .sf-reset ol { padding: 10px 0; }
            .sf-reset h1 { background-color:#FFFFFF; padding: 15px 28px; margin-bottom: 20px;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                border: 1px solid #ccc;
            }
        </style>
    </head>
    <body>
                    <div id="sf-resetcontent" class="sf-reset">
                <h1>Whoops, looks like something went wrong.</h1>
                                        <h2 class="block_exception clear_fix">
                            <span class="exception_counter">1/1</span>
                            <span class="exception_title"><abbr title="PDOException">PDOException</abbr> in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php line 55" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Connector.php line 55</a>:</span>
                            <span class="exception_message">SQLSTATE[HY000] [1045] Access denied for user &#039;homestead&#039;@&#039;localhost&#039; (using password: YES)</span>
                        </h2>
                        <div class="block">
                            <ol class="traces list_exception">
       <li> in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php line 55" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Connector.php line 55</a></li>
       <li>at <abbr title="PDO">PDO</abbr>->__construct('mysql:host=localhost;dbname=homestead', 'homestead', 'secret', <em>array</em>('0', '2', '0', <em>false</em>, <em>false</em>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php line 55" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Connector.php line 55</a></li>
       <li>at <abbr title="Illuminate\Database\Connectors\Connector">Connector</abbr>->createConnection('mysql:host=localhost;dbname=homestead', <em>array</em>('driver' => 'mysql', 'host' => 'localhost', 'database' => 'homestead', 'username' => 'homestead', 'password' => 'secret', 'charset' => 'utf8', 'collation' => 'utf8_unicode_ci', 'prefix' => '', 'strict' => <em>false</em>, 'name' => 'mysql'), <em>array</em>('0', '2', '0', <em>false</em>, <em>false</em>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/MySqlConnector.php line 22" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">MySqlConnector.php line 22</a></li>
       <li>at <abbr title="Illuminate\Database\Connectors\MySqlConnector">MySqlConnector</abbr>->connect(<em>array</em>('driver' => 'mysql', 'host' => 'localhost', 'database' => 'homestead', 'username' => 'homestead', 'password' => 'secret', 'charset' => 'utf8', 'collation' => 'utf8_unicode_ci', 'prefix' => '', 'strict' => <em>false</em>, 'name' => 'mysql')) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/ConnectionFactory.php line 60" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ConnectionFactory.php line 60</a></li>
       <li>at <abbr title="Illuminate\Database\Connectors\ConnectionFactory">ConnectionFactory</abbr>->createSingleConnection(<em>array</em>('driver' => 'mysql', 'host' => 'localhost', 'database' => 'homestead', 'username' => 'homestead', 'password' => 'secret', 'charset' => 'utf8', 'collation' => 'utf8_unicode_ci', 'prefix' => '', 'strict' => <em>false</em>, 'name' => 'mysql')) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/ConnectionFactory.php line 49" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ConnectionFactory.php line 49</a></li>
       <li>at <abbr title="Illuminate\Database\Connectors\ConnectionFactory">ConnectionFactory</abbr>->make(<em>array</em>('driver' => 'mysql', 'host' => 'localhost', 'database' => 'homestead', 'username' => 'homestead', 'password' => 'secret', 'charset' => 'utf8', 'collation' => 'utf8_unicode_ci', 'prefix' => '', 'strict' => <em>false</em>), 'mysql') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/DatabaseManager.php line 175" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">DatabaseManager.php line 175</a></li>
       <li>at <abbr title="Illuminate\Database\DatabaseManager">DatabaseManager</abbr>->makeConnection('mysql') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/DatabaseManager.php line 67" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">DatabaseManager.php line 67</a></li>
       <li>at <abbr title="Illuminate\Database\DatabaseManager">DatabaseManager</abbr>->connection(<em>null</em>) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 3224" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 3224</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>::resolveConnection(<em>null</em>) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 3190" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 3190</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>->getConnection() in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 1870" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 1870</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>->newBaseQueryBuilder() in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 1813" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 1813</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>->newQueryWithoutScopes() in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 1787" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 1787</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>->newQuery() in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 3435" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 3435</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>->__call('where', <em>array</em>('id', '{articles}')) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 949" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 949</a></li>
       <li>at <abbr title="Jetlag\Eloquent\Article">Article</abbr>->where('id', '{articles}') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 949" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 949</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->Illuminate\Routing\{closure}('{articles}', <em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), '{articles}', <em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 784" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 784</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->performBinding('articles', '{articles}', <em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 767" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 767</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->substituteBindings(<em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 754" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 754</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->findRoute(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 659" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 659</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->dispatchToRoute(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 635" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 635</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->dispatch(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php line 236" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 236</a></li>
       <li>at <abbr title="Illuminate\Foundation\Http\Kernel">Kernel</abbr>->Illuminate\Foundation\Http\{closure}(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php line 139" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Pipeline.php line 139</a></li>
       <li>at <abbr title="Illuminate\Pipeline\Pipeline">Pipeline</abbr>->Illuminate\Pipeline\{closure}(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php line 103" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Pipeline.php line 103</a></li>
       <li>at <abbr title="Illuminate\Pipeline\Pipeline">Pipeline</abbr>->then(<em>object</em>(<abbr title="Closure">Closure</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php line 122" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 122</a></li>
       <li>at <abbr title="Illuminate\Foundation\Http\Kernel">Kernel</abbr>->sendRequestThroughRouter(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php line 87" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 87</a></li>
       <li>at <abbr title="Illuminate\Foundation\Http\Kernel">Kernel</abbr>->handle(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/ApiDocGenerator.php line 325" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ApiDocGenerator.php line 325</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\ApiDocGenerator">ApiDocGenerator</abbr>->callRoute('GET', 'api/0.1/articles/{articles}') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/ApiDocGenerator.php line 61" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ApiDocGenerator.php line 61</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\ApiDocGenerator">ApiDocGenerator</abbr>->getRouteResponse(<em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/ApiDocGenerator.php line 25" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ApiDocGenerator.php line 25</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\ApiDocGenerator">ApiDocGenerator</abbr>->processRoute(<em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/Commands/GenerateDocumentation.php line 79" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">GenerateDocumentation.php line 79</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>->handle()</li>
       <li>at <abbr title=""></abbr>call_user_func_array(<em>array</em>(<em>object</em>(<abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>), 'handle'), <em>array</em>()) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Container/Container.php line 507" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Container.php line 507</a></li>
       <li>at <abbr title="Illuminate\Container\Container">Container</abbr>->call(<em>array</em>(<em>object</em>(<abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>), 'handle')) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Console/Command.php line 150" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Command.php line 150</a></li>
       <li>at <abbr title="Illuminate\Console\Command">Command</abbr>->execute(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Command/Command.php line 256" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Command.php line 256</a></li>
       <li>at <abbr title="Symfony\Component\Console\Command\Command">Command</abbr>->run(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Console/Command.php line 136" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Command.php line 136</a></li>
       <li>at <abbr title="Illuminate\Console\Command">Command</abbr>->run(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Application.php line 841" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Application.php line 841</a></li>
       <li>at <abbr title="Symfony\Component\Console\Application">Application</abbr>->doRunCommand(<em>object</em>(<abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Application.php line 189" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Application.php line 189</a></li>
       <li>at <abbr title="Symfony\Component\Console\Application">Application</abbr>->doRun(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Application.php line 120" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Application.php line 120</a></li>
       <li>at <abbr title="Symfony\Component\Console\Application">Application</abbr>->run(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php line 107" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 107</a></li>
       <li>at <abbr title="Illuminate\Foundation\Console\Kernel">Kernel</abbr>->handle(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/artisan line 36" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">artisan line 36</a></li>
    </ol>
</div>

            </div>
    </body>
</html>
    ```

### HTTP Request
`GET api/0.1/articles/{articles}`

`HEAD api/0.1/articles/{articles}`


## Show the form for editing the specified resource.

> Example request:

```bash
curl "http://homestead.app/api/0.1/articles/{articles}/edit" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://homestead.app/api/0.1/articles/{articles}/edit",
    "method": "GET",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

    > Example response:

    ```json
    <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="robots" content="noindex,nofollow" />
        <style>
            /* Copyright (c) 2010, Yahoo! Inc. All rights reserved. Code licensed under the BSD License: http://developer.yahoo.com/yui/license.html */
            html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:text-top;}sub{vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}input,textarea,select{*font-size:100%;}legend{color:#000;}

            html { background: #eee; padding: 10px }
            img { border: 0; }
            #sf-resetcontent { width:970px; margin:0 auto; }
                        .sf-reset { font: 11px Verdana, Arial, sans-serif; color: #333 }
            .sf-reset .clear { clear:both; height:0; font-size:0; line-height:0; }
            .sf-reset .clear_fix:after { display:block; height:0; clear:both; visibility:hidden; }
            .sf-reset .clear_fix { display:inline-block; }
            .sf-reset * html .clear_fix { height:1%; }
            .sf-reset .clear_fix { display:block; }
            .sf-reset, .sf-reset .block { margin: auto }
            .sf-reset abbr { border-bottom: 1px dotted #000; cursor: help; }
            .sf-reset p { font-size:14px; line-height:20px; color:#868686; padding-bottom:20px }
            .sf-reset strong { font-weight:bold; }
            .sf-reset a { color:#6c6159; cursor: default; }
            .sf-reset a img { border:none; }
            .sf-reset a:hover { text-decoration:underline; }
            .sf-reset em { font-style:italic; }
            .sf-reset h1, .sf-reset h2 { font: 20px Georgia, "Times New Roman", Times, serif }
            .sf-reset .exception_counter { background-color: #fff; color: #333; padding: 6px; float: left; margin-right: 10px; float: left; display: block; }
            .sf-reset .exception_title { margin-left: 3em; margin-bottom: 0.7em; display: block; }
            .sf-reset .exception_message { margin-left: 3em; display: block; }
            .sf-reset .traces li { font-size:12px; padding: 2px 4px; list-style-type:decimal; margin-left:20px; }
            .sf-reset .block { background-color:#FFFFFF; padding:10px 28px; margin-bottom:20px;
                -webkit-border-bottom-right-radius: 16px;
                -webkit-border-bottom-left-radius: 16px;
                -moz-border-radius-bottomright: 16px;
                -moz-border-radius-bottomleft: 16px;
                border-bottom-right-radius: 16px;
                border-bottom-left-radius: 16px;
                border-bottom:1px solid #ccc;
                border-right:1px solid #ccc;
                border-left:1px solid #ccc;
            }
            .sf-reset .block_exception { background-color:#ddd; color: #333; padding:20px;
                -webkit-border-top-left-radius: 16px;
                -webkit-border-top-right-radius: 16px;
                -moz-border-radius-topleft: 16px;
                -moz-border-radius-topright: 16px;
                border-top-left-radius: 16px;
                border-top-right-radius: 16px;
                border-top:1px solid #ccc;
                border-right:1px solid #ccc;
                border-left:1px solid #ccc;
                overflow: hidden;
                word-wrap: break-word;
            }
            .sf-reset a { background:none; color:#868686; text-decoration:none; }
            .sf-reset a:hover { background:none; color:#313131; text-decoration:underline; }
            .sf-reset ol { padding: 10px 0; }
            .sf-reset h1 { background-color:#FFFFFF; padding: 15px 28px; margin-bottom: 20px;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                border: 1px solid #ccc;
            }
        </style>
    </head>
    <body>
                    <div id="sf-resetcontent" class="sf-reset">
                <h1>Whoops, looks like something went wrong.</h1>
                                        <h2 class="block_exception clear_fix">
                            <span class="exception_counter">1/1</span>
                            <span class="exception_title"><abbr title="PDOException">PDOException</abbr> in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php line 55" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Connector.php line 55</a>:</span>
                            <span class="exception_message">SQLSTATE[HY000] [1045] Access denied for user &#039;homestead&#039;@&#039;localhost&#039; (using password: YES)</span>
                        </h2>
                        <div class="block">
                            <ol class="traces list_exception">
       <li> in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php line 55" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Connector.php line 55</a></li>
       <li>at <abbr title="PDO">PDO</abbr>->__construct('mysql:host=localhost;dbname=homestead', 'homestead', 'secret', <em>array</em>('0', '2', '0', <em>false</em>, <em>false</em>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php line 55" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Connector.php line 55</a></li>
       <li>at <abbr title="Illuminate\Database\Connectors\Connector">Connector</abbr>->createConnection('mysql:host=localhost;dbname=homestead', <em>array</em>('driver' => 'mysql', 'host' => 'localhost', 'database' => 'homestead', 'username' => 'homestead', 'password' => 'secret', 'charset' => 'utf8', 'collation' => 'utf8_unicode_ci', 'prefix' => '', 'strict' => <em>false</em>, 'name' => 'mysql'), <em>array</em>('0', '2', '0', <em>false</em>, <em>false</em>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/MySqlConnector.php line 22" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">MySqlConnector.php line 22</a></li>
       <li>at <abbr title="Illuminate\Database\Connectors\MySqlConnector">MySqlConnector</abbr>->connect(<em>array</em>('driver' => 'mysql', 'host' => 'localhost', 'database' => 'homestead', 'username' => 'homestead', 'password' => 'secret', 'charset' => 'utf8', 'collation' => 'utf8_unicode_ci', 'prefix' => '', 'strict' => <em>false</em>, 'name' => 'mysql')) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/ConnectionFactory.php line 60" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ConnectionFactory.php line 60</a></li>
       <li>at <abbr title="Illuminate\Database\Connectors\ConnectionFactory">ConnectionFactory</abbr>->createSingleConnection(<em>array</em>('driver' => 'mysql', 'host' => 'localhost', 'database' => 'homestead', 'username' => 'homestead', 'password' => 'secret', 'charset' => 'utf8', 'collation' => 'utf8_unicode_ci', 'prefix' => '', 'strict' => <em>false</em>, 'name' => 'mysql')) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Connectors/ConnectionFactory.php line 49" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ConnectionFactory.php line 49</a></li>
       <li>at <abbr title="Illuminate\Database\Connectors\ConnectionFactory">ConnectionFactory</abbr>->make(<em>array</em>('driver' => 'mysql', 'host' => 'localhost', 'database' => 'homestead', 'username' => 'homestead', 'password' => 'secret', 'charset' => 'utf8', 'collation' => 'utf8_unicode_ci', 'prefix' => '', 'strict' => <em>false</em>), 'mysql') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/DatabaseManager.php line 175" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">DatabaseManager.php line 175</a></li>
       <li>at <abbr title="Illuminate\Database\DatabaseManager">DatabaseManager</abbr>->makeConnection('mysql') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/DatabaseManager.php line 67" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">DatabaseManager.php line 67</a></li>
       <li>at <abbr title="Illuminate\Database\DatabaseManager">DatabaseManager</abbr>->connection(<em>null</em>) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 3224" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 3224</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>::resolveConnection(<em>null</em>) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 3190" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 3190</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>->getConnection() in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 1870" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 1870</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>->newBaseQueryBuilder() in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 1813" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 1813</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>->newQueryWithoutScopes() in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 1787" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 1787</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>->newQuery() in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php line 3435" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Model.php line 3435</a></li>
       <li>at <abbr title="Illuminate\Database\Eloquent\Model">Model</abbr>->__call('where', <em>array</em>('id', '{articles}')) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 949" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 949</a></li>
       <li>at <abbr title="Jetlag\Eloquent\Article">Article</abbr>->where('id', '{articles}') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 949" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 949</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->Illuminate\Routing\{closure}('{articles}', <em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), '{articles}', <em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 784" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 784</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->performBinding('articles', '{articles}', <em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 767" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 767</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->substituteBindings(<em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 754" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 754</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->findRoute(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 659" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 659</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->dispatchToRoute(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 635" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Router.php line 635</a></li>
       <li>at <abbr title="Illuminate\Routing\Router">Router</abbr>->dispatch(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php line 236" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 236</a></li>
       <li>at <abbr title="Illuminate\Foundation\Http\Kernel">Kernel</abbr>->Illuminate\Foundation\Http\{closure}(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php line 139" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Pipeline.php line 139</a></li>
       <li>at <abbr title="Illuminate\Pipeline\Pipeline">Pipeline</abbr>->Illuminate\Pipeline\{closure}(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>))</li>
       <li>at <abbr title=""></abbr>call_user_func(<em>object</em>(<abbr title="Closure">Closure</abbr>), <em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php line 103" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Pipeline.php line 103</a></li>
       <li>at <abbr title="Illuminate\Pipeline\Pipeline">Pipeline</abbr>->then(<em>object</em>(<abbr title="Closure">Closure</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php line 122" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 122</a></li>
       <li>at <abbr title="Illuminate\Foundation\Http\Kernel">Kernel</abbr>->sendRequestThroughRouter(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php line 87" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 87</a></li>
       <li>at <abbr title="Illuminate\Foundation\Http\Kernel">Kernel</abbr>->handle(<em>object</em>(<abbr title="Illuminate\Http\Request">Request</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/ApiDocGenerator.php line 325" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ApiDocGenerator.php line 325</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\ApiDocGenerator">ApiDocGenerator</abbr>->callRoute('GET', 'api/0.1/articles/{articles}/edit') in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/ApiDocGenerator.php line 61" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ApiDocGenerator.php line 61</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\ApiDocGenerator">ApiDocGenerator</abbr>->getRouteResponse(<em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/ApiDocGenerator.php line 25" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">ApiDocGenerator.php line 25</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\ApiDocGenerator">ApiDocGenerator</abbr>->processRoute(<em>object</em>(<abbr title="Illuminate\Routing\Route">Route</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/mpociot/laravel-apidoc-generator/src/Mpociot/ApiDoc/Commands/GenerateDocumentation.php line 79" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">GenerateDocumentation.php line 79</a></li>
       <li>at <abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>->handle()</li>
       <li>at <abbr title=""></abbr>call_user_func_array(<em>array</em>(<em>object</em>(<abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>), 'handle'), <em>array</em>()) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Container/Container.php line 507" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Container.php line 507</a></li>
       <li>at <abbr title="Illuminate\Container\Container">Container</abbr>->call(<em>array</em>(<em>object</em>(<abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>), 'handle')) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Console/Command.php line 150" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Command.php line 150</a></li>
       <li>at <abbr title="Illuminate\Console\Command">Command</abbr>->execute(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Command/Command.php line 256" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Command.php line 256</a></li>
       <li>at <abbr title="Symfony\Component\Console\Command\Command">Command</abbr>->run(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Console/Command.php line 136" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Command.php line 136</a></li>
       <li>at <abbr title="Illuminate\Console\Command">Command</abbr>->run(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Application.php line 841" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Application.php line 841</a></li>
       <li>at <abbr title="Symfony\Component\Console\Application">Application</abbr>->doRunCommand(<em>object</em>(<abbr title="Mpociot\ApiDoc\Commands\GenerateDocumentation">GenerateDocumentation</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Application.php line 189" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Application.php line 189</a></li>
       <li>at <abbr title="Symfony\Component\Console\Application">Application</abbr>->doRun(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/symfony/console/Application.php line 120" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Application.php line 120</a></li>
       <li>at <abbr title="Symfony\Component\Console\Application">Application</abbr>->run(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php line 107" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">Kernel.php line 107</a></li>
       <li>at <abbr title="Illuminate\Foundation\Console\Kernel">Kernel</abbr>->handle(<em>object</em>(<abbr title="Symfony\Component\Console\Input\ArgvInput">ArgvInput</abbr>), <em>object</em>(<abbr title="Symfony\Component\Console\Output\ConsoleOutput">ConsoleOutput</abbr>)) in <a title="/home/vince/workspace/jetlag/Laravel/artisan line 36" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">artisan line 36</a></li>
    </ol>
</div>

            </div>
    </body>
</html>
    ```

### HTTP Request
`GET api/0.1/articles/{articles}/edit`

`HEAD api/0.1/articles/{articles}/edit`


## Update the specified resource in storage.

> Example request:

```bash
curl "http://homestead.app/api/0.1/articles/{articles}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://homestead.app/api/0.1/articles/{articles}",
    "method": "PUT",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```


### HTTP Request
`PUT api/0.1/articles/{articles}`


## Update the specified resource in storage.

> Example request:

```bash
curl "http://homestead.app/api/0.1/articles/{articles}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://homestead.app/api/0.1/articles/{articles}",
    "method": "PATCH",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```


### HTTP Request
`PATCH api/0.1/articles/{articles}`


## Remove the specified resource from storage.

> Example request:

```bash
curl "http://homestead.app/api/0.1/articles/{articles}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://homestead.app/api/0.1/articles/{articles}",
    "method": "DELETE",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```


### HTTP Request
`DELETE api/0.1/articles/{articles}`


