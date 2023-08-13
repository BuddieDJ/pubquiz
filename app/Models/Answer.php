<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'player_id',
        'lobby_id',
        'answer',
        'round',
        'is_correct'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function lobby()
    {
        return $this->belongsTo(Lobby::class);
    }
}
