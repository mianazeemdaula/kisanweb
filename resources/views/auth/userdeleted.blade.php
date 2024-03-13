@extends('layouts.guest')
@section('body')
    <!-- component -->
    <section class="min-h-screen flex flex-col">
        <div class="flex items-center justify-center">
            <div class="rounded-lg sm:border-2 px-4 lg:px-24 py-16 lg:max-w-xl sm:max-w-md w-full text-center">
                <div class="flex justify-start items-center">
                    <div class="items-start text-left">
                        @if($deleted)
                        <div class="font-bold">User account deleted</div>
                        <p>Your account has been deleted successfully</p>
                        @else
                        <div class="font-bold">Error</div>
                        <p>User not found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection