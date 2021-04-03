<?php

namespace App\Models;


class User extends BaseModel
{
    protected $table = 'user';

    // Ẩn cột password ở JSON trả về
    protected $hidden = [
        'password'
    ];

    /**
     * Danh sách ứng dụng của người dùng.
     */
    public function apps()
    {
        return $this->belongsToMany('App\Models\App', 'user_app');
    }
}
