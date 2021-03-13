<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Product;
use App\Hashers\MainHasher;
use App\Transformers\{AdminTransformer, ProductImageOnlyTransformer, ProductCategoryTransformer, ProductBrandOnlyTransformer};
use App\Transformers\TagsTransformer;
use Illuminate\Support\Facades\Storage;
use Spatie\Tags\Tag;

class ProductTagTransformer extends TransformerAbstract
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
        $product = new Product();
        $productHasTags = $product->whereHas('tags', function ($query) {
            $query->where('tags.id', ">", 0);
        })->orWhere('id',$model->id)->limit(100)->get()->toArray();


        $allTags = [];
        foreach($productHasTags as $product) {
            $rez = Product::find($product['id']);
            $result = $rez->tags()->get();
            $tags = fractal($result, new TagsTransformer())->toArray()['data'];

            $allTags = array_merge($allTags, $tags);
        }
        $allTags = array_map("unserialize", array_unique(array_map("serialize", $allTags)));
        $resultTags = [];
        foreach($allTags as $tag) {
            array_push($resultTags, $tag);
        }

        $category = fractal($model->category_brand->category, new ProductCategoryTransformer())->toArray()['data'];
        $brand = fractal($model->category_brand->brand, new ProductBrandOnlyTransformer())->toArray()['data'];
        $tags = $model->tags()->get();

        $result = [];
        $result["id"] = MainHasher::encode($model["id"]);
        $result["id_category_brand"] = MainHasher::encode($model["id_category_brand"]);
        $result["id_category"] = $category["id"];
        $result["id_brand"] = $brand["id"];
        $result["relationships"] = [
            'category' => $category,
            'brand' => $brand,
            'tags' => fractal($model->tags()->get(), new TagsTransformer())->toArray()['data'],
            'all_tags' => $resultTags,
        ];
        return $result;
    }
}
