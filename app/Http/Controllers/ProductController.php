<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\PriceGrouping;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->has('type') && $request->type === 'admin'){
            $products = Product::with(['productCategory', 'priceGrouping'])->latest()->paginate(25);
        } else{
            $products = auth()->user()->products()->with(['productCategory', 'priceGrouping'])->latest()->paginate(25);
            //return $products;
        }
        $productCategories = ProductCategory::all();
        $priceGroupings = PriceGrouping::all();
        $discounts = Discount::all();
        return $request->wantsJson()
            ? new JsonResponse(['products' => $products, 'productCategories' => $productCategories, 'priceGroupings' => $priceGroupings, 'discounts' => $discounts], 200)
            : view('pages.products.index', compact('products', 'productCategories', 'priceGroupings', 'discounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'price_grouping_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::create([
                'product_category_id' => $request->product_category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price_grouping_id' => $request->price_grouping_id,
                'user_id' => auth::user()->id
            ]);
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse([$product, 'message' => 'Product Created Successfully'], 200)
                : back()->with('success', 'Product Created Successfully');
        } catch(\Exception $e){
            DB::rollBack();
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product)
    {
        $discounts = Discount::latest()->get();
        return $request->wantsJson()
            ? new JsonResponse([$product, $discounts, 'message' => 'Product Created Successfully'], 200)
            : view('pages.products.show', compact('product', 'discounts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'product_category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'price_grouping_id' => 'required',
        ]);
        try {
            DB::beginTransaction();

            $product->update($request->all());
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse([$product, 'message' => 'Product Updated Successfully'], 200)
                : back()->with('success', 'Product Updated Successfully');
        } catch(\Exception $e){
            DB::rollBack();

            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();
            $product->delete();
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse([$product, 'message' => 'Product Deleted Successfully'], 200)
                : back()->with('success', 'Product Deleted Successfully');
        }
        catch(\Exception $e){
            DB::rollBack();
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }
    }


    /**
     * Add Image the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadProductImage(Request $request)
    {
        /*try {
            DB::beginTransaction();
            $product->delete();
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse([$product, 'message' => 'Product Deleted Successfully'], 200)
                : back()->with('success', 'Product Deleted Successfully');
        }
        catch(\Exception $e){
            DB::rollBack();
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }*/
    }
}
