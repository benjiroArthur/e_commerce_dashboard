@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <status-component :statuses="{{json_encode($statuses)}}" status-route="{{route('status.store')}}">
                </status-component>
            </div>
        </div>
    </div>
@endsection
