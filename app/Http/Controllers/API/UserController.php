<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Address, User};
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
					'name' => 'required|string|max:255',
					'address' => 'required|string|max:255',
					'email' => 'required|string|email|max:255|unique:users',
					'password' => 'required|string|min:8|confirmed',
					'gender' => 'required|string|min:1',
					'phone' => 'required|numeric|regex:/^\S*$/u',
					'user_type' => 'required|numeric'					
        ]);
				
				DB::beginTransaction();
				try {
					$address = new Address();
					$address->name = $request->address;
					$address->save();

					if ($address->id) {
							$user = new User();
							$user->name = $request->name;
							$user->gender = $request->gender;
							$user->email = $request->email;
							$user->phone = $request->phone;
							$user->password = bcrypt($request->password);
							$user->id_user_type = $request->user_type;
							$user->id_address = $address->id;
							$user->save();
					}

					DB::commit();
					return $user;
			}
			catch (\Exception $e) {
					return response()->json([
							'success' => false,
							'data' => [],
							'message' => 'Failed create user',
					]);
					DB::rollback();
			}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
			$user = User::findOrFail($id);

			$user->delete();
			$user->name = 'old-'.$user->name;
			$user->email = 'old-'.$user->email;
			$user->update();

			return ['message' => 'User Deleted.'];
    }
}
