@extends('layouts.admin')
@section('content')
    <div>
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Dashboard Overview</h2>
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <x-stat-card title="Total Users" count="{{ $data['users'] }}" color="bg-gradient-to-br from-blue-500 to-indigo-600">
                <span class="bi bi-people text-lg"></span>
            </x-stat-card>
            <x-stat-card title="Last Day Users" count="{{ $data['last_day'] }}" color="bg-gradient-to-br from-indigo-400 to-violet-600">
                <span class="bi bi-person-check text-lg"></span>
            </x-stat-card>
            <x-stat-card title="Today Users" count="{{ $data['today'] }}" color="bg-gradient-to-br from-emerald-400 to-teal-600">
                <span class="bi bi-person-plus text-lg"></span>
            </x-stat-card>
            <x-stat-card title="Open Deals" count="{{ $data['deals'] }}" color="bg-gradient-to-br from-amber-400 to-orange-500">
                <span class="bi bi-cart text-lg"></span>
            </x-stat-card>
            <x-stat-card title="Today Deals" count="{{ $data['today_deals'] }}" color="bg-gradient-to-br from-orange-400 to-rose-600">
                <span class="bi bi-cart-plus text-lg"></span>
            </x-stat-card>
            <x-stat-card title="Closed Deals" count="{{ $data['closed_deals'] }}" color="bg-gradient-to-br from-rose-500 to-red-600">
                <span class="bi bi-cart-check text-lg"></span>
            </x-stat-card>
            <x-stat-card title="Feeds" count="{{ $data['feed'] }}" color="bg-gradient-to-br from-fuchsia-500 to-purple-600">
                <span class="bi bi-newspaper text-lg"></span>
            </x-stat-card>
            <x-stat-card title="Shops" count="{{ $data['shops'] }}" color="bg-gradient-to-br from-sky-400 to-blue-600">
                <span class="bi bi-shop-window text-lg"></span>
            </x-stat-card>
            <x-stat-card title="Today Shops" count="{{ $data['today_shops'] }}" color="bg-gradient-to-br from-cyan-400 to-teal-500">
                <span class="bi bi-shop text-lg"></span>
            </x-stat-card>
            <x-stat-card title="Rates DEOs" count="{{ $data['deo_count'] }}" color="bg-gradient-to-br from-purple-500 to-pink-600">
                <span class="bi bi-people-fill text-lg"></span>
            </x-stat-card>
            <x-stat-card title="Today Rates" count="{{ $data['rates_count'] }}" color="bg-gradient-to-br from-pink-500 to-rose-600">
                <span class="bi bi-graph-up text-lg"></span>
            </x-stat-card>
        </div>
    </div>
@endsection
