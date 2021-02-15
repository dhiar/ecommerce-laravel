<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Product;
use App\Hashers\MainHasher;
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
        $result = $model->toArray();
        $result["id"] = MainHasher::encode($result["id"]);
        $result["id_product_category"] = MainHasher::encode($result["id_product_category"]);
        $result["id_admin"] = MainHasher::encode($result["id_admin"]);
        $result["image"] = \env('APP_URL').Storage::url($model->image);
        $result["relationships"] = [
            'category' => $model->category,
            'admin' => $model->admin,
        ];
        return $result;
    }
}
