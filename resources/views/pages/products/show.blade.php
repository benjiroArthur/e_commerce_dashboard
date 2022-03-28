@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <product-details-component :product="{{json_encode($product->with('images', 'user', 'productCategory', 'priceGrouping', 'discounts')->first())}}" :product-category="{{json_encode($product->productCategory)}}"  product-image-route="{{route('products-image-upload')}}" products-route="{{route('products.index')}}?type={{auth()->user()->account_type}}"
                :discounts="{{json_encode($discounts)}}" product-discount-route="{{route('discount-product.store')}}">
                </product-details-component>
            </div>
        </div>
    </div>
@endsection

