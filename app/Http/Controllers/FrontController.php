<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\StaticPage;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	protected $baseTemplate = '';

	public function welcome()
	{
		$page=StaticPage::find(1);
		if (!$page) {
			return redirect('login');
		}
		return view('welcome',compact('page'));
	}

}
