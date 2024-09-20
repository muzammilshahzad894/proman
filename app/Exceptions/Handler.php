<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDOException;
use Illuminate\Database\QueryException;
use Throwable;
use Sentry\Laravel\Integration;

class Handler extends ExceptionHandler
{

    /**
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {




        $ex_message_text = $exception->getMessage();

        $check_db_table_string = "Base table or view not found";
        $check_db_col_string = "Column not found";

        if (strpos($ex_message_text, $check_db_table_string) !== false || strpos($ex_message_text, $check_db_col_string) !== false) {

            //die($exception->getLine());
            //die($exception->getFile());


            $emailData = [
                'action' => 'email', // view , email
                'template' => 'exception_email',
                'subject' => 'Rentals Client DB Exception | '.config('general.site_name'),
                'toName' => "Umer Rezo",
                'to' => 'ua@rezosystems.com',
                'msg' => $ex_message_text,
                'emailContent' => [
                    'msg' => $ex_message_text
                ]
            ];

            try {
                sendEmail($emailData);
            } catch (\Exception $e) {

            }
            run_migrations();
            die("Something Went wrong, Refresh again!");

        }

        /*if($exception instanceof QueryException)
        {
            die("database error");
            return "db error, try refresh";
        }*/
        //die('asdf');

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
