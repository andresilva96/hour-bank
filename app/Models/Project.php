<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'hash'];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->attributes['hash'] = Str::random(7);
        });
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
