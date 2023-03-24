<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::latest();
        if ($request->boolean('owned')) {
            $books->where('user_id', Auth::user()->getKey());
        }
//        $books = Book::where('user_id', Auth::user()->getKey()); //找到自己的書單

//        return Book::all(); //資料太多的話不建議all()，會改用pagenate()
        return $books->paginate();
    }
    public function store(Request $request)
    {
        $this->authorize('create', [Book::class]); //這邊的Book::class會call BookPolicy
        $user = Auth::user();
        $validated = $this->validate($request, [
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

         return $user->books()->create($validated);
    }
}
