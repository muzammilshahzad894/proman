<?php namespace App\Http\Controllers\Traits;

use App\FileEntry;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

trait HandlesUploadedFiles {

	/**
	 * Assigns full name (filename + extension) to the $fileEntry
	 *
	 * @param Request $request
	 * @param UploadedFile $uploadedFile
	 * @return FileEntry
	 */
	protected function generateFileEntry(Request $request, UploadedFile $uploadedFile)
	{
		$fileEntry = new FileEntry($request->all());

		// set the file's mime type
		$fileEntry->file_extension = $uploadedFile->getClientOriginalExtension();

		// if filename consists only from extension, ex: .jpg without prefix
		if(!$fileEntry->file_name){
			$fileEntry->file_name = md5($uploadedFile->getFilename() . time());
		}

		return $fileEntry;
	}

	/**
	 * Stores a file into directory listed in .env file and
	 * saves a record about file path into the database
	 *
	 * @param string  $disk               - which disk to use (ex: panel, client, etc..)
	 * @param Request $request
	 * @param string  $fileInputFieldName - indicates the name, under which uploaded file is stored in $request
	 * @return \App\FileEntry $fileEntry
	 */
	public function storeUploadedFile($disk, Request $request, $fileInputFieldName = 'file')
	{
		$uploadedFile = $request->file($fileInputFieldName);

		$fileEntry = $this->generateFileEntry($request, $uploadedFile);

		// save the file in the filesystem
		Storage::disk($disk)->put($fileEntry->fullName(), File::get($uploadedFile))
			?: Log::error("The file couldn't be added to the filesystem.");

		// save the file info in the database
		$fileEntry->save()
			?: Log::error("The file couldn't be saved in the database.");

		return $fileEntry;
	}

	/**
	 * Deletes an uploaded file from both filesystem and database
	 *
	 * @param FileEntry $fileEntry
	 * @return boolean
	 */
	public function deleteUploadedFile(FileEntry $fileEntry)
	{
		$success = true;

		// attempt to delete the file from the filesystem
		$success = $success && File::delete(filePath($fileEntry->fullName()));

		// in case of failure, log the error and return false
		if(!$success){
			Log::error("The file {$fileEntry->fullName()}(id: {$fileEntry->id})
				couldn't be deleted from the filesystem.");

			return $success;
		}

		// attempt to delete the file from the database
		$success = $success && $fileEntry->delete();

		// in case of failure, log the error and return false
		if(!$success){
			Log::error("The file {$fileEntry->fullName()}(id: {$fileEntry->id})
				couldn't be deleted from the database.");
		}

		return $success;
	}

}
