<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voicemail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'number_id',
        'contact_id',
        'media_url',
        'length',
        'transcription',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'number_id' => 'integer',
        'contact_id' => 'integer',
        'length' => 'integer',
    ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function number()
    {
        return $this->belongsTo(\App\Models\Number::class);
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }
}
