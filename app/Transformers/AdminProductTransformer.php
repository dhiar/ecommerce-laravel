<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Product;
use App\Hashers\MainHasher;
use App\Transformers\{AdminTransformer, ProductImageOnlyTransformer, ProductCategoryTransformer, ProductBrandOnlyTransformer};
use App\Transformers\TagsTransformer;
use Illuminate\Support\Facades\Storage;
use Spatie\Tags\Tag;

class AdminProductTransformer extends TransformerAbstract
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
        $tags = $model->tags()->get();

        $result = $model->toArray();
        $result["id"] = MainHasher::encode($result["id"]);
        $result["id_admin"] = MainHasher::encode($result["id_admin"]);
        $result["relationships"] = [
            'admin' => fractal($model->admin, new AdminTransformer())->toArray()['data'],
        ];

        unset($result['id_category_brand']);
        unset($result['condition']);
        unset($result['description']);
        unset($result['is_published']);
        unset($result['is_promo']);
        unset($result['transaction']);
        unset($result['count_view']);
        unset($result['count_like']);
        unset($result['category_brand']);
        unset($result['stock']);
        unset($result['id_category']);
        unset($result['id_brand']);
        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        return $result;
    }
}
