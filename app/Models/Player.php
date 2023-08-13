<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'lobby_id'
    ];

    public function lobby()
    {
        return $this->belongsTo(Lobby::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function latestAnswer()
    {
        return $this->hasOne(Answer::class)->latest();
    }
}
