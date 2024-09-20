<?php namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HandlesUploadedFiles;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Cache;

class StaticPageController extends Controller {

	use HandlesUploadedFiles;

	protected $validationRules;


	protected $baseTemplate = '';
	

	public function __construct()
	{
		$this->validationRules = [
			'file'                  =>  'required_with:file_display_order,file_name|max:2048|mimes:jpeg,jpg,png,gif,pdf',
			'file_name'             =>  'string|alpha_dash|max:255|unique:files',
			'file_display_order'    =>  'numeric'
		];
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$staticPage = \App\StaticPage::find($id)->load([
			//   sort the files by file_display_order values. in case
			// multiple files have the same display orders, the
			// recently created ones should come first
			'files' => function($query) {
				$query->orderBy('updated_at', 'DESC')
					->orderBy('file_display_order');
			}
		]);

		Cache::forget('pages_static');

		return $staticPage ?
			view($this->baseTemplate.'static_pages.edit', compact('staticPage')) : back();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update($id, Request $request)
	{

		//return $request->all();;
		//$this->validate($request, $this->validationRules);

		$staticPage = \App\StaticPage::find($id);
		$staticPage->update($request->all());
		Session::flash('success', 'The page has been updated successfully');

		if($request->hasFile('file')){
			$fileEntry = $this->storeUploadedFile('client', $request);
			$staticPage->files()->save($fileEntry);
		}
		 Cache::forget('pages_static');
		$static_page=\App\StaticPage::find($id);
			$static_page->button_link=$request->button_link;
			$static_page->button_text=$request->button_text;
			//$static_page->save();
			

		return back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $page_id
	 * @param int $file_id - id of the file that must be deleted
	 * @return Illuminate\Http\JsonResponse
	 */
	public function destroy_file($page_id, $file_id)
	{
		$fileEntry = \App\StaticPage::find($page_id)
			->files()
			->find($file_id);

		$success = $this->deleteUploadedFile($fileEntry);

		return response()->json(compact('success'));
	}
	/*ALTER TABLE `static_pages` ADD `button_text` VARCHAR(255) NULL DEFAULT NULL AFTER `updated_at`, ADD `button_link` VARCHAR(700) NULL DEFAULT NULL AFTER `button_text`;
ALTER TABLE `headers_posts` ADD `page_type` VARCHAR(255) NULL DEFAULT NULL AFTER `type`;

	*/

}
