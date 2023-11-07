@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        
        @foreach ($errors->all() as $error)
            <div class="text-red-500">{{ $error }}</div>
        @endforeach
        <form action="{{ route('admin.ads.update',$ad->id) }}" method="post" class="" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 p-2 gap-2 md:p-4 items-end">
                <div>
                    <h3 class="p-1">Title</h3>
                    <input type="text" placeholder="Title" name="title" value="{{ $ad->title }}" class="w-80">
                    @error('title')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Title Urdu</h3>
                    <input type="text" placeholder="Title Urdu" name="title_ur" value="{{ $ad->title_ur }}" class="w-80">
                    @error('title_ur')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Type</h3>
                    <select name="action" id="" class="w-80">
                        <option value="whatsapp">Whatsapp</option>
                        <option value="link">Web Link</option>
                        <option value="deal">Deal</option>
                        <option value="shop">Shop</option>
                    </select>
                    @error('action')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Link</h3>
                    <input type="text" placeholder="Link" name="link" value="{{ $ad->link }}" class="w-80">
                    @error('link')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Start Date</h3>
                    <input type="date" placeholder="Start Date" name="start_date" value="{{ $ad->start_date }}" class="w-80">
                    @error('start_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">End Date</h3>
                    <input type="date" placeholder="End Date" name="end_date" value="{{ $ad->end_date }}" class="w-80">
                    @error('end_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Latitude</h3>
                    <input type="number" placeholder="Latitude" name="lat" value="{{ $ad->location->latitude }}" class="w-80" step="any">
                    @error('lat')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Longitude</h3>
                    <input type="number" placeholder="Longitude" name="lng" value="{{ $ad->location->longitude }}" class="w-80" step="any">
                    @error('lng')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">View Range (km)</h3>
                    <input type="number" placeholder="View km" name="view_km" value="{{ $ad->view_km }}" class="w-80" step="any">
                    @error('view_km')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <h3 class="p-1">Image</h3>
                    <input type="file" placeholder="Ad Image" name="image" class="w-80">
                    @error('image')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Send
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
