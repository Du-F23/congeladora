<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teams extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'acronym',
        'team',
        'capitan_id'
    ];

    public function capitan(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'teams_users', 'team_id', 'user_id');
    }
}
