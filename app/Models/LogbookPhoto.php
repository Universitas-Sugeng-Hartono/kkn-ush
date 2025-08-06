<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LogbookPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'logbook_id',
        'path'
    ];

    public function logbook()
    {
        return $this->belongsTo(Logbook::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }
} 