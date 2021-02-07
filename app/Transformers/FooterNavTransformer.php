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
        // $footers = Footer::whereIdNavigation($object->id_navigation)->get();
        // fractal($footers , FooterTransformer::class)->toArray()['data'],

        $id_navigation = $object->id_navigation;
        $navigation = Navigation::find($id_navigation);
        $navPages = Navigation::find($id_navigation)->pages;

        $pages = [];
        foreach ($navPages as $page) {
            $pages[] = [
                'id' => MainHasher::encode($page->id),
                'title' => $page->title
            ];
        }
        
        return [
            'navigation' => fractal($navigation , NavigationTransformer::class)->toArray()['data'],
            'pages' => $pages
        ];
    }
}
