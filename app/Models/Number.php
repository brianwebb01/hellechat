<?php

namespace App\Models;

use App\Models\Traits\UsesHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    use HasFactory;
    use UsesHashId;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_account_id',
        'phone_number',
        'sip_registration_url',
        'friendly_label',
        'external_identity',
        'dnd_calls',
        'dnd_voicemail',
        'dnd_messages',
        'dnd_allow_contacts',
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
        'dnd_calls' => 'boolean',
        'dnd_voicemail' => 'boolean',
        'dnd_messages' => 'boolean',
        'dnd_allow_contacts' => 'boolean',
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

    public function shouldRing(Contact $contact = null)
    {
        if ($this->dnd_calls) {
            return $this->dnd_allow_contacts && ! \is_null($contact);
        }

        return true;
    }
}
