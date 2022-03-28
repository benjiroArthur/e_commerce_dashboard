@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{--<users-component :users="{{json_encode($users)}}" :user-types="{{json_encode($userTypes)}}" user-route="{{route('users.store')}}">
                    <div slot="pagination" class="col-md-12 mb-0 pb-0 d-flex bg-transparent justify-content-center">
                        <span>{!!  $users->withQueryString()->links()  !!}</span>
                    </div>
                </users-component>--}}
            </div>
        </div>
    </div>
@endsection
