<?php

namespace Webkul\Shop\Http\Controllers;

use App\CategoriesContent;
use App\ProductCategories;
use Illuminate\Http\Request;
use Webkul\Product\Models\Product;
use Webkul\Category\Models\Category;
use Webkul\Core\Repositories\SliderRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Category\Repositories\CategoryRepository;

class ProductsCategoriesProxyController extends Controller
{
    /**
     * Category repository instance.
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Product repository instance.
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Slider repository instance.
     *
     * @var \Webkul\Core\Repositories\SliderRepository
     */
    protected $sliderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        SliderRepository $sliderRepository
    ) {
        $this->categoryRepository = $categoryRepository;

        $this->productRepository = $productRepository;

        $this->sliderRepository = $sliderRepository;

        parent::__construct();
    }

    /**
     * Show product or category view. If neither category nor product matches, abort with code 404.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Exception
     */
    public function index(Request $request)
    {
        $slugOrPath = trim($request->getPathInfo(), '/');

        $slugOrPath = urldecode($slugOrPath);

        // support url for chinese, japanese, arbic and english with numbers.
        if (preg_match('/^([\x{0621}-\x{064A}\x{4e00}-\x{9fa5}\x{3402}-\x{FA6D}\x{3041}-\x{30A0}\x{30A0}-\x{31FF}_a-z0-9-]+\/?)+$/u', $slugOrPath)) {

            if ($category = $this->categoryRepository->findByPath($slugOrPath)) {

                return view($this->_config['category_view'], compact('category'));
            }

            if ($product = $this->productRepository->findBySlug($slugOrPath)) {

                $breadcrumb = "<a href='/'>Home</a>";

               

                $categories_content = CategoriesContent::all();
                $categories = Category::orderBy('parent_id', 'desc')->get();
                // $categories = Category::all();
                $products = Product::all();
                $mycategories = ProductCategories::all();
                


                $customer = auth()->guard('customer')->user();

                return view($this->_config['product_view'], compact('product', 'customer', 'categories', 'products', 'mycategories', 'categories_content', 'breadcrumb'));
            }

            abort(404);
        }

        $sliderData = $this->sliderRepository->getActiveSliders();

        return view('shop::home.index', compact('sliderData'));
    }


}
