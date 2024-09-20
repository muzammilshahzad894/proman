<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class FileEntry extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'files';

	// TODO: eliminate the feature of manually naming files
	protected $fillable = ['file_name', 'file_display_order'];

	/**
	 * Returns file_name + extension
	 *
	 * @return string
	 */
	public function fullName()
	{
		return $this->file_name . '.' . $this->file_extension;
	}

	public function fileable()
	{
		return $this->morphTo();
	}

}
