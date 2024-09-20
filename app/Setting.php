<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Log; 

class Setting extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    protected $guarded = [];

    public function updateConfig($data)
    {
        // rewriting old config's values. This way
        // no 'key => value' pair gets deleted accidentally
        Log::info($data); 
        $this->config = $this->config ? array_merge($this->config, $data) : $data;
        Log::info($this->config);

        // $user = Auth::user();
        
        // invalidate the obsolete cache
        Artisan::call('cache:clear');
        // Artisan::call('config:cache');

        // if($user){
        //     Auth::loginUsingId($user->id);
        // }
        
        // invalidate the obsolete cache
        Cache::forget('site');

        $this->save();
    }

    public function customUpdate(Request $request)
    {
        $newConfig = $request->only('config')['config'];

        if($request->hasFile('config.print_logo_file')) {
            $this->handleUploadedLogo($request);
        }

        // rewriting old config's values. This way
        // no 'key => value' pair gets deleted accidentally
        $this->config = $this->config ? array_merge($this->config, $newConfig) : $newConfig;

        // invalidate the obsolete cache
        Cache::forget($this->key);

        $this->save();
    }

    private function handleUploadedLogo(Request $request)
    {
        $uploadedLogo = $request->file('config.print_logo_file');

        if ($uploadedLogo->isValid()) {
            $this->deleteOldLogo();
            $this->persistNewLogo($uploadedLogo);
        }
    }

    public function deleteOldLogo()
    {
        if (isset($this->config['print_logo']) && $this->config['print_logo']) {
            File::delete(base_path(env('UPLOADS_RELATIVE_PATH') . DIRECTORY_SEPARATOR . $this->config['print_logo']));
        }
    }

    private function persistNewLogo(UploadedFile $uploadedLogo)
    {
        $fileName = $this->generateFileName($uploadedLogo);
        $uploadedLogo->move(base_path(env('UPLOADS_RELATIVE_PATH')), $fileName);

        $this->persistNewLogoName($fileName);
    }

    private function persistNewLogoName($fileName)
    {
        $config = $this->config;
        $config['print_logo'] = $fileName;
        $this->config = $config;
    }

    private function generateFileName(UploadedFile $uploadedFile)
    {
        return 'print_logo.' . $uploadedFile->getClientOriginalExtension();
    }

    public function removeEmail($email)
    {
        $config = $this->config;

        $key = array_search($email, $config['additional_emails']);

        // $key might be 0, hence the strict comparison
        if($key !== false) unset($config['additional_emails'][$key]);

        $this->config = $config;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters / Setters
    |--------------------------------------------------------------------------
    */

    /**
     * @return array
     */
    public function getConfigAttribute()
    {
        $config = json_decode(@$this->attributes['config'], true);

        return $config ? $config : [];
    }

    /**
     * @return array
     */
    public function getValidationRulesAttribute()
    {
        $validationRules = json_decode($this->attributes['validation_rules'], true);

        return $validationRules ? $validationRules : [];
    }

    /**
     * @param array
     */
    public function setConfigAttribute(array $config)
    {
        $this->attributes['config'] = json_encode($config);
    }

    /**
     * In case if someone attempts to change validation rules
     * from within the app, an exception will be thrown
     *
     * @throws \Exception
     * @param array
     */
    public function setValidationRulesAttribute(array $validationRules)
    {
        throw new \Exception('The validation rules are immutable!');
    }

    public static function reservation_limit_set($limit)
    {   
        \Cache::forget('site');
        $setting = Setting::query()->firstOrNew(
            ['key' => 'site'],
            [
                'view_name' => 'settings.site',
                'title' => 'Site Settings',
                'description' => '',
            ]
        );
        $config['default_reservation_records']=$limit;
        
        $setting->updateConfig($config);
        
        return $setting;
    }
}
