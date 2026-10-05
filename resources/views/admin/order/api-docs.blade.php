@extends('admin.app')

@section('title', 'API Documentation')

@section('content')

<div class="max-w-5xl mx-auto space-y-6" x-data="{ tab: 'public' }">

    <div class="bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-1">RJ SHOP API</h2>
                <p class="text-sm text-indigo-100">REST API for customer and admin mobile apps</p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-code text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/20 flex flex-wrap items-center gap-4 text-xs">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-white/20 font-mono">Base URL</span>
                <span class="font-mono">{{ url('/api') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-white/20 font-mono">Version</span>
                <span class="font-mono">v1</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-1.5 flex gap-1">
        <button @click="tab = 'public'"
                :class="tab === 'public' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <i class="fa-solid fa-users text-xs"></i> Customer API
        </button>
        <button @click="tab = 'admin'"
                :class="tab === 'admin' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <i class="fa-solid fa-user-shield text-xs"></i> Admin API
        </button>
    </div>

    <div x-show="tab === 'public'" class="space-y-6">

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-key text-amber-500"></i> Authentication
            </h3>

            <p class="text-sm text-gray-600 mb-4">All API requests must include an API key in the header.</p>

            <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-xs text-slate-100 font-mono leading-relaxed"><span class="text-slate-500"># Header</span>
X-API-KEY: <span class="text-emerald-400">your_api_key_here</span>

<span class="text-slate-500"># Content-Type</span>
Content-Type: application/json</pre>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">POST</span>
                    <code class="text-sm font-mono text-gray-800">/api/orders</code>
                </div>
                <span class="text-xs text-gray-500">Create new order</span>
            </div>

            <div class="p-6 space-y-6">

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Request Body</h4>

                    <div class="space-y-3">
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-2 text-xs font-medium text-gray-700 border-b border-gray-200">
                                Customer Information
                            </div>
                            <div class="p-4 space-y-2 text-xs">
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">customer_name</code>
                                    <span class="text-gray-500 col-span-2">string, required</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">customer_email</code>
                                    <span class="text-gray-500 col-span-2">string, required</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">customer_phone</code>
                                    <span class="text-gray-500 col-span-2">string, required</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">company_name</code>
                                    <span class="text-gray-500 col-span-2">string, optional</span>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-2 text-xs font-medium text-gray-700 border-b border-gray-200">
                                Shipping Address
                            </div>
                            <div class="p-4 space-y-2 text-xs">
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">shipping_address</code>
                                    <span class="text-gray-500 col-span-2">string, required</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">shipping_address2</code>
                                    <span class="text-gray-500 col-span-2">string, optional</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">shipping_city</code>
                                    <span class="text-gray-500 col-span-2">string, required</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">shipping_state</code>
                                    <span class="text-gray-500 col-span-2">string, required — 2-letter code</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">shipping_zip</code>
                                    <span class="text-gray-500 col-span-2">string, required</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">shipping_country</code>
                                    <span class="text-gray-500 col-span-2">string, optional — Default: US</span>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-2 text-xs font-medium text-gray-700 border-b border-gray-200">
                                Items (Array)
                            </div>
                            <div class="p-4 space-y-2 text-xs">
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">items[].product_id</code>
                                    <span class="text-gray-500 col-span-2">integer, required</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">items[].quantity</code>
                                    <span class="text-gray-500 col-span-2">integer, required — Min: 1</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">items[].attributes</code>
                                    <span class="text-gray-500 col-span-2">object, optional</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">items[].print_zone_id</code>
                                    <span class="text-gray-500 col-span-2">integer, optional</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">items[].print_width</code>
                                    <span class="text-gray-500 col-span-2">number, optional</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <code class="font-mono text-indigo-600">items[].print_height</code>
                                    <span class="text-gray-500 col-span-2">number, optional</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Example Request</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"customer_name"</span>: <span class="text-emerald-400">"John Doe"</span>,
  <span class="text-sky-400">"customer_email"</span>: <span class="text-emerald-400">"john@example.com"</span>,
  <span class="text-sky-400">"customer_phone"</span>: <span class="text-emerald-400">"+1 555 123 4567"</span>,
  <span class="text-sky-400">"shipping_address"</span>: <span class="text-emerald-400">"123 Main Street"</span>,
  <span class="text-sky-400">"shipping_city"</span>: <span class="text-emerald-400">"Los Angeles"</span>,
  <span class="text-sky-400">"shipping_state"</span>: <span class="text-emerald-400">"CA"</span>,
  <span class="text-sky-400">"shipping_zip"</span>: <span class="text-emerald-400">"90001"</span>,
  <span class="text-sky-400">"items"</span>: [
    {
      <span class="text-sky-400">"product_id"</span>: <span class="text-amber-400">12</span>,
      <span class="text-sky-400">"quantity"</span>: <span class="text-amber-400">5</span>,
      <span class="text-sky-400">"attributes"</span>: {
        <span class="text-sky-400">"Size"</span>: <span class="text-emerald-400">"XL"</span>,
        <span class="text-sky-400">"Color"</span>: <span class="text-emerald-400">"Black"</span>
      }
    }
  ]
}</pre>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Response — 201</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">true</span>,
  <span class="text-sky-400">"data"</span>: {
    <span class="text-sky-400">"order_number"</span>: <span class="text-emerald-400">"RJ-2026-0001"</span>,
    <span class="text-sky-400">"status"</span>: <span class="text-emerald-400">"pending"</span>,
    <span class="text-sky-400">"total"</span>: <span class="text-amber-400">130.00</span>
  }
}</pre>
                    </div>
                </div>

            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">GET</span>
                    <code class="text-sm font-mono text-gray-800">/api/orders/{order_number}</code>
                </div>
                <span class="text-xs text-gray-500">Track order status</span>
            </div>

            <div class="p-6 space-y-4">
                <p class="text-sm text-gray-600">Retrieve order details by order number. No authentication needed for tracking.</p>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Example Response</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">true</span>,
  <span class="text-sky-400">"data"</span>: {
    <span class="text-sky-400">"order_number"</span>: <span class="text-emerald-400">"RJ-2026-0001"</span>,
    <span class="text-sky-400">"status"</span>: <span class="text-emerald-400">"shipped"</span>,
    <span class="text-sky-400">"payment_status"</span>: <span class="text-emerald-400">"paid"</span>,
    <span class="text-sky-400">"tracking_number"</span>: <span class="text-emerald-400">"1Z999AA10123456784"</span>,
    <span class="text-sky-400">"total"</span>: <span class="text-amber-400">130.00</span>,
    <span class="text-sky-400">"items"</span>: [
      {
        <span class="text-sky-400">"product_name"</span>: <span class="text-emerald-400">"Gildan T-Shirt"</span>,
        <span class="text-sky-400">"quantity"</span>: <span class="text-amber-400">5</span>,
        <span class="text-sky-400">"unit_price"</span>: <span class="text-amber-400">25.00</span>
      }
    ],
    <span class="text-sky-400">"status_history"</span>: [
      { <span class="text-sky-400">"status"</span>: <span class="text-emerald-400">"pending"</span>, <span class="text-sky-400">"at"</span>: <span class="text-emerald-400">"2026-10-06T14:30:00Z"</span> },
      { <span class="text-sky-400">"status"</span>: <span class="text-emerald-400">"confirmed"</span>, <span class="text-sky-400">"at"</span>: <span class="text-emerald-400">"2026-10-06T15:00:00Z"</span> },
      { <span class="text-sky-400">"status"</span>: <span class="text-emerald-400">"shipped"</span>, <span class="text-sky-400">"at"</span>: <span class="text-emerald-400">"2026-10-07T10:00:00Z"</span> }
    ]
  }
}</pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-100 text-cyan-700">GET</span>
                    <code class="text-sm font-mono text-gray-800">/api/products</code>
                </div>
                <span class="text-xs text-gray-500">List all active products</span>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Public endpoint — no authentication needed.</p>

                <div class="space-y-2 text-xs">
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">model_id</code>
                        <span class="text-gray-500 col-span-2">integer, optional — Filter by model</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">category_id</code>
                        <span class="text-gray-500 col-span-2">integer, optional — Filter by category</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">search</code>
                        <span class="text-gray-500 col-span-2">string, optional — Search name or SKU</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">per_page</code>
                        <span class="text-gray-500 col-span-2">integer, optional — Default: 20, Max: 100</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-100 text-cyan-700">GET</span>
                    <code class="text-sm font-mono text-gray-800">/api/delivery/check</code>
                </div>
                <span class="text-xs text-gray-500">Check delivery availability</span>
            </div>

            <div class="p-6 space-y-4">
                <div class="space-y-2 text-xs">
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">zip</code>
                        <span class="text-gray-500 col-span-2">string, required</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">city</code>
                        <span class="text-gray-500 col-span-2">string, optional</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">state</code>
                        <span class="text-gray-500 col-span-2">string, optional</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">subtotal</code>
                        <span class="text-gray-500 col-span-2">number, optional — Check free shipping</span>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Example Response</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">true</span>,
  <span class="text-sky-400">"data"</span>: {
    <span class="text-sky-400">"available"</span>: <span class="text-amber-400">true</span>,
    <span class="text-sky-400">"zone"</span>: <span class="text-emerald-400">"West Coast"</span>,
    <span class="text-sky-400">"price"</span>: <span class="text-amber-400">5.00</span>,
    <span class="text-sky-400">"is_free"</span>: <span class="text-amber-400">false</span>,
    <span class="text-sky-400">"free_label"</span>: <span class="text-emerald-400">"Free over $100.00"</span>,
    <span class="text-sky-400">"delivery_time"</span>: <span class="text-emerald-400">"2-4 days"</span>,
    <span class="text-sky-400">"cod_available"</span>: <span class="text-amber-400">true</span>
  }
}</pre>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div x-show="tab === 'admin'" x-cloak class="space-y-6">

        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl p-6 text-white">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user-shield text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold mb-1">Admin Mobile App API</h3>
                    <p class="text-sm text-slate-300">Manage orders, update statuses and track business from your phone.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-key text-amber-500"></i> Admin Authentication
            </h3>

            <p class="text-sm text-gray-600 mb-4">Admin app must first login to receive a Bearer token. Use this token in all subsequent requests.</p>

            <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-xs text-slate-100 font-mono leading-relaxed"><span class="text-slate-500"># After login, use this header</span>
