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
    // 管理員有全部權限
    //  - update: update a book of all user
    //  - destroy:
    // 使用者只能新增、編輯、刪除自己的書單
        //


}
