@extends('layouts.admin')
@section('body')
<form action="{{ url('/reports/rates/') }}" method="post" class="max-w-lg mx-auto">
    @csrf
  <div class="mb-4">
    <label class="block text-gray-700 font-bold mb-2" for="title">
      Title
    </label>
    <select class="block w-full p-2 border rounded shadow" name="type_id">
      @foreach ($crops as $crop)
          <optgroup label="{{ $crop->name }}">
              @foreach ($crop->types as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
              @endforeach
          </optgroup>
      @endforeach
  </select>  
  </div>
  <div class="flex justify-end">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
      Generate Report
    </button>
  </div>

</form>

@endsection