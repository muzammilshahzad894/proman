<?php namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class Attachment extends Base
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'title',
        'filename',
        'property_id',
        'order',
        'main',
        'status',
    ];
    
    protected $appends = [
        'file_size', 'file_url'
    ];

    /**
     * @return int
     */
    function getFileSizeAttribute()
    {
        $size = 0;
        $file = public_path('uploads/properties/' . $this->filename);
        if (File::exists($file)) {
            $size = File::size($file);
        }
        return $size;
    }

    /**
     * @return string
     */
    function getFileUrlAttribute()
    {
        return url('uploads/properties/' . $this->filename);
    }
}
