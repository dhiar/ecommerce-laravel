<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Providers\RouteServiceProvider;
use App\{Admin, Address};
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME_ADMIN;
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
	{
		$this->middleware('guest:admin');
	}

    public function showRegisterForm()
	{
		return view('auth.admin-register');
	}

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|numeric|regex:/^\S*$/u',
        ]);

        DB::beginTransaction();
        try {
            $address = Address::create(['name' => 'Indonesia']);

            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = bcrypt($request->password);
            $admin->id_user_type = 3;
            $admin->phone = $request->phone;
            $admin->id_address = $address->id;
            $admin->job_title = 'Manage & monitor owner product.';
            $admin->save();
            DB::commit();
        }
        catch (\Exception $e) {
            $admin = null;
            DB::rollback();
        }
    
        if ($admin) {
            // Show Message
            return redirect()->back()->with(session()->flash('alert-success', 'Registrasi admin berhasil.'));
        } else {
            return redirect()->back()->with(session()->flash('alert-danger', 'Terjadi kesalahan. Silakan hubungi Admin!'));
        }           
    }
}
