<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'birth_date',
        'grade',
        'avatar',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'grade' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            ParentProfile::class,
            'parent_student',
            'student_id',
            'parent_id'
        )->withPivot('relationship')
            ->withTimestamps();
    }
}
