@extends('layouts.admin')
@section('content')
    <div class="">
        <div class="grid grid-cols-4 gap-4">
            <x-stat-card title="Total Users" count="{{ $data['users'] }}" color="bg-green-200">
                <span class="bi bi-snow"></span>
            </x-stat-card>
            <x-stat-card title="Last Day Users" count="{{ $data['last_day'] }}" color="bg-green-200">
                <span class="bi bi-snow"></span>
            </x-stat-card>
            <x-stat-card title="Today Users" count="{{ $data['today'] }}" color="bg-green-200">
                <span class="bi bi-snow"></span>
            </x-stat-card>
            <x-stat-card title="Open Deals" count="{{ $data['deals'] }}" color="bg-green-200">
                <span class="bi bi-snow"></span>
            </x-stat-card>
            <x-stat-card title="Today Deals" count="{{ $data['today_deals'] }}" color="bg-green-200">
                <span class="bi bi-snow"></span>
            </x-stat-card>
            <x-stat-card title="Feeds" count="{{ $data['feed'] }}" color="bg-green-200">
                <span class="bi bi-snow"></span>
            </x-stat-card>
            <x-stat-card title="Shops" count="{{ $data['shops'] }}" color="bg-green-200">
                <span class="bi bi-snow"></span>
            </x-stat-card>
            <x-stat-card title="Today Shops" count="{{ $data['today_shops'] }}" color="bg-green-200">
                <span class="bi bi-snow"></span>
            </x-stat-card>
        </div>
    </div>
@endsection
