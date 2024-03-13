@extends('layouts.guest')
@section('body')
    <!-- component -->
    <section class="min-h-screen flex flex-col">
        
        <div class="flex items-center justify-center">
            <div class="rounded-lg sm:border-2 px-4 lg:px-24 py-16 lg:max-w-xl sm:max-w-md w-full text-center">
                <div class="">
                    @if (isset($errors) && count($errors) > 0)
                        <div class="alert alert-warning @if($errors->has('message')) text-green-500 @else text-red-500  @endif " role="alert">
                            <ul class="list-unstyled mb-">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="items-start text-left">
                        <div class="font-bold">Steps to remove user account</div>
                        <p>1-fill the form with your email and submit</p>
                        <p>2-If the email is valid an email send to your email with a delete user link</p>
                        <p>3-Click on the link to delete your account</p>
                    </div>
                </div>
                <form class="text-center" action="{{ url('remove-user') }}" method="POST">
                    @csrf
                    <div class="py-2 text-left">
                        <input name="email" type="text"
                            class="border-2 border-gray-100 focus:outline-none bg-gray-100 block w-full py-2 px-4 rounded-lg focus:border-gray-700 "
                            placeholder="Email" />
                    </div>
                    <div class="py-2">
                        <button type="submit"
                            class="border-2 border-gray-100 focus:outline-none bg-green-600 text-white font-bold tracking-wider block w-full p-2 rounded-lg focus:border-gray-700 hover:bg-green-700">
                            Proceed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection