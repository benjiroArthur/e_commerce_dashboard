<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="authUser" content="{{ auth::user() }}">
    @else
        <meta name="authUser" content="{{ json_encode([]) }}">
    @endauth

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @auth
        @php
            $adminLinks = [
                ['name' => 'Dashboard', 'link' => route('home'), 'hasSub' => false, 'icon' => 'mdi-home'],
                ['name' => 'User Management', 'link' => route('users.index'), 'hasSub' => false, 'icon' => 'mdi-account-group'],
                ['name' => 'Manage Products', 'hasSub' => true, 'icon' => 'mdi-home',
                    'subLinks' => [
                        ['name' => 'Products', 'link' => route('products.index').'?type=admin', 'icon' => 'mdi-home'],
                        ['name' => 'Products Category', 'link' => route('product-category.index'), 'icon' => 'mdi-home']
                    ]
                ],
                ['name' => 'Manage Discounts', 'link' => route('discounts.index'), 'hasSub' => false, 'icon' => 'mdi-home'],
                ['name' => 'Manage Price', 'link' => route('price-grouping.index'), 'hasSub' => false, 'icon' => 'mdi-home'],
                //['name' => 'Orders', 'link' => route('orders.index').'?type=admin', 'hasSub' => false, 'icon' => 'mdi-home'],
                ['name' => 'Payments', 'link' => route('payments.index').'?type=admin', 'hasSub' => false, 'icon' => 'mdi-home'],
                ['name' => 'Configurations', 'hasSub' => true, 'icon' => 'mdi-home',
                    'subLinks' => [
                        ['name' => 'Progress Status', 'link' => route('status.index'), 'icon' => 'mdi-home'],
                        ['name' => 'Payment Types', 'link' => route('payment-type.index'), 'icon' => 'mdi-home']
                    ]
                ],

            ];
            $vendorLinks = [
                ['name' => 'Dashboard', 'link' => route('home'), 'hasSub' => false, 'icon' => 'mdi-home'],
                ['name' => 'Products', 'link' => route('products.index').'?type=vendor', 'hasSub' => false, 'icon' => 'mdi-store'],
                //['name' => 'My Orders', 'link' => route('orders.index').'?type=vendor', 'hasSub' => false, 'icon' => 'mdi-storefront'],
                ['name' => 'Payments', 'link' => route('payments.index').'?type=admin', 'hasSub' => false, 'icon' => 'mdi-cash-multiple'],

            ];
            $customerLinks = [
                ['name' => 'Market Place', 'link' => route('home'), 'hasSub' => false, 'icon' => 'mdi-store'],
                //['name' => 'My Orders', 'link' => route('orders.index').'?type=customer', 'hasSub' => false, 'icon' => 'mdi-storefront'],
            ];
            if(auth::user()->account_type === 'admin'){
                $routeLinks = $adminLinks;
            } elseif (auth::user()->account_type === 'vendor'){
                $routeLinks = $vendorLinks;
            } else{
                $routeLinks = $customerLinks;
            }

        @endphp
    @endauth

    <div id="app">
        <v-app>
        {{--<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>--}}
            @auth
                <regular-user-layout
                    logout-url="{{route('logout')}}"
                    user-profile="{{route('logout')}}"
                    change-password="{{route('logout')}}"
                    :route-links="{{json_encode($routeLinks)}}"
                >
                    <div class="d-flex justify-content-end px-5" slot="message" style="z-index:30">
                        @include('layouts.messages')
                    </div>
                    <main class="py-4" slot="main">

                        @yield('content')
                    </main>
                </regular-user-layout>
            @else
                <div class="" slot="message" style="z-index:30">
                    @include('layouts.messages')
                </div>
                <main class="py-4">
                    @yield('content')
                </main>
            @endauth

        </v-app>
    </div>

    <script type="text/javascript">
        (function() {
            setTimeout(function() {
                let element = document.querySelector('.alert');
                if(element){
                element.remove()
                }
            }, 5000);

        })();
        /*$(document).ready(function() {
            setTimeout(function() {
                document.querySelector('.alert').remove();
                //$('.alert').remove();
            }, 5000);
        });*/
    </script>
    @yield('scripts')
</body>
</html>
