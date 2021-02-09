<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\{Footer, Navigation, Page};
use App\Hashers\MainHasher;
use App\Transformers\{FooterTransformer, PageTitleTransformer};

class FooterNavTransformer extends TransformerAbstract
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
    public function transform($object)
    {
        // $footers = Footer::whereIdNavigation($object->navigation_id)->get();
        // fractal($footers , FooterTransformer::class)->toArray()['data'],

        $navigation_id = $object->navigation_id;
        $navigation = Navigation::find($navigation_id);
        $navPages = Navigation::find($navigation_id)->pages;

        $pages = [];
        foreach ($navPages as $page) {
            $footer =$navigation->footers->where('page_id',$page->id)->first();
            $pages[] = [
                'id' => MainHasher::encode($page->id),
                'title' => $page->title,
                'navigation_id' => $navigation->id,
                'footer' =>fractal($footer , FooterTransformer::class)->toArray()['data']
            ];
        }
        
        return [
            'navigation' => fractal($navigation , NavigationTransformer::class)->toArray()['data'],
            'pages' => $pages
        ];
    }
}
