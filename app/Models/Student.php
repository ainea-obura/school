<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'fname',
        'lname',
        'adm_no',
        'user_id',
        'class',
        'stream_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function assign()
    {
        return $this->hasMany(Assign::class);
    }
}
