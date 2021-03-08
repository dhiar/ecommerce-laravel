<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Product;
use App\Hashers\MainHasher;
use App\Transformers\{AdminTransformer, ProductImageOnlyTransformer, ProductCategoryTransformer, ProductBrandOnlyTransformer};
use Illuminate\Support\Facades\Storage;

class ProductTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $model)
    {
        $category = fractal($model->category_brand->category, new ProductCategoryTransformer())->toArray()['data'];
        $brand = fractal($model->category_brand->brand, new ProductBrandOnlyTransformer())->toArray()['data'];

        $result = $model->toArray();
        $result["id"] = MainHasher::encode($result["id"]);
        $result["id_category_brand"] = MainHasher::encode($result["id_category_brand"]);
        $result["id_admin"] = MainHasher::encode($result["id_admin"]);
        $result["id_category"] = $category["id"];
        $result["id_brand"] = $brand["id"];
        $result["image"] = \env('APP_URL').Storage::url($model->image);
        $result["relationships"] = [
            'category_brand' => $model->category_brand,
            'category' => $category,
            'brand' => $brand,
            'admin' => fractal($model->admin, new AdminTransformer())->toArray()['data'],
            'images' => fractal($model->images, new ProductImageOnlyTransformer())->toArray()['data'],
        ];
        return $result;
    }
}
