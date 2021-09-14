<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public const DIRECTION_OUT = 'outbound';
    public const DIRECTION_IN = 'inbound';

    public const STATUS_LOCAL_CREATED = 'local-created';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_SENDING = 'sending';
    public const STATUS_SENT = 'sent';
    public const STATUS_DELIVERY_UNKNOWN = 'delivery_unknown';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_UNDELIVERED = 'undelivered';
    public const STATUS_FAILED = 'failed';
    public const STATUS_RECEIVED = 'received';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from',
        'to',
        'body',
        'error_code',
        'error_message',
        'direction',
        'status',
        'num_media',
        'media',
        'external_identity',
        'external_date_created',
        'external_date_updated',
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
        'service_account_id' => 'integer',
        'contact_id' => 'integer',
        'num_media' => 'integer',
        'media' => 'array',
        'external_date_created' => 'datetime',
        'external_date_updated' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function number()
    {
        return $this->belongsTo(\App\Models\Number::class);
    }

    public function serviceAccount()
    {
        return $this->belongsTo(\App\Models\ServiceAccount::class);
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }
}
