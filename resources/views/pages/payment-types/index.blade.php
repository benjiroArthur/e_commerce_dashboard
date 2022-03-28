@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <payment-type-component :payment-types="{{json_encode($paymentTypes)}}" payment-type-route="{{route('payment-type.store')}}">
                </payment-type-component>
            </div>
        </div>
    </div>
@endsection
