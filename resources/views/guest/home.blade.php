@extends('layouts.guest')
@section('title', 'Welcome | Digital Mandi')
@section('body')
    <div class="bg-mesh min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-20 text-center animate-fade-up">
            <h1 class="font-display text-4xl sm:text-5xl font-extrabold text-gray-900 mb-6">
                Welcome to <span class="text-gradient">Digital Mandi</span>
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto mb-8">
                Your one-stop platform for agricultural trading and real-time market insights.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('rates') }}"
                    class="inline-flex items-center gap-2 px-7 py-3.5 font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg shadow-green-600/25 hover:-translate-y-0.5 transition-base">
                    <span class="bi bi-bar-chart-line"></span> View Market Rates
                </a>
                <a href="{{ url('deals') }}"
                    class="inline-flex items-center gap-2 px-7 py-3.5 font-semibold text-green-700 bg-white border-2 border-green-200 rounded-xl hover:border-green-400 transition-base">
                    <span class="bi bi-bag-check"></span> Browse Deals
                </a>
            </div>
        </div>
    </div>
@endsection
