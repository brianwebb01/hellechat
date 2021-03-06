<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'company',
        'phone_numbers',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'phone_numbers' => 'array',
    ];

    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class);
    }

    public function voicemails()
    {
        return $this->hasMany(\App\Models\Voicemail::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function friendlyName()
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name.' '.$this->last_name;
        } elseif ($this->first_name && ! $this->last_name) {
            return $this->first_name;
        } elseif (! $this->first_name && ! $this->last_name) {
            return $this->company;
        }
    }
}
