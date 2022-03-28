<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentTypeController extends Controller
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
        $paymentTypes = PaymentType::all();
        return $request->wantsJson()
            ? new JsonResponse(['paymentTypes' => $paymentTypes], 200)
            : view('pages.payment-types.index', compact('paymentTypes'));
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
            'name' => 'required|unique:payment_types',
        ]);
        try {
            DB::beginTransaction();
            $paymentType = new PaymentType();
            $paymentType->create($request->all());
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse([$paymentType, 'message' => 'Payment Type Created Successfully'], 200)
                : back()->with('success', 'Payment Type Created Successfully');
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
     * @param  \App\Models\PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentType $paymentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentType $paymentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentType $paymentType)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $paymentType->update($request->all());
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse([$paymentType, 'message' => 'Payment Type Updated Successfully'], 200)
                : back()->with('success', 'Payment Type Updated Successfully');
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
     * @param \App\Models\PaymentType $paymentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PaymentType $paymentType)
    {
        try {
            DB::beginTransaction();
            $paymentType->delete();
            DB::commit();
            return $request->wantsJson()
                ? new JsonResponse(['message' => 'Payment Type Deleted Successfully'], 200)
                : back()->with('success', 'Payment Type Deleted Successfully');
        } catch(\Exception $e){
            DB::rollBack();
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }
    }
}
