@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('admin.category-deals.update', $deal->id) }}" method="post" class="">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 p-2 gap-2 md:p-4 items-end">
                <div>
                    <h3 class="p-1">Subcategory</h3>
                    <select name="sub_category_id" id="" class="w-80">
                        @foreach ($categories as $item)
                            <optgroup label="{{ $item->name }}">
                                @foreach ($item->subcategories as $sub)
                                    <option value="{{ $sub->id }}" @if ($sub->id == $deal->sub_category_id) selected @endif>
                                        {{ $sub->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('sub_category_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Weight Type</h3>
                    <select name="weight_type_id" id="" class="w-80">
                        @foreach ($weights as $item)
                            <option value="{{ $item->id }}" @if ($item->id == $deal->weight_type_id) selected @endif>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('weight_type_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Packing</h3>
                    <select name="packing_id" id="" class="w-80">
                        @foreach ($packings as $item)
                            <option value="{{ $item->id }}" @if ($item->id == $deal->packing_id) selected @endif>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('packing_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Demand</h3>
                    <input type="text" placeholder="Demand" name="demand" value="{{ $deal->demand }}" class="w-80">
                    @error('demand')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Qty</h3>
                    <input type="text" placeholder="Qty" name="qty" value="{{ $deal->qty }}" class="w-80">
                    @error('qty')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Note</h3>
                    <input type="text" placeholder="Note" name="note" value="{{ $deal->note }}" class="w-80">
                    @error('note')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Save Changes
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
