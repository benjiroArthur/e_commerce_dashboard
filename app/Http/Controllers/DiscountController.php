<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
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
        $discounts = Discount::latest()->get();
        return $request->wantsJson()
            ? new JsonResponse(['discounts' => $discounts], 200)
            : view('pages.discounts.index', compact('discounts'));
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
            'rate' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $discount = new Discount();
            $discount->create($request->all());
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse([$discount, 'message' => 'Discount Created Successfully'], 200)
                : back()->with('success', 'Discount Created Successfully');
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
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discount $discount)
    {
        $this->validate($request, [
            'rate' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $discount->update($request->all());
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse([$discount, 'message' => 'Discount Updated Successfully'], 200)
                : back()->with('success', 'Discount Updated Successfully');
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
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Discount $discount)
    {
        try {
            DB::beginTransaction();
            $discount->delete();
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse(['message' => 'Discount Deleted Successfully'], 200)
                : back()->with('success', 'Discount Deleted Successfully');
        } catch(\Exception $e){
            DB::rollBack();
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }
    }
}
