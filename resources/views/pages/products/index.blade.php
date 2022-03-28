@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <products-component
                    :products="{{json_encode($products)}}"
                    :product-categories="{{json_encode($productCategories)}}"
                    :price-groupings="{{json_encode($priceGroupings)}}"
                    product-route="{{route('products.store')}}">
                    <div slot="pagination" class="col-md-12 mb-0 pb-0 d-flex bg-transparent justify-content-center">
                        <span>{!!  $products->withQueryString()->links()  !!}</span>
                    </div>
                </products-component>
            </div>
        </div>
    </div>
@endsection
