<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\SearchRepository;

 class SearchController extends Controller
{
    /**
     * SearchRepository object
     *
     * @var \Webkul\Product\Repositories\SearchRepository
    */
    protected $searchRepository;
    protected $categoryRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\SearchRepository  $searchRepository
     * @return void
    */
    public function __construct(SearchRepository $searchRepository, CategoryRepository $categoryRepository)
    {
        $this->searchRepository = $searchRepository;
        $this->categoryRepository = $categoryRepository;

        parent::__construct();
    }

    /**
     * Index to handle the view loaded with the search results
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $results = $this->searchRepository->search(request()->all());

        return view($this->_config['view'])->with('results', $results->count() ? $results : null);
    }


    public function allSearch()
    {
        $results = $this->searchRepository->search(request()->all());

        return view($this->_config['view'])->with('results', $results->count() ? $results : null);
    }



    public function all()
    {
        $request = request()->all();

        $channel = core()->getRequestedChannelCode();

        $locale = core()->getRequestedLocaleCode();

        $categories = $this->categoryRepository->getPartial();

        $items = array();

        foreach (config('product_types') as $item) {
            $item['children'] = [];

            array_push($items, $item);
        }

        $types = core()->sortItems($items);

        $colors = DB::table('attribute_options')
            ->where('attribute_id', 23)
            ->get();

        $sizes = DB::table('attribute_options')
            ->where('attribute_id', 24)
            ->get();

        $results = app(ProductFlatRepository::class)->scopeQuery(function ($query) use ($channel, $locale, $request) {

            $query = $query->distinct()
                ->addSelect('product_flat.*')
                ->join('product_flat as variants', 'product_flat.id', '=', DB::raw('COALESCE(' . DB::getTablePrefix() . 'variants.parent_id, ' . DB::getTablePrefix() . 'variants.id)'))
                ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale)
                ->whereNotNull('product_flat.url_key');

            if (!empty(Arr::get($request, 'category', ''))) {
                $query = $query->whereIn('product_categories.category_id', Arr::get($request, 'category', []));
            }

            if (!empty(Arr::get($request, 'type', ''))) {
                $query = $query->whereIn('products.type', Arr::get($request, 'type', []));
            }

            if (!empty(Arr::get($request, 'size', ''))) {
                $query = $query->whereIn('variants.size', Arr::get($request, 'size', []));
            }

            if (!empty(Arr::get($request, 'color', ''))) {
                $query = $query->whereIn('variants.color', Arr::get($request, 'color', []));
            }

//            if (!empty(Arr::get($request, 'priceFrom', ''))) {
//                $query = $query->where('variants.price', '>=', urldecode(Arr::get($request, 'priceFrom', '')));
//            }

            if (!empty(Arr::get($request, 'priceTo', ''))) {
                $query = $query->where('variants.price', '<=', Arr::get($request, 'priceTo', ''));
            }

            if (! core()->getConfigData('catalog.products.homepage.out_of_stock_items')) {
                $query = $this->checkOutOfStockItem($query);
            }

            return $query->where('product_flat.status', 1)
                ->where('product_flat.visible_individually', 1)
                ->orderBy('product_id', 'desc');
        })->paginate(16);

        return view($this->_config['view'])->with('results', $results->count() ? $results : null)->with(compact('categories', 'types', 'colors', 'sizes'));
    }
}
