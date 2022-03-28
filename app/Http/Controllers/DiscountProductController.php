<?php

namespace App\Http\Controllers;

use App\Models\DiscountProduct;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountProductController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'product_id'=> 'required',
            'discount_id'=> 'required',
            'start_date'=> 'required',
            'end_date'=> 'required',
        ]);
        //dd($request->all());
        try {
            DB::beginTransaction();
            $discountProduct = new DiscountProduct();
            $discountProduct->create([
                'product_id'=> $request->product_id,
                'discount_id'=> $request->discount_id,
                'start_date'=> Carbon::parse($request->start_date.' 00:00:00')->format('d.m.Y H:i:s'),
                'end_date'=> Carbon::parse($request->end_date.' 23:59:59')->format('d.m.Y H:i:s'),
            ]);
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse([$discountProduct, 'message' => 'Product Discount Added Successfully'], 200)
                : back()->with('success', 'Product Discount Added Successfully');
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
     * @param  \App\Models\DiscountProduct  $discountProduct
     * @return \Illuminate\Http\Response
     */
    public function show(DiscountProduct $discountProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DiscountProduct  $discountProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(DiscountProduct $discountProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DiscountProduct  $discountProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DiscountProduct $discountProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param \App\Models\DiscountProduct $discountProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, DiscountProduct $discountProduct)
    {
        try {
            DB::beginTransaction();
            $discountProduct->delete();

            return $request->wantsJson()
                ? new JsonResponse(['message' => 'Product Discount Removed Successfully'], 200)
                : back()->with('success', 'Product Discount Removed Successfully');
        }
        catch(\Exception $e){
            DB::rollBack();
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }
    }
}
