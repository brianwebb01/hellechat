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

    /**
     * Scope a query to only include contacts with messages
     * composing a thread
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThreadsForUser($query, User $user)
    {
        // return $query->where('user_id', $user->id)
        //     ->has('messages')
        //     ->with('messages', function($mQuery){
        //         $mQuery->orderBy('created_at', 'ASC');
        //     });

        return $query->select(
                'contacts.*',
                DB::raw('messages.body as recent_message_body'),
                DB::raw('messages.created_at as recent_message_created_at')
            )
            ->join('messages', 'contacts.id', '=', 'messages.contact_id')
            ->where('contacts.user_id', $user->id)
            ->orderBy('messages.created_at', 'DESC')
            ->groupBy('contacts.id', 'messages.created_at', 'messages.body');
    }


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
}
