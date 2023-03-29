<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

// 大家都可以看到閱讀清單
//  - index: book list
//  - show: id of book
// admin 管理員有全部權限
//  - store: create a book
//  - update: update a book of all user
//  - destroy: delete a book of all user
// 使用者只能新增、編輯、刪除自己的書單
//  - store: create a book
//  - update: update a book
//  - destroy: delete a book

    protected $fillable = [
        'name',
        'author',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
