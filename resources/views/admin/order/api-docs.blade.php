@extends('admin.app')

@section('title', 'API Documentation')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <div class="bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-1">Orders API</h2>
                <p class="text-sm text-indigo-100">REST API for mobile app and external integrations</p>
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

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-key text-amber-500"></i> Authentication
        </h3>

        <p class="text-sm text-gray-600 mb-4">All API requests must include an API key in the header. Contact the administrator to obtain your API key.</p>

        <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
            <pre class="text-xs text-slate-100 font-mono leading-relaxed"><span class="text-slate-500"># Header</span>
X-API-KEY: <span class="text-emerald-400">your_api_key_here</span>

<span class="text-slate-500"># Content-Type</span>
Content-Type: application/json</pre>
        </div>

        <div class="mt-4 px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg flex gap-3">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5"></i>
            <p class="text-xs text-amber-800">Never expose your API key in client-side code. Keep it secure on your server.</p>
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
                                <span class="text-gray-500 col-span-2">string, required — Full name</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <code class="font-mono text-indigo-600">customer_email</code>
                                <span class="text-gray-500 col-span-2">string, required — Valid email</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <code class="font-mono text-indigo-600">customer_phone</code>
                                <span class="text-gray-500 col-span-2">string, required — Phone number</span>
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
                                <span class="text-gray-500 col-span-2">string, required — 2-letter state code (e.g. CA)</span>
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
                                <span class="text-gray-500 col-span-2">integer, required — Product ID</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <code class="font-mono text-indigo-600">items[].quantity</code>
                                <span class="text-gray-500 col-span-2">integer, required — Min: 1</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <code class="font-mono text-indigo-600">items[].attributes</code>
                                <span class="text-gray-500 col-span-2">object, optional — e.g. { "Size": "XL", "Color": "Black" }</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <code class="font-mono text-indigo-600">items[].print_zone_id</code>
                                <span class="text-gray-500 col-span-2">integer, optional — For apparel products</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <code class="font-mono text-indigo-600">items[].print_width</code>
                                <span class="text-gray-500 col-span-2">number, optional — For custom_size products</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <code class="font-mono text-indigo-600">items[].print_height</code>
                                <span class="text-gray-500 col-span-2">number, optional</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <code class="font-mono text-indigo-600">items[].options</code>
                                <span class="text-gray-500 col-span-2">array, optional — Product options</span>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 text-xs font-medium text-gray-700 border-b border-gray-200">
                            Additional
                        </div>
                        <div class="p-4 space-y-2 text-xs">
                            <div class="grid grid-cols-3 gap-3">
                                <code class="font-mono text-indigo-600">payment_method</code>
                                <span class="text-gray-500 col-span-2">string, optional — manual, cash, card, bank, other</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <code class="font-mono text-indigo-600">customer_note</code>
                                <span class="text-gray-500 col-span-2">string, optional — Max 1000 chars</span>
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
  <span class="text-sky-400">"shipping_country"</span>: <span class="text-emerald-400">"US"</span>,
  <span class="text-sky-400">"payment_method"</span>: <span class="text-emerald-400">"manual"</span>,
  <span class="text-sky-400">"customer_note"</span>: <span class="text-emerald-400">"Please deliver after 5 PM"</span>,
  <span class="text-sky-400">"items"</span>: [
    {
      <span class="text-sky-400">"product_id"</span>: <span class="text-amber-400">12</span>,
      <span class="text-sky-400">"quantity"</span>: <span class="text-amber-400">5</span>,
      <span class="text-sky-400">"attributes"</span>: {
        <span class="text-sky-400">"Size"</span>: <span class="text-emerald-400">"XL"</span>,
        <span class="text-sky-400">"Color"</span>: <span class="text-emerald-400">"Black"</span>
      },
      <span class="text-sky-400">"print_zone_id"</span>: <span class="text-amber-400">3</span>
    }
  ]
}</pre>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-800 mb-3 text-sm">Response — 201 Created</h4>
                <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                    <pre class="text-xs text-slate-100 font-mono leading-relaxed">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">true</span>,
  <span class="text-sky-400">"message"</span>: <span class="text-emerald-400">"Order created successfully"</span>,
  <span class="text-sky-400">"data"</span>: {
    <span class="text-sky-400">"order_number"</span>: <span class="text-emerald-400">"RJ-2026-0001"</span>,
    <span class="text-sky-400">"status"</span>: <span class="text-emerald-400">"pending"</span>,
    <span class="text-sky-400">"subtotal"</span>: <span class="text-amber-400">125.00</span>,
    <span class="text-sky-400">"delivery_cost"</span>: <span class="text-amber-400">5.00</span>,
    <span class="text-sky-400">"total"</span>: <span class="text-amber-400">130.00</span>,
    <span class="text-sky-400">"created_at"</span>: <span class="text-emerald-400">"2026-10-06T14:30:00Z"</span>
  }
}</pre>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-800 mb-3 text-sm">Error Responses</h4>

                <div class="space-y-3">
                    <div class="border border-red-200 rounded-lg overflow-hidden">
                        <div class="bg-red-50 px-4 py-2 flex items-center justify-between">
                            <span class="text-xs font-medium text-red-700">422 Unprocessable Entity</span>
                            <span class="text-[10px] text-red-500">Validation failed</span>
                        </div>
                        <div class="bg-slate-900 p-3 overflow-x-auto">
                            <pre class="text-xs text-slate-100 font-mono">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">false</span>,
  <span class="text-sky-400">"message"</span>: <span class="text-emerald-400">"Validation error"</span>,
  <span class="text-sky-400">"errors"</span>: {
    <span class="text-sky-400">"customer_email"</span>: [<span class="text-emerald-400">"Email is required"</span>],
    <span class="text-sky-400">"items"</span>: [<span class="text-emerald-400">"At least one item is required"</span>]
  }
}</pre>
                        </div>
                    </div>

                    <div class="border border-amber-200 rounded-lg overflow-hidden">
                        <div class="bg-amber-50 px-4 py-2 flex items-center justify-between">
                            <span class="text-xs font-medium text-amber-700">401 Unauthorized</span>
                            <span class="text-[10px] text-amber-500">Invalid or missing API key</span>
                        </div>
                        <div class="bg-slate-900 p-3 overflow-x-auto">
                            <pre class="text-xs text-slate-100 font-mono">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">false</span>,
  <span class="text-sky-400">"message"</span>: <span class="text-emerald-400">"Unauthorized"</span>
}</pre>
                        </div>
                    </div>

                    <div class="border border-rose-200 rounded-lg overflow-hidden">
                        <div class="bg-rose-50 px-4 py-2 flex items-center justify-between">
                            <span class="text-xs font-medium text-rose-700">400 Bad Request</span>
                            <span class="text-[10px] text-rose-500">No delivery available for this address</span>
                        </div>
                        <div class="bg-slate-900 p-3 overflow-x-auto">
                            <pre class="text-xs text-slate-100 font-mono">{
  <span class="text-sky-400">"success"</span>: <span class="text-amber-400">false</span>,
  <span class="text-sky-400">"message"</span>: <span class="text-emerald-400">"Delivery not available for this address"</span>
}</pre>
                        </div>
                    </div>
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
            <p class="text-sm text-gray-600">Retrieve order details by order number. Only requires the order number — no additional authentication needed for tracking.</p>

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

        <div class="p-6 space-y-4">
            <p class="text-sm text-gray-600">Retrieve list of active products with images, prices and attributes. Public endpoint — no authentication needed.</p>

            <div>
                <h4 class="font-medium text-gray-800 mb-3 text-sm">Query Parameters</h4>
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
                        <span class="text-gray-500 col-span-2">string, optional — Search by name or SKU</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">per_page</code>
                        <span class="text-gray-500 col-span-2">integer, optional — Default: 20, Max: 100</span>
                    </div>
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
            <p class="text-sm text-gray-600">Check if delivery is available for a given address and get the cost.</p>

            <div>
                <h4 class="font-medium text-gray-800 mb-3 text-sm">Query Parameters</h4>
                <div class="space-y-2 text-xs">
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">zip</code>
                        <span class="text-gray-500 col-span-2">string, required — ZIP code</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">city</code>
                        <span class="text-gray-500 col-span-2">string, optional</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">state</code>
                        <span class="text-gray-500 col-span-2">string, optional — 2-letter code</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <code class="font-mono text-indigo-600">subtotal</code>
                        <span class="text-gray-500 col-span-2">number, optional — To check free shipping threshold</span>
                    </div>
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

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-indigo-500"></i> Rate Limits
        </h3>

        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">POST /api/orders</span>
                <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">60 req / minute</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">GET /api/orders/{number}</span>
                <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">120 req / minute</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">GET /api/products</span>
                <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">120 req / minute</span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-gray-600">GET /api/delivery/check</span>
                <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">120 req / minute</span>
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
                <span class="text-emerald-800">Created — Order created</span>
            </div>
            <div class="flex items-start gap-3 p-3 rounded-lg bg-amber-50 border border-amber-100">
                <span class="font-mono font-bold text-amber-700">400</span>
                <span class="text-amber-800">Bad Request — Invalid data</span>
            </div>
            <div class="flex items-start gap-3 p-3 rounded-lg bg-amber-50 border border-amber-100">
                <span class="font-mono font-bold text-amber-700">401</span>
                <span class="text-amber-800">Unauthorized — Invalid API key</span>
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

@endsection