Authorization: <span class="text-emerald-400">Bearer {token}</span>
Content-Type: application/json</pre>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">POST</span>
                    <code class="text-sm font-mono text-gray-800">/api/admin/login</code>
                </div>
                <span class="text-xs text-gray-500">Admin login</span>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Request Body</h4>
                    <div class="space-y-2 text-xs">
                        <div class="grid grid-cols-3 gap-3">
                            <code class="font-mono text-indigo-600">email</code>
                            <span class="text-gray-500 col-span-2">string, required</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <code class="font-mono text-indigo-600">password</code>
                            <span class="text-gray-500 col-span-2">string, required</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <code class="font-mono text-indigo-600">device_name</code>
                            <span class="text-gray-500 col-span-2">string, required — e.g. "iPhone 14"</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Example Request</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"email"</span>: <span class="text-emerald-400">"admin@rjshop.com"</span>,
  <span class="text-sky-400">"password"</span>: <span class="text-emerald-400">"admin123"</span>,
  <span class="text-sky-400">"device_name"</span>: <span class="text-emerald-400">"iPhone 14 Pro"</span>
}</pre>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Response — 200</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">true</span>,
  <span class="text-sky-400">"data"</span>: {
    <span class="text-sky-400">"token"</span>: <span class="text-emerald-400">"1|abcdefghijklmnopqrstuvwxyz123456"</span>,
    <span class="text-sky-400">"user"</span>: {
      <span class="text-sky-400">"id"</span>: <span class="text-amber-400">1</span>,
      <span class="text-sky-400">"name"</span>: <span class="text-emerald-400">"Admin"</span>,
      <span class="text-sky-400">"email"</span>: <span class="text-emerald-400">"admin@rjshop.com"</span>,
      <span class="text-sky-400">"role"</span>: <span class="text-emerald-400">"admin"</span>
    }
  }
}</pre>
                    </div>
                </div>

                <div class="px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg flex gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5"></i>
                    <p class="text-xs text-amber-800">If 2FA is enabled, an additional code verification step is required.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">GET</span>
                    <code class="text-sm font-mono text-gray-800">/api/admin/orders</code>
                </div>
                <span class="text-xs text-gray-500">List orders</span>
            </div>

            <div class="p-6 space-y-4">
                <div class="space-y-2 text-xs">
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">status</code>
                        <span class="text-gray-500 col-span-2">string, optional — pending, confirmed, processing, shipped, delivered, cancelled, refunded</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">payment_status</code>
                        <span class="text-gray-500 col-span-2">string, optional — unpaid, paid, refunded</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">search</code>
                        <span class="text-gray-500 col-span-2">string, optional — Order #, name, email, phone</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">branch_id</code>
                        <span class="text-gray-500 col-span-2">integer, optional</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">per_page</code>
                        <span class="text-gray-500 col-span-2">integer, optional — Default: 20</span>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Response — 200</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">true</span>,
  <span class="text-sky-400">"data"</span>: [
    {
      <span class="text-sky-400">"id"</span>: <span class="text-amber-400">1</span>,
      <span class="text-sky-400">"order_number"</span>: <span class="text-emerald-400">"RJ-2026-0001"</span>,
      <span class="text-sky-400">"customer_name"</span>: <span class="text-emerald-400">"John Doe"</span>,
      <span class="text-sky-400">"status"</span>: <span class="text-emerald-400">"pending"</span>,
      <span class="text-sky-400">"payment_status"</span>: <span class="text-emerald-400">"unpaid"</span>,
      <span class="text-sky-400">"total"</span>: <span class="text-amber-400">130.00</span>,
      <span class="text-sky-400">"items_count"</span>: <span class="text-amber-400">2</span>,
      <span class="text-sky-400">"created_at"</span>: <span class="text-emerald-400">"2026-10-06T14:30:00Z"</span>
    }
  ],
  <span class="text-sky-400">"meta"</span>: {
    <span class="text-sky-400">"current_page"</span>: <span class="text-amber-400">1</span>,
    <span class="text-sky-400">"total"</span>: <span class="text-amber-400">45</span>,
    <span class="text-sky-400">"per_page"</span>: <span class="text-amber-400">20</span>
  }
}</pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-2 border-indigo-200 overflow-hidden shadow-md">
            <div class="px-6 py-4 border-b border-indigo-100 bg-indigo-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500 text-white">PUT</span>
                    <code class="text-sm font-mono text-gray-800">/api/admin/orders/{id}/status</code>
                </div>
                <span class="text-xs font-medium text-indigo-600 flex items-center gap-1.5">
                    <i class="fa-solid fa-star text-[10px]"></i> Most Important
                </span>
            </div>

            <div class="p-6 space-y-4">
                <p class="text-sm text-gray-600">Change order status from the mobile app. This is the most-used endpoint — updates order status, logs the change, and is instantly visible on admin panel and customer app.</p>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">URL Parameters</h4>
                    <div class="space-y-2 text-xs">
                        <div class="grid grid-cols-3 gap-3">
                            <code class="font-mono text-indigo-600">{id}</code>
                            <span class="text-gray-500 col-span-2">integer, required — Order ID</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Request Body</h4>
                    <div class="space-y-2 text-xs">
                        <div class="grid grid-cols-3 gap-3">
                            <code class="font-mono text-indigo-600">status</code>
                            <span class="text-gray-500 col-span-2">string, required — pending, confirmed, processing, shipped, delivered, cancelled, refunded</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <code class="font-mono text-indigo-600">note</code>
                            <span class="text-gray-500 col-span-2">string, optional — Max 500 chars</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Example Request</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed"><span class="text-slate-500">PUT /api/admin/orders/1/status</span>
