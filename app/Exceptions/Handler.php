<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Swift_TransportException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Error_log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException)
        {
            $exception = new NotFoundHttpException($exception->getMessage(), $exception);
        }
        if ($this->isHttpException($exception))
        {
            $statusCode = $exception->getStatusCode();
            switch ($statusCode)
            {
                case '404': return response()->view('errors.404', array(), 404);
                case '403' : return response()->view("auth.verification_expired", array(), 403);
                // case '500' : return response()->view("errors.render", compact("exception"), 500);
            }
        }

        if($exception instanceof MethodNotAllowedHttpException){
            $_exp = explode("/", $request->server('REDIRECT_URL'));
            return redirect()->back();
        }

        if($exception instanceof Swift_TransportException){
            Log::error("Mail : ".$exception->getMessage());
            return redirect()->route("forgot.email.sent")->with('status', trans('passwords.setn'));
        }

        $headers = $request->header();
        $ptype = $headers['post-type'] ?? "";

        if($exception instanceof ValidationException){
            return parent::render($request, $exception);
        }

        if($exception instanceof AuthenticationException){
            $redirect = route("login")."?redirect=".urlencode($request->path());
            return redirect()->to($redirect);
        }

        $err['message'] = $exception->getMessage();
        $err['file'] =  $exception->getFile();
        $err['line'] =  $exception->getLine();
        $err['code'] =  $exception->getCode();
        $err['trace'] = $exception->getTrace();

        $causer_id = Auth::id() ?? null;

        // $error_log = new Error_log();
        // $error_log->causer_id = $causer_id;
        // $error_log->error_log = json_encode($err);
        // $error_log->url = $request->path();
        // $error_log->save();

        if(!$request->ajax()){
            return response()->view("errors.render", compact("err"));
        }

        // dd($exception, $request->ajax());

        // $statusCode = $exception->getStatusCode();
        // dd($statusCode);

        if(!$request->ajax()){
            // return view("errors.render", compact("exception"));
        }

        return parent::render($request, $exception);
    }
}
