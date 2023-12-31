<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoccerMatches extends Model
{
    use HasFactory;

    protected $fillable = [
        'dayOfMatch',
        'team_local_id',
        'team_visit_id',
        'referee_id',
        'team_local_goals',
        'team_visit_goals',
        'team_local_fouls',
        'team_visit_fouls',
        'started',
    ];

    public function team_local(){
        return $this->belongsTo(Teams::class);
    }

    public function team_visit(){
        return $this->belongsTo(Teams::class);
    }

    public function referee() {
        return $this->belongsTo(User::class);
    }

    public function scopeThisWeek($query) {
        $today = Carbon::now()->format('Y-m-d');
        $endOfThisWeek = Carbon::now()->addDays(8)->format('Y-m-d');

        return $query->whereBetween('dayOfMatch', [$today, $endOfThisWeek]);
    }

    public function goals()
    {
        return $this->hasMany(MatchUser::class, 'soccerMatch_id');
    }

    public function addGoals()
    {
        return $this->belongsToMany(MatchUser::class, 'match_users', 'soccerMatch_id', 'player_id');
    }

    public function ganador(): ?int
    {
        if ($this->team_local_goals > $this->team_visit_goals) {
            return $this->team_local_id;
        }

        // Si el equipo visitante ganó, devolver su ID
        if ($this->team_local_goals < $this->team_visit_goals) {
            return $this->team_visit_id;
        }

        // Si hubo empate, devolver null
        return null;
    }

}
