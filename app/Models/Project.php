<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'user_id',
    ];

    /**
     * Usersテーブルとのリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tasksテーブルとのリレーション
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}