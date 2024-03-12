<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete Account</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- component -->
    <section class="min-h-screen flex flex-col">
        <div class="flex justify-center items-center">
            @if (isset($errors) && count($errors) > 0)
                <div class="alert alert-warning @if($errors->has('message')) text-green-500 @else text-red-500  @endif " role="alert">
                    <ul class="list-unstyled mb-">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="flex flex-1 items-center justify-center">
            <div class="rounded-lg sm:border-2 px-4 lg:px-24 py-16 lg:max-w-xl sm:max-w-md w-full text-center">
                <form class="text-center" action="{{ url('remove-user') }}" method="POST">
                    @csrf
                    <h1 class="font-bold tracking-wider text-xl mb-8 w-full text-gray-600">
                        Delete User
                    </h1>
                    <div class="py-2 text-left">
                        <input name="email" type="text"
                            class="border-2 border-gray-100 focus:outline-none bg-gray-100 block w-full py-2 px-4 rounded-lg focus:border-gray-700 "
                            placeholder="Email" />
                    </div>
                
                    <div class="py-2">
                        <button type="submit"
                            class="border-2 border-gray-100 focus:outline-none bg-green-600 text-white font-bold tracking-wider block w-full p-2 rounded-lg focus:border-gray-700 hover:bg-green-700">
                            Remove User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>