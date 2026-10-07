<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function form()
    {
        return view('theme.rjshop-theme.staticpages.track');
    }

    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:50',
            'email' => 'required|email|max:150',
        ]);

        $order = Order::where('order_number', $validated['order_number'])
            ->where('customer_email', $validated['email'])
            ->first();

        if (!$order) {
            return back()
                ->withInput()
                ->withErrors(['order_number' => 'No order found with that number and email.']);
        }

        return redirect()->route('track.show', ['token' => $order->tracking_token]);
    }

    public function show(string $token)
    {
        $order = Order::with([
            'items.options',
            'statusLogs' => fn($q) => $q->where('is_public', 1)->orderBy('created_at'),
        ])
            ->where('tracking_token', $token)
            ->firstOrFail();

        return view('theme.rjshop-theme.staticpages.track-show', compact('order'));
    }
}