<span class="text-slate-500">Authorization: Bearer 1|abcdefgh...</span>

{
  <span class="text-sky-400">"status"</span>: <span class="text-emerald-400">"shipped"</span>,
  <span class="text-sky-400">"note"</span>: <span class="text-emerald-400">"Shipped via FedEx"</span>
}</pre>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Response — 200</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">true</span>,
  <span class="text-sky-400">"message"</span>: <span class="text-emerald-400">"Order status updated to Shipped"</span>,
  <span class="text-sky-400">"data"</span>: {
    <span class="text-sky-400">"order_number"</span>: <span class="text-emerald-400">"RJ-2026-0001"</span>,
    <span class="text-sky-400">"status"</span>: <span class="text-emerald-400">"shipped"</span>,
    <span class="text-sky-400">"shipped_at"</span>: <span class="text-emerald-400">"2026-10-07T10:00:00Z"</span>
  }
}</pre>
                    </div>
                </div>

                <div class="px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg flex gap-3">
                    <i class="fa-solid fa-circle-info text-indigo-600 mt-0.5"></i>
                    <div class="text-xs text-indigo-800">
                        <p class="font-medium mb-1">Automatic Actions</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>Timestamp recorded (shipped_at, delivered_at etc.)</li>
                            <li>Status history logged with admin name</li>
                            <li>Visible immediately on admin panel and customer app</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700">PUT</span>
                    <code class="text-sm font-mono text-gray-800">/api/admin/orders/{id}/payment</code>
                </div>
                <span class="text-xs text-gray-500">Update payment & tracking</span>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Request Body</h4>
                    <div class="space-y-2 text-xs">
                        <div class="grid grid-cols-3 gap-3">
                            <code class="font-mono text-indigo-600">payment_status</code>
                            <span class="text-gray-500 col-span-2">string, optional — unpaid, paid, refunded</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <code class="font-mono text-indigo-600">tracking_number</code>
                            <span class="text-gray-500 col-span-2">string, optional</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <code class="font-mono text-indigo-600">admin_note</code>
                            <span class="text-gray-500 col-span-2">string, optional</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Example Request</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"payment_status"</span>: <span class="text-emerald-400">"paid"</span>,
  <span class="text-sky-400">"tracking_number"</span>: <span class="text-emerald-400">"1Z999AA10123456784"</span>
}</pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">GET</span>
                    <code class="text-sm font-mono text-gray-800">/api/admin/orders/{id}</code>
                </div>
                <span class="text-xs text-gray-500">Order detail with items and designs</span>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-600">Returns full order info: items, attributes, options, design file URLs, status history, customer info.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">GET</span>
                    <code class="text-sm font-mono text-gray-800">/api/admin/stats</code>
                </div>
                <span class="text-xs text-gray-500">Dashboard statistics</span>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <h4 class="font-medium text-gray-800 mb-3 text-sm">Response — 200</h4>
                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">true</span>,
  <span class="text-sky-400">"data"</span>: {
    <span class="text-sky-400">"total_orders"</span>: <span class="text-amber-400">145</span>,
    <span class="text-sky-400">"pending_orders"</span>: <span class="text-amber-400">12</span>,
    <span class="text-sky-400">"processing_orders"</span>: <span class="text-amber-400">8</span>,
    <span class="text-sky-400">"today_revenue"</span>: <span class="text-amber-400">1250.00</span>,
    <span class="text-sky-400">"month_revenue"</span>: <span class="text-amber-400">34500.00</span>,
    <span class="text-sky-400">"total_customers"</span>: <span class="text-amber-400">89</span>
  }
}</pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">POST</span>
                    <code class="text-sm font-mono text-gray-800">/api/admin/logout</code>
                </div>
                <span class="text-xs text-gray-500">Invalidate current token</span>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-600">Revoke the current access token. Admin must login again.</p>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-indigo-500"></i> Status Codes
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
            <div class="flex items-start gap-3 p-3 rounded-lg bg-emerald-50 border border-emerald-100">
                <span class="font-mono font-bold text-emerald-700">200</span>
                <span class="text-emerald-800">Success</span>
            </div>
            <div class="flex items-start gap-3 p-3 rounded-lg bg-emerald-50 border border-emerald-100">
                <span class="font-mono font-bold text-emerald-700">201</span>
                <span class="text-emerald-800">Created</span>
            </div>
            <div class="flex items-start gap-3 p-3 rounded-lg bg-amber-50 border border-amber-100">
                <span class="font-mono font-bold text-amber-700">400</span>
                <span class="text-amber-800">Bad Request</span>
            </div>
            <div class="flex items-start gap-3 p-3 rounded-lg bg-amber-50 border border-amber-100">
                <span class="font-mono font-bold text-amber-700">401</span>
                <span class="text-amber-800">Unauthorized</span>
            </div>
            <div class="flex items-start gap-3 p-3 rounded-lg bg-rose-50 border border-rose-100">
                <span class="font-mono font-bold text-rose-700">403</span>
                <span class="text-rose-800">Forbidden</span>
            </div>
            <div class="flex items-start gap-3 p-3 rounded-lg bg-rose-50 border border-rose-100">
                <span class="font-mono font-bold text-rose-700">404</span>
                <span class="text-rose-800">Not Found</span>
            </div>
            <div class="flex items-start gap-3 p-3 rounded-lg bg-rose-50 border border-rose-100">
                <span class="font-mono font-bold text-rose-700">422</span>
                <span class="text-rose-800">Validation Error</span>
            </div>
            <div class="flex items-start gap-3 p-3 rounded-lg bg-rose-50 border border-rose-100">
                <span class="font-mono font-bold text-rose-700">429</span>
                <span class="text-rose-800">Too Many Requests</span>
            </div>
            <div class="flex items-start gap-3 p-3 rounded-lg bg-rose-50 border border-rose-100">
                <span class="font-mono font-bold text-rose-700">500</span>
                <span class="text-rose-800">Server Error</span>
            </div>
        </div>
    </div>

</div>

<style>[x-cloak]{display:none!important;}</style>

@endsection
