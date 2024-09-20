<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'static_pages';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title', 'content', 'meta_title', 'meta_description', 'meta_keywords','button_text','button_link'];

	public function files()
	{
		return $this->morphMany('\App\FileEntry', 'fileable');
	}

}
