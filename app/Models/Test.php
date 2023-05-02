<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['type', 'date', 'course_id'];

    protected $searchableFields = ['*'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
