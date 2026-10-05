@extends('admin.app')

@section('title', 'Dashboard')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-lg border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-500">Total Revenue</span>
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-800">$0.00</div>
        <div class="text-xs text-gray-400 mt-1">This month</div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-500">Orders</span>
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-800">0</div>
        <div class="text-xs text-gray-400 mt-1">This month</div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-500">Products</span>
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-box"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-800">0</div>
        <div class="text-xs text-gray-400 mt-1">Active</div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-500">Customers</span>
            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-800">0</div>
        <div class="text-xs text-gray-400 mt-1">Total</div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-700">Recent Orders</h2>
            <a href="#" class="text-sm text-indigo-600 hover:underline">View all</a>
        </div>
        <div class="p-8 text-center text-gray-400 text-sm">
            No orders yet
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-700">Quick Links</h2>
        </div>
        <div class="p-4 space-y-2">
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                <i class="fa-solid fa-plus w-4 text-indigo-500"></i> Add Product
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                <i class="fa-solid fa-tags w-4 text-indigo-500"></i> Manage Categories
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                <i class="fa-solid fa-gear w-4 text-indigo-500"></i> Settings
            </a>
        </div>
    </div>

</div>

@endsection
