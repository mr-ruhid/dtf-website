<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiProxyController extends Controller
{
    public function show(Request $request)
    {
        $url = $request->query('url');

        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            abort(400, 'Invalid URL');
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            abort(400, 'Invalid host');
        }

        try {
            $response = Http::withOptions([
                'allow_redirects' => ['max' => 5, 'track_redirects' => true],
                'verify' => false,
            ])->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept-Encoding' => 'identity',
            ])->timeout(30)->get($url);
        } catch (\Exception $e) {
            abort(502, 'Upstream error');
        }

        $html = $response->body();

        $html = preg_replace('/<meta[^>]*http-equiv=["\']?Content-Security-Policy["\']?[^>]*>/i', '', $html);
        $html = preg_replace('/<meta[^>]*http-equiv=["\']?X-Frame-Options["\']?[^>]*>/i', '', $html);

        if (stripos($html, '<head') !== false) {
            $html = preg_replace('/<head([^>]*)>/i', '<head$1><base href="' . e($url) . '">', $html, 1);
        }

        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('X-Frame-Options', '')
            ->header('Content-Security-Policy', '');
    }
}