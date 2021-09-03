<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'service_account_id',
        'phone_number',
        'friendly_label',
        'disabled',
        'external_identity',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'service_account_id' => 'integer',
        'disabled' => 'boolean',
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

    public function serviceAccount()
    {
        return $this->belongsTo(\App\Models\ServiceAccount::class);
    }
}
