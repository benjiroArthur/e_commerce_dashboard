@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <products-category-component :product-categories="{{json_encode($productCategories)}}" products-category-route="{{route('product-category.store')}}">
                    <div slot="pagination" class="col-md-12 mb-0 pb-0 d-flex bg-transparent justify-content-center">
                        <span>{!!  $productCategories->withQueryString()->links()  !!}</span>
                    </div>
                </products-category-component>
            </div>
        </div>
    </div>
@endsection
