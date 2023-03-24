<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
//        return $user->id === $book->user_id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Book $book): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionToCreateBook();
        // 這裡的 user 是從 middleware('auth') 來的
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Book $book): bool
    {
        // $user 是從middleware來的、$book是從Controller呼叫[Book::class, $book]來的
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Book $book): bool
    {
        //
    }

//    /**
//     * Determine whether the user can restore the model.
//     */
//    public function restore(User $user, Book $book): bool
//    {
//        //
//    }
//
//    /**
//     * Determine whether the user can permanently delete the model.
//     */
//    public function forceDelete(User $user, Book $book): bool
//    {
//        //
//    }

    public function isAdmin()
    {
//        return $this->role === self::ROLE_ADMIN;
    }
}
