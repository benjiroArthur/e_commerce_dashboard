@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <orders-component :orders="{{json_encode($orders)}}" order-route="{{route('orders.store')}}">
                    <div slot="pagination" class="col-md-12 mb-0 pb-0 d-flex bg-transparent justify-content-center">
                        <span>{!!  $orders->withQueryString()->links()  !!}</span>
                    </div>
                </orders-component>
            </div>
        </div>
    </div>
@endsection
