<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['title', 'user_id'];

    protected $searchableFields = ['*'];

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
