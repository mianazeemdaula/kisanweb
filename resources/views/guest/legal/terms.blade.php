@extends('layouts.guest')
@section('title')
    Terms & Conditions | Digital Mandi
@endsection
@section('body')
    {{-- Styled here, not with utility classes: the compiled stylesheet only
         contains the classes that existed when it was last built. --}}
    <style>
        .legal-page { max-width: 48rem; margin: 0 auto; padding: 2.5rem 1rem 4rem; }
        .legal-card { background: #fff; border: 1px solid #f3f4f6; border-radius: 1.5rem; padding: 2rem 1.5rem; box-shadow: 0 1px 2px rgba(0, 0, 0, .05); }
        .legal-card h1 { font-size: 1.875rem; font-weight: 800; color: #111827; margin-bottom: 1.5rem; }
        .legal-card p { color: #374151; line-height: 1.75; margin-bottom: 1rem; }
        .legal-card strong { display: inline-block; color: #111827; font-size: 1.125rem; margin-top: .75rem; }
        .legal-card ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: #374151; line-height: 1.75; }
        .legal-card a { color: #16a34a; text-decoration: underline; word-break: break-word; }
        @media (min-width: 640px) { .legal-card { padding: 2.5rem; } }
    </style>
    <div class="legal-page">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ url('/') }}" class="hover:text-green-600 transition-base">Home</a>
            <span class="bi bi-chevron-right text-xs"></span>
            <span class="text-gray-700 font-medium">Terms &amp; Conditions</span>
        </nav>
        <div class="legal-card">
            <h1>Terms &amp; Conditions</h1>
            @include('legal.terms_content')
        </div>
    </div>
@endsection
