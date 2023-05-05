<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrerenderingController extends Controller
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws GuzzleException
     */
    public function prerender(Request $request)
    {
        // 構造Prerender.io服務的URL，包含要預渲染的網頁的URL
        $url = 'https://service.prerender.io/' . $request->fullUrl();
//dd($request);
        // 使用GuzzleHttp的get方法發送GET請求
        $response = $this->client->get($url, [
            'headers' => [
                // 在請求頭中添加Prerender.io的token，用於身份驗證
                'X-Prerender-Token' => config('prerender.token')
            ]
        ]);
//dd($response);
        // 獲取預渲染過的HTML頁面的內容
        $html = $response->getBody()->getContents();

        // 將HTML頁面返回給前端瀏覽器
        return new Response($html, 200, [
            'Content-Type' => 'text/html'
        ]);
    }
}

