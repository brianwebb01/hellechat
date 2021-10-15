<?php

namespace App\Models\Utils;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Thread
{
    public $phoneNumber;
    public $messages;
    public $lastUpdatedAt;
    public $previewBody;
    public $contact;

    /**
     * Function to get a listing of threads for the given
     * user.
     *
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    public static function threadsSummaryForUser(User $user)
    {
        return $user->messages()
            ->with('number', 'contact')
            ->whereIn('id', static::getThreadMessageIdsForUser($user))
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m) => static::newThreadFromMessage($m));
    }

    /**
     * Function to return an array of message IDs that are
     * the most recent message to / from a particular phone number
     *
     * - gets a list of distinct phone numbers used on messages
     *   both to and from
     *
     * - join a list of messages on from||to number matching the
     *   unique list, get the max created at, group by unique number
     *
     * - join in all messages where the unique number matches the
     *   from||to and having the created_at that matches the found
     *   max, group by id
     *
     * @param User $user
     * @return array
     */
    public static function getThreadMessageIdsForUser(User $user)
    {
        $sql = <<<SQL
        select
            threads.id
        from (
            select
                uniq_numbers.number,
                MAX(m.created_at) as max_created
            from (
                select
                    distinct(number)
                from (
                    select
                        `from` as number
                    from
                        messages
                    where
                        user_id = {$user->id}

                    UNION ALL

                    select
                        `to` as number
                    from
                        messages
                    where
                        user_id = {$user->id}
                ) all_user_numbers
            ) uniq_numbers
            join
                messages m on
                    m.from = uniq_numbers.number
                    or m.to = uniq_numbers.number
            group by
                uniq_numbers.number
        ) locator_records
        join messages as threads on
            (locator_records.number = threads.to OR locator_records.number = threads.from)
            AND locator_records.max_created = threads.created_at
        group by
            threads.id
        order by
            threads.created_at desc;
        SQL;

        return collect(
            json_decode(json_encode(DB::select($sql)), true)
        )->flatten();
    }

    /**
     * Function to take a message and convert it into a thread
     * instance.  The intended use is for building up summary
     * thread instances.
     *
     * @param Message $message
     * @return Thread
     */
    public static function newThreadFromMessage(Message $message)
    {
        $thread = new static;

        if ($message->number->phone_number == $message->from)
            $thread->phoneNumber = $message->to;
        else
            $thread->phoneNumber = $message->from;

        if (is_null($message->body) && $message->num_media > 0)
            $thread->previewBody = $message->media[0];
        else
            $thread->previewBody = $message->body;

        $thread->lastUpdatedAt = $message->created_at->timezone($message->user->time_zone)->format(\DateTime::ISO8601);

        $thread->contact = $message->contact;

        return $thread;
    }

    /**
     * Function to return a full thread with all messages belonging
     * to the given user and conversing with the given phone number
     *
     * @param User $user
     * @param string $phoneNumber
     * @return Thread
     */
    public static function getThread(User $user, $phoneNumber)
    {
        $thread = new static;

        $thread->phoneNumber = $phoneNumber;
        $thread->messages = $user->messages()
            ->where('from', $phoneNumber)
            ->orWhere('to', $phoneNumber)
            ->orderBy('created_at', 'ASC')
            ->get();

        $last = $thread->messages->last();

        if(is_null($last->body) && $last->num_media > 0){
            $thread->previewBody = $last->media[0];
        } else {
            $thread->previewBody = $last->body;
        }

        $thread->lastUpdatedAt = $last->created_at->timezone($last->user->time_zone)->format(\DateTime::ISO8601);
        $thread->contact = $user->contacts()
            ->firstWhere('phone_numbers', 'like', '%'.$phoneNumber.'%');

        return $thread;
    }

    /**
     * Convert the thread instance to an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'phone_number' => $this->phoneNumber,
            'last_updated_at' => $this->lastUpdatedAt,
            'preview_body' => $this->previewBody,
            'messages' => $this->messages->toArray(),
            'contact' => $this->contact ?
                $this->contact->toArray() : null
        ];
    }

    /**
     * Convert the thread instance to JSON
     *
     * @return string
     */
    public function toJson()
    {
        return \json_encode($this->toArray(), \JSON_PRETTY_PRINT);
    }
}