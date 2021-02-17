<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\ProductImage;
use App\Hashers\MainHasher;
use Illuminate\Support\Facades\Storage;

class ProductImageTransformer extends TransformerAbstract
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
    public function transform(ProductImage $model)
    {
        $result = $model->toArray();
        $result["id"] = MainHasher::encode($result["id"]);
        $result["id_product"] = MainHasher::encode($result["id_product"]);
        $result["image"] = \env('APP_URL').Storage::url($model->image);
        $result["storage_path_image"] = $model->image;
        $result["relationships"] = [
            'product' => $model->product,
        ];
        return $result;
    }
}
