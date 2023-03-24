<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
//        $this->authorize('viewAny', [Book::class]); //要去編輯policy
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
//        $this->authorize('create', [Book::class]); //這邊的 Book::class 會 call BookPolicy 的 create method
        $user = Auth::user();
        $validated = $this->validate($request, [
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

         return $user->books()->create($validated);
    }
    public function update(Request $request, Book $book)
    {
        // url裏面有book id，update時帶入的id的那本書=>$book  (laravel model binding)

        $this->authorize('update', [Book::class, $book]); //$book要丟到policy的
        $validated = $this->validate($request, [
            'name' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            // sometimes 如果有資料就檢驗，沒資料就不檢驗，但要跟前端講好，或是就用required
        ]);
        $book->update($validated);
        return $book;
    }
    public function destroy(Book $book)
    {
        $this->authorize('delete', [Book::class, $book]);


        \DB::beginTransaction(); // 宣告開始同時對DB很多表進行操作，
        try {
            $book->delete();
//            $book->images()->delete();
        } catch (\Exception $exception) {
            \DB::rollBack();  // 如果沒有同步刪除，則返回
            throw $exception;
        }
        \DB::commit(); // 結束DB操作，如果沒加上這行，會把所有表都鎖起來


        $book->delete();
        return response()->noContent();
    }

    public function show(Book $book)
    {
        $this->authorize('show', [Book::class, $book]);
        return $book;
    }

}
