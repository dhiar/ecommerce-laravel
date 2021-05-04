<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Address, Admin, User, Rekening};
use Illuminate\Support\Facades\DB;
use App\Transformers\{AdminTransformer, UserTransformer};
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Http\Transformers\IlluminatePaginatorAdapter;
use App\Helpers\PaginationFormat;
use App\Hashers\MainHasher;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// example
		// $this->middleware('auth', ['only' => 'index']);
		// $this->middleware('auth', ['except' => ['create', 'store', 'edit', 'update', 'delete']]);

		$this->middleware('auth:admin-api', ['profile', 'updateProfile', 'updateRekening']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$request = Request('q');
		
		if ($request) {
			$paginator = User::where(function($query) use ($request) {
				$query
				->where('name', 'LIKE', "%$request%")
				->orWhere('phone', 'LIKE', "%$request%")
				->orWhere('email', 'LIKE', "%$request%");
			})->paginate(10);
		} else {
			$paginator = User::latest()->paginate(10);
		}

		$user = $paginator->getCollection();

		$response = fractal()
								->collection($user, new UserTransformer())
								->paginateWith(new IlluminatePaginatorAdapter($paginator))
								->toArray();
		
		return PaginationFormat::commit($paginator, $response);
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
			'password' => 'required|string|min:8|confirmed',
			'address' => 'required|string|max:255',
			'email' => 'required|string|email|max:191|unique:users',
			'gender' => 'required|string|min:1',
			'phone' => 'required|numeric|regex:/^\S*$/u',
			'id_user_type' => 'required|numeric'					
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
				$user->id_user_type = $request->id_user_type;
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
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateProfile(Request $request)
	{
		$adminId = Auth::id();

		$this->validate($request, [
			'name' => 'required|string|max:255',
			'password' => 'sometimes|string|min:8|confirmed',
			'job_title' => 'required|string|max:255',
			'email' => 'required|string|email|max:191|unique:admins,email,'.Auth::id(),
			'phone' => 'required|numeric|regex:/^\S*$/u'
		]);

		$user = Admin::findOrFail($adminId);
		$currentPhoto = $user->photo;

		if ($request->photo != $currentPhoto) {		
			$name = time().'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
			Image::make($request->photo)->save(public_path('img/profile/').$name);
			$request->merge(['photo' => $name]);

			// remove photo from server if exist
			$userPhoto = public_path('img/profile/').$currentPhoto;
			if (file_exists($userPhoto)) {
				@unlink($userPhoto);
			}
		}

		DB::beginTransaction();
		try {
			if(!empty($request->password)) {
				$request->merge(['password' => bcrypt($request->password)]);
			}

			$user->update($request->all());
			DB::commit();

			return ['message' => 'Success update user profile.'];
		}
		catch (\Exception $e) {
			return ['message' => 'Failed update user profile.'];
			DB::rollback();
		}
	}

	public function updateRekening(Request $request, $id) {
		$admin = Admin::find($id);
		$existingIds = $admin->rekenings()->get()->pluck('id');
		$syncIds = [];

		DB::beginTransaction();
		try {
			foreach (request('rekenings') as $key => $object) {
				if($object["id"])
				{
					$id = is_numeric($object["id"]) ? $object["id"] : MainHasher::decode($object["id"]);
					array_push($syncIds, $id);

					// update rekening dengan value baru
					$rekening = Rekening::find($id);
					$rekening->update([
						'rekening' => $object['rekening'],
						'number' =>  $object['number'],
					]);

				} else {
					// jika id null, maka create rekening , attach ke pivot
					$rekening = Rekening::create(['rekening'=> $object['rekening'], 'number' => $object['number']]);
					array_push($syncIds, $rekening->id);
				}
			}
			
			$admin->rekenings()->sync($syncIds); // sync ke pivot table
			foreach ($existingIds  as $idRekening) {
				// delete rekening yang sudah tidak dipakai oleh admin
				if ( !in_array($idRekening, $syncIds) ) {
					$rekening = Rekening::findOrFail($idRekening);
					$rekening->delete();
				}
			}

			DB::commit();
			return response()->json([
				'success' => true,
				'data' => [],
				'message' => 'Success update rekening',
			]);
		}
		catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'data' => [],
				'message' => 'Failed update rekening',
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
	public function profile()
	{
		return fractal(Auth::user(), AdminTransformer::class)->toArray()['data'];
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		
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
		$user = User::findOrFail($id);
		$id_address = $user->id_address;

		$this->validate($request, [
			'name' => 'required|string|max:255',
			'address' => 'required|string|max:255',
			'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
			'gender' => 'required|string|min:1',
			'phone' => 'required|numeric|regex:/^\S*$/u',
			'id_user_type' => 'required|numeric'					
		]);

		DB::beginTransaction();
		try {
			Address::find($id_address)->update(['name' => $request->address]);

			unset($request['address']);
			$user->update($request->all());

			DB::commit();

			return ['message' => 'Updated the user info.'];
		}
		catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'data' => [],
				'message' => 'Failed update user',
			]);
			DB::rollback();
		}
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

	public function search()
	{
		$request = Request('q');
		
		if ($request) {
			$paginator = User::where(function($query) use ($request) {
				$query
				->where('name', 'LIKE', "%$request%")
				->orWhere('phone', 'LIKE', "%$request%")
				->orWhere('email', 'LIKE', "%$request%");
			})->paginate(10);
		} else {
			$paginator = User::latest()->paginate(10);
		}

		$user = $paginator->getCollection();
		$response = fractal()
								->collection($user, new UserTransformer())
								->paginateWith(new IlluminatePaginatorAdapter($paginator))
								->toArray();
		return PaginationFormat::commit($paginator, $response);
	}
}
