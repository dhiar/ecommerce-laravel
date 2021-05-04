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

        // dd($model->get()->toArray());
        // dd(request()->all());

        // array:9 [
        //     "id_category" => null
        //     "id_brand" => null
        //     "name" => null
        //     "province_id" => null
        //     "city_id" => null
        //     "district_id" => null
        //     "id_seller" => "0"
        //     "limit" => 6
        //     "page" => "1"
        //   ]

        if ($request->id_seller == 0) {
            // dd($model->get()->toArray());
        } else {
            $model = $model->where('id', $request->id_seller );
            // dd('byId = ',$model->get()->toArray());
        }

        // id_category: this.categoryId,
        // id_brand: this.brandId,
        // product_tags: this.product_tags,
        // name: this.addressName,
        // province_id: this.addressProvinceId,
        // city_id: this.addressCityId,
        // district_id: this.addressDistrictId,
        // id_seller: this.seller.id,
        // limit: 6,

        // if (request('id_brand') || request('id_category')  )  {

        // }
        // if (request('id_brand') || request('id_category')  )  {
        //     $brandId = is_numeric(request('id_brand')) ? request('id_brand') : MainHasher::decode(request('id_brand'));
        //     $categoryId = is_numeric(request('id_category')) ? request('id_category') : MainHasher::decode(request('id_category'));

        //     if (request('id_category') && request('id_brand')) {
        //         $model = $model::whereHas('category_brand', function($query) use($categoryId, $brandId) { 
        //             $query->where('id_category', $categoryId)->where('id_brand', $brandId); 
        //         });
        //     } else {
        //         $model = $model::whereHas('category_brand', function($query) use($categoryId) { 
        //             $query->where('id_category', $categoryId); 
        //         });
        //     }
        // }
        // else if (
        //     request('province_id') || request('city_id')  || request('district_id') || request('name')
        // ) {
        //     $model = $model::whereHas('admin', function($query) { 
        //         $query->whereHas('address',  function($qAddress) {
        //             $name = request('name') ? request('name') : "";
        //             $province_id = request('province_id') ? request('province_id') : "";
        //             $city_id = request('city_id') ? request('city_id') : "";
        //             $district_id = request('district_id') ? request('district_id') : "";

        //             $qAddress->where('name', 'LIKE', "%".$name."%")
        //             ->where('province_id', 'LIKE', "%$province_id%")
        //             ->where('city_id', 'LIKE', "%$city_id%")
        //             ->where('district_id', 'LIKE', "%$district_id%");
        //         }); 
        //     });
        // }
        // else {
        //     $model = $this->model;
        // }

        // if (request('product_tags')) {
        //     $tags = collect(request('product_tags'))->pluck('name')->toArray();
        //     $model = $model::withAllTags($tags, 'product');
        // }

        // if (request('category_slug')) {
        //     $category_slug = request('category_slug');
        //     $category = ProductCategory::where('slug', $category_slug)->first();
        //     if($category->count() > 0) {
        //         $categoryId = $category->id;
        //         $model = $model::whereHas('category_brand', function($query) use($categoryId) { 
        //             $query->where('id_category', $categoryId); 
        //         });
        //     }
        // }

        $fieldOrder = $request->fieldOrder ?? "id";
        $sort = $request->sort ?? "ASC";
        $limit = request('limit') ?? 10;

        return $request->index($model, $this->transformer, $fieldOrder, $sort, $limit, $this->table);
    }
}
