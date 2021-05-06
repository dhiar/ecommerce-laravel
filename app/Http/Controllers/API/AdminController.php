<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\AdminTransformer;
use App\Admin;
use Illuminate\Support\Facades\DB;
use App\Hashers\MainHasher;
use Illuminate\Support\Facades\Schema;
use Spatie\Tags\Tag;
use Auth;

class AdminController extends Controller
{
    // use Auth;
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $this->middleware('guest', ['only' => [
            'filterSellers'
        ]]);
        $this->model = new Admin();
        $this->table = $this->model->getTable();
        $this->transformer = new AdminTransformer();
    }

    public function filterSellers(CommonRequest $request){
        $model = $this->model;

        $name = $request->name;
        $province_id = $request->province_id;
        $city_id = $request->city_id;
        $district_id = $request->district_id;

        if ($request->id_seller == 0) {
            $model = $model->whereHas('address',  function($qAddress) use ($name, $province_id, $city_id, $district_id) {
                $qAddress->where('name', 'LIKE', "%".$name."%")
                ->where('province_id', 'LIKE', "%$province_id%")
                ->where('city_id', 'LIKE', "%$city_id%")
                ->where('district_id', 'LIKE', "%$district_id%");
            });
        } else {
            $model = $model->where('id', $request->id_seller );
        }

        $fieldOrder = $request->fieldOrder ?? "id";
        $sort = $request->sort ?? "ASC";
        $limit = request('limit') ?? 10;

        return $request->index($model, $this->transformer, $fieldOrder, $sort, $limit, $this->table);
    }
}
