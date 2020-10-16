<?php

namespace App\Http\Controllers\Auth;

use App\Address;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RegisterController extends Controller
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'numeric', 'regex:/^\S*$/u'],
            'address' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    // protected function create(array $data)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $address = new Address();
    //         $address->name = $data['address'];
    //         $address->save();

    //         if ($address->id) {
    //             $user = new User();
    //             $user->name = $data['name'];
    //             $user->email = $data['email'];
    //             $user->password = bcrypt($data['password']);
    //             $user->id_user_type = 3;
    //             $user->id_address = $address->id;
    //             $user->phone = $data['phone'];
    //             $user->save();
    //         }

    //         DB::commit();
    //         return $user;
    //     }
    //     catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'data' => [],
    //             'message' => 'Failed create user',
    //         ]);
    //         DB::rollback();
    //     }
    // }

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $address = new Address();
            $address->name = $request->address;
            $address->save();

            if ($address->id) {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->id_user_type = 3;
                $user->id_address = $address->id;
                $user->phone = $request->phone;
                $user->verification_code = sha1(time());
                $user->save();
            }
            DB::commit();
        }
        catch (\Exception $e) {
            $user = null;
            DB::rollback();
        }
    
        if ($user) {
            // Send Email
            MailController::sendSignupEmail($user->name, $user->email, $user->verification_code);

            // Show Message
            return redirect()->back()->with(session()->flash('alert-success', 'Akun anda sudah ter-create. Silakan check email link verifikasi! Pendaftaran berhasil.
                Harap periksa juga folder spam, promotions, all inbox atau semisalnya di email Anda.'));
        } else {
            return redirect()->back()->with(session()->flash('alert-danger', 'Terjadi kesalahan. Silakan hubungi admin!'));
        }
            
    }

    public function verifyUser(Request $request)
    {  
        $verifitation_code = $request->code;
        $user = User::whereVerificationCode($verifitation_code)->first();

        if ($user) {
            $user->is_verified = 1;
            $user->update();
            return redirect()->route('login')->with(session()->flash('alert-success', 'Akun anda sudah ter-verifikasi. Silakan login!'));
        }
        return redirect()->route('login')->with(session()->flash('alert-danger', 'Invalid verification code. Please check your email!'));
    }
}
