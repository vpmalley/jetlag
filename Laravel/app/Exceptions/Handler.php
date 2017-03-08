<?php namespace Jetlag\Exceptions;

use Log;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler {

  /**
  * A list of the exception types that should not be reported.
  *
  * @var array
  */
  protected $dontReport = [
    'Symfony\Component\HttpKernel\Exception\HttpException'
  ];

  /**
  * Report or log an exception.
  *
  * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
  *
  * @param  \Exception  $e
  * @return void
  */
  public function report(Exception $e)
  {
    if ($e instanceof HttpException) {
      Log::warning($e->getStatusCode() . ' : ' . $e->getMessage());
    }
    return parent::report($e);
  }

  /**
  * Render an exception into an HTTP response.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Exception  $e
  * @return \Illuminate\Http\Response
  */
  public function render($request, Exception $e)
  {
    if ($e instanceof ModelNotFoundException) {
      abort(404);
    }
    if (($e instanceof HttpException) && ($request->ajax() || 'api' === $request->segment(1))) {
      $errorBody =  [
                  'error' => [
                    'status' => $e->getStatusCode(),
                    'message' => $e->getMessage(),
                  ]
                ];
      return response()->json($errorBody, $e->getStatusCode());
    }
    return parent::render($request, $e);
  }

}
