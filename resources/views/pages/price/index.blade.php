@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <price-grouping-component :price-groupings="{{json_encode($priceGroupings)}}"  price-route="{{route('price-grouping.store')}}">
                    <div slot="pagination" class="col-md-12 mb-0 pb-0 d-flex bg-transparent justify-content-center">
                        <span>{!!  $priceGroupings->withQueryString()->links()  !!}</span>
                    </div>
                </price-grouping-component>
            </div>
        </div>
    </div>
@endsection
