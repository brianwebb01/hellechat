<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Twilio\Rest\Client;

class ServiceAccount extends Model
{
    use HasFactory;

    public const PROVIDER_TWILIO = 'twilio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'provider',
        'api_key',
        'api_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'api_key' => 'encrypted',
        'api_secret' => 'encrypted'
    ];


    public function numbers()
    {
        return $this->hasMany(\App\Models\Number::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getProviderClient()
    {
        if($this->provider = self::PROVIDER_TWILIO){
            return app(Client::class, [$this->api_key, $this->api_secret]);
        }
    }
}
