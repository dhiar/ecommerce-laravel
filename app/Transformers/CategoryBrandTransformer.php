<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\CategoryBrand;
use App\Hashers\MainHasher;
use Illuminate\Support\Facades\Storage;
use App\Transformers\{ProductBrandTransformer, ProductCategoryTransformer};

class CategoryBrandTransformer extends TransformerAbstract
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
    public function transform(CategoryBrand $model)
    {
        return [
            'id' => MainHasher::encode($model->id),
            'id_category' => MainHasher::encode($model->id_category),
            'id_brand' => MainHasher::encode($model->id_category),
            'relationships' => [
                'category' => [
                    'id' => MainHasher::encode($model->category->id),
                    'name' => $model->category->name,
                    'slug' => $model->category->slug,
                    'icon' => $model->category->icon,
                ],
                'brand' => [
                    'id' => MainHasher::encode($model->brand->id),
                    'name' => $model->brand->name,
                    'slug' => $model->brand->slug,
                ],
            ]
        ];
    }
}
