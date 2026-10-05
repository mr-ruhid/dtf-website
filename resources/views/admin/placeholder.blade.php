@extends('admin.app')

@section('title', $title ?? 'Coming Soon')

@section('content')

<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-md">
        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mx-auto mb-6 shadow-lg shadow-indigo-500/30">
            <i class="fa-solid fa-hammer text-3xl text-white"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">{{ $title ?? 'Coming Soon' }}</h2>
        <p class="text-sm text-slate-500">This section is under construction. It will be available soon.</p>
    </div>
</div>

@endsection
