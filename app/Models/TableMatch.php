<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'matches',
        'wins',
        'loses',
        'draw',
        'points',
        'goals_team',
        'goals_conceded'
    ];

    public function team()
    {
        return $this->belongsTo(Teams::class);
    }
}
