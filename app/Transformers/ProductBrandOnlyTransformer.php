<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\ProductBrand;
use App\Hashers\MainHasher;
use Illuminate\Support\Facades\Storage;
use App\Transformers\CategoryBrandTransformer;

class ProductBrandOnlyTransformer extends TransformerAbstract
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
    public function transform(ProductBrand $model)
    {
        return [
            'id' => MainHasher::encode($model->id),
            'name' => $model->name,
            'slug' => $model->slug,
        ];
    }
}
