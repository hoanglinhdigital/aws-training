<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordReset extends Model
{
    const TOKEN_LENGTH = 60;
    protected $fillable = ['email', 'token'];


    public function getUpdatedAtColumn()
    {
        return null;
    }

    public function generateToken() 
    {
        $this->token = Str::random($this::TOKEN_LENGTH);
        return $this->token;
    }
}
