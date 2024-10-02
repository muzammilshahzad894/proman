<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Log;
use Session;
use Cache;

class AdminController extends Controller
{
	public function index()
	{
		$users = User::where('type', 'admin')->orWhere('type', 'staff')->orderBy('created_at', 'desc')->get();

		return view('admin.users.index', [
			'users' => $users
		]);
	}

	/**
	 * Update User
	 * URL: /admin/users/{user} (POST)
	 *
	 * @param Request $request
	 * @param $user
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request, $id)
	{
		$data = $request->all();
		$user = User::find($id);
		// Validation
		$this->validate($request, [
			'name' => 'required|min:2|max:255',
			'email' => 'required|max:255|email|unique:users,email,' . $id,
			'type' => 'required'
		]);

		foreach (
			[
				'name',
				'email',
				'type',
			] as $field
		) {
			if (isset($data[$field]) && $data[$field] != $user->{$field}) {
				$user->{$field} = $data[$field];
			}
		}

		// Set the new password
		if (!empty($data['password'])) {
			// Validation
			$this->validate($request, [
				'password' => 'required|min:6|max:255|confirmed',
			]);
			$user->{'password'} = bcrypt($data['password']);
		}

		$user->save();
		Session::flash('success', 'Information has been updated successfully.');

		return redirect(route('admin.users.index', $user['id']));
	}

	public function edit($id)
	{
		$user = User::where('id', $id)->first();
		return view('admin.users.edit')->with('user', $user);
	}

	public function create()
	{
		$user  = '';
		return view('admin.users.create')->with('user', $user);
	}

	public function store(Request $request)
	{
		$data = $request->all();
		// Validation
		$this->validate($request, [
			'name' => 'required|min:2|max:255',
			'email' => 'required|max:255|email|unique:users,email',
			'password' => 'required|min:6|max:255|confirmed',
			'type' => 'required'
		]);

		$user =  User::create([
			'name' => $request['name'],
			'email' => $request['email'],
			'password' => bcrypt($request['password']),
			'type' => $request['type'],
		]);

		if (!empty($request->get('email_details'))) {
			$emailData = [
				'action' => 'email', // view , email
				'template' => 'admin-details',
				'subject' => 'Login details',
				'toName' => $user->name,
				'to' => $user->email,
				'emailContent' => [
					'user' => $user,
					'password' => $request['password'],
				]
			];
			// send
			try {
				sendEmail($emailData);
				Session::flash('success', 'New admin user has been created successfully');
			} catch (\Exception $e) {
				Session::flash('info', 'New admin user has been created successfully but system failed to send email to the user.');
				info("user sending email failed! ");
			}
		} else {
			Session::flash('success', 'New admin user has been created successfully');
		}

		return redirect(route('admin.users.index'));
	}

	public function destroy($id)
	{
		// delete admin, only if he's not an emergency admin
		$user = User::where('id', $id)
			->where('email', '!=', env('EMERGENCY_ADMIN_EMAIL'))
			->first();

		$user->delete();

		Cache::forget('general');
		return response()->json('success', 200);
	}
	
	public function get_user(Request $request )
    {
		try {
			$id = $request->get('id');
			$user = User::find($id);
			
			$fullName = explode(' ', $user->name, 2);
			
			$data = [
				'first_name' => $fullName[0] ?? '',
				'last_name' =>  $fullName[1] ?? '',
				'email' =>  $user->email,
				'phone' => $user->phone,
			];
			return response()->json($data, 200);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			return response()->json($e->getMessage(), 500);
		}
    }
}
