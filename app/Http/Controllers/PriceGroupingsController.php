<?php

namespace App\Http\Controllers;

use App\Models\PriceGrouping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PriceGroupingsController extends Controller
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
        $priceGroupings = PriceGrouping::latest()->paginate(25);
        return $request->wantsJson()
            ? new JsonResponse(['priceGroupings' => $priceGroupings], 200)
            : view('pages.price.index', compact( 'priceGroupings'));
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
            'amount' => 'required',
        ]);
        try{
            DB::beginTransaction();
            $priceGrouping = PriceGrouping::create($request->all());
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse($priceGrouping, 200)
                : back()->with('success', 'Price Grouping Created Successfully');
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
     * @param  \App\Models\PriceGrouping  $priceGroupings
     * @return \Illuminate\Http\Response
     */
    public function show(PriceGrouping $priceGroupings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PriceGrouping  $priceGroupings
     * @return \Illuminate\Http\Response
     */
    public function edit(PriceGrouping $priceGroupings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PriceGrouping  $priceGrouping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PriceGrouping $priceGrouping)
    {
        $this->validate($request, [
            'amount' => 'required',
        ]);
        try{
            //dd($priceGroupings);
            DB::beginTransaction();
//            $priceGrouping = PriceGrouping::find($priceGroupings);
//            dd($priceGroupings);
            $priceGrouping->update($request->all());
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse($priceGrouping, 200)
                : back()->with('success', 'Price Grouping Updated Successfully');
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
     * @param \App\Models\PriceGrouping $priceGrouping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PriceGrouping $priceGrouping)
    {
        try {
            $priceGrouping->delete();
            return $request->wantsJson()
                ? new JsonResponse(['message' => 'Price Grouping Deleted Successfully'], 200)
                : back()->with('success', 'Price Grouping Deleted Successfully');
        } catch(\Exception $e){
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }
    }
}
