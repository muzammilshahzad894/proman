<?php

//migrate/refresh


Route::get('/migrate/{key}',  array('as' => 'refresh', function($key = null)
{

    if($key == "install"){
        try {
            echo '<br>init migrate:install...';
            $output = new \Symfony\Component\Console\Output\BufferedOutput; 
            Artisan::call('migrate', [], $output);
            dump($output);
            echo 'done migrate:install';

        } catch (Exception $e) {
            return $e->getMessage();
            //Response::make($e->getMessage(), 500);
        }
    }

}));


Route::get('/swagger/generate',  array('as' => 'refresh', function($key = null)
{
    
    echo '<br>init swagger docs generation...';
    $output = new \Symfony\Component\Console\Output\BufferedOutput; 
    Artisan::call('l5-swagger:generate',[],$output);
    dump($output);
    echo 'done swagger docs generated';
}));

Route::get('/view_clear',  array('as' => 'refresh', function($key = null)
{

    
        try {
            echo '<br>init view:clear...';
            $output = new \Symfony\Component\Console\Output\BufferedOutput; 
            Artisan::call('view:clear');
            dump($output);
            echo 'done view:clear';

        } catch (Exception $e) {
            return $e->getMessage();
            //Response::make($e->getMessage(), 500);
        }
    

}));

Route::get('/queue_work',  array('as' => 'refresh', function($key = null)
{

    
        try {
            echo '<br>init queue:work...';
            $output = new \Symfony\Component\Console\Output\BufferedOutput; 
            Artisan::call('queue:work');
            dump($output);
            echo 'done queue:work';

        } catch (Exception $e) {
            return $e->getMessage();
            //Response::make($e->getMessage(), 500);
        }
    

}));
Route::get('/config_cache',  array('as' => 'refresh', function($key = null)
{

    
        try {
            echo '<br>init view:clear...';
            $output = new \Symfony\Component\Console\Output\BufferedOutput; 
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('config:cache');
            dump($output);
            echo 'done cache:clear config:clear cache:config';

        } catch (Exception $e) {
            return $e->getMessage();
            //Response::make($e->getMessage(), 500);
        }
    

}));

//Setup route example db/seed
Route::get('/db/{key}',  array('as' => 'install', function($key = null)
{
    if($key == "seed"){
        try {

            echo '<br>init tables seader...';
            Artisan::call('db:seed');
            echo '<br>done with seader';
        } catch (ReflectionException $e) {
            return $e->getMessage();
        }
    }else{
        App::abort(404);
    }

}));

Route::get('run-seeder/{class}', function ($class) {
    try {
        $exitCode = Artisan::call('db:seed', ['--class' => $class]);
        
        if ($exitCode === 0) {
            return $class . ' seeder run successfully.';
        } else {
            return 'Seeder failed to run.';
        }
    } catch (Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
    }
});

Route::get('/passport_install',  array('as' => 'refresh', function($key = null)
{
    try {
        echo '<br>init passport:install...';
        $output = new \Symfony\Component\Console\Output\BufferedOutput; 
        Artisan::call('passport:install');
        Artisan::call('passport:client');
        dump($output);
        echo 'done passport:install';
    } catch (Exception $e) {
        return $e->getMessage();
        //Response::make($e->getMessage(), 500);
    }
}));

//Setup route example seed_email_templates
Route::get('seed_email_templates',  array('as' => 'install', function($key = null)
{
    
        try {

            echo '<br>init tables email templates seader...';
            Artisan::call('db:seed', [ '--class' => 'EmailTemplatesSeeder']);
            echo '<br>done with seader';
        } catch (ReflectionException $e) {
            return $e->getMessage();
        }
    

}));

//Setup route example linking storage
Route::get('link_storage',  array('as' => 'install', function($key = null)
{
    
        try {

            echo '<br>init link_storage...';
            Artisan::call('storage:link');
            echo '<br>done with artisan command';
        } catch (ReflectionException $e) {
            return $e->getMessage();
        }
    

}));

Route::get('log',function ()
{
    // from PHP documentations
     $logFile = file(storage_path().'/logs/laravel.log');
     $logCollection = [];
     // Loop through an array, show HTML source as HTML source; and line numbers too.
     foreach ($logFile as $line_num => $line) {
        $logCollection[] = array('line'=> $line_num, 'content'=> htmlspecialchars($line));
     }
     dump($logFile);
});

Route::get('composer',function ()
{
    // from PHP documentations
    system('composer dump-autoload');
   // dump($result); 

}); 


Route::get('swagger-hq',function ()
{
   

	File::delete(storage_path('api-docs/api-docs.json'));

	$content = File::get(public_path().'/api-docs.json-hq');

	File::put(storage_path('api-docs/api-docs.json'),$content );

	dump('Done');


});