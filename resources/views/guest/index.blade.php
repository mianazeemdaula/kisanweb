@extends('layouts.guest')
@section('body')
    <!-- component -->
    <div class="py-4 mt-2 bg-green-50">
        <div class="marquee">
            <ul class="crop-rates">
                @foreach (\App\Models\CropRate::take(20)->get() as $item)
                    <li> {{ $item->city->name }} {{ $item->min_price }} - {{ $item->max_price }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="flex items-center justify-center h-screen bg-gray-200">
        <div class="container">
            <div class="bg-white rounded-lg shadow-lg p-5 md:p-20 mx-2">
                <div class="text-center">
                    <h2
                        class="text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl ">
                        Kisan<span class="text-green-600">Stock</span>
                    </h2>
                    <h3 class='text-xl md:text-3xl mt-10'>Coming Soon</h3>
                    <p class="text-md md:text-xl mt-10"><a class="hover:underline"
                            href="https://www.kisanstock.com">kisanstock</a> is a website help you provide buying and
                        selling facilities for crops.</p>
                </div>
                <div class="flex flex-wrap mt-10 justify-center">
                    <div class="m-3">
                        <a href="https://www.facebook.com/kisanstock" title="KisanStock On Facebook"
                            class="md:w-32 bg-white tracking-wide text-gray-800 font-bold rounded border-2 border-blue-600 hover:border-blue-600 hover:bg-blue-600 hover:text-white shadow-md py-2 px-6 inline-flex items-center">
                            <span class="mx-auto">Facebook</span>
                        </a>
                    </div>
                    <div class="m-3">
                        <a href="https://tiktok.com/@kisanstock" title="Quicktoolz On TikTok"
                            class="md:w-32 bg-white tracking-wide text-gray-800 font-bold rounded border-2 border-blue-500 hover:border-blue-500 hover:bg-blue-500 hover:text-white shadow-md py-2 px-6 inline-flex items-center">
                            <span class="mx-auto">TikTok</span>
                        </a>
                    </div>
                    <div class="m-3">
                        <a href="https://youtube.com/@kisanstock/" title="KisanStock On Youtube"
                            class="md:w-32 bg-white tracking-wide text-gray-800 font-bold rounded border-2 border-red-600 hover:border-red-600 hover:bg-red-600 hover:text-white shadow-md py-2 px-6 inline-flex items-center">
                            <span class="mx-auto">Youtube</span>
                        </a>
                    </div>
                    <div class="m-3">
                        <a href="https://play.google.com/store/apps/details?id=com.kisanstock.app"
                            title="KisanStock On Playstore"
                            class="md:w-64 bg-white tracking-wide text-gray-800 font-bold rounded border-2 border-orange-500 hover:border-orange-500 hover:bg-orange-500 hover:text-white shadow-md py-2 px-6 inline-flex items-center">
                            <span class="mx-auto">Download App</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
