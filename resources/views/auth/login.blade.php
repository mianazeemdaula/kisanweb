@extends('layouts.guest')
@section('body')
    <!-- component -->
    <section class="min-h-screen flex flex-col">
        <nav class="flex justify-center items-center">
            <div class="px-4 py-4 font-bold text-3xl">
                Kisan Stock
            </div>
        </nav>
        <div class="flex justify-center items-center">
            @if (isset($errors) && count($errors) > 0)
                <div class="alert alert-warning" role="alert">
                    <ul class="list-unstyled mb-0 text-red-500">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="flex flex-1 items-center justify-center">
            <div class="rounded-lg sm:border-2 px-4 lg:px-24 py-16 lg:max-w-xl sm:max-w-md w-full text-center">
                <form class="text-center" action="{{ route('login') }}" method="POST">
                    @csrf
                    <h1 class="font-bold tracking-wider text-3xl mb-8 w-full text-gray-600">
                        Sign in
                    </h1>
                    <div class="py-2 text-left">
                        <input name="username" type="text"
                            class="border-2 border-gray-100 focus:outline-none bg-gray-100 block w-full py-2 px-4 rounded-lg focus:border-gray-700 "
                            placeholder="Email" />
                    </div>
                    <div class="py-2 text-left">
                        <input name="password" type="password"
                            class="border-2 border-gray-100 focus:outline-none bg-gray-100 block w-full py-2 px-4 rounded-lg focus:border-gray-700 "
                            placeholder="Password" />
                    </div>
                    <div class="py-2">
                        <button type="submit"
                            class="border-2 border-gray-100 focus:outline-none bg-green-600 text-white font-bold tracking-wider block w-full p-2 rounded-lg focus:border-gray-700 hover:bg-green-700">
                            Sign In
                        </button>
                    </div>
                </form>
                <div class="text-center">
                    <a href="#" class="hover:underline">Forgot password?</a>
                </div>
            </div>
        </div>
    </section>
@endsection
