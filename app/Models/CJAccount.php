<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CJAccount extends Model
{
       use HasFactory;

    protected $table = 'cj_accounts';

    protected $fillable = [
        'user_id',
        'open_id',
        'access_token',
        'refresh_token',
        'access_token_expires_at',
        'refresh_token_expires_at',
    ];

    protected $casts = [
        'access_token_expires_at' => 'datetime',
        'refresh_token_expires_at' => 'datetime',
    ];

    /**
     * Relationship: Token belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
