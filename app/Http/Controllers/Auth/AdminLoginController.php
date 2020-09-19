<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends Controller
{
	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/admin';

	public function __construct()
	{
		$this->middleware('guest:admin', ['except' => ['logout']]);
	}

	public function showLoginForm()
	{
		return view('auth.admin-login');
	}

	public function login(Request $request)
	{
		// Validate the form data
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required|min:8',
		]);

		// Attemp to log the user in
		if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) 
		{
			// If successful, then redirect to their intended location
			return redirect()->intended(route('admin.dashboard'));
		}

		// If unsuccessful, then redirect back to the login with the form data
		return redirect()->back()->withInput($request->only('email', 'remember'));
	}

	public function logout()
  {
    if (Auth::guard('admin')->check()) {
      Auth::guard('admin')->logout();
    } elseif (Auth::guard('user')->check()) {
      Auth::guard('user')->logout();
    }

    return redirect('/admin');

  }
}
