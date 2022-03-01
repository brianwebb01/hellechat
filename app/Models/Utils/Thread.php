<?php

namespace App\Models\Utils;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class Thread
{
    /**
     * Function to return an array of message IDs that are
     * the most recent message to / from a particular phone number and
     * the count of unread messages associated with that number
     *
     * @param User $user
     * @return array
     */
    public static function getRecentThreadSql(User $user)
    {
        $sql = <<<SQL
        select
            `message_ids`.`id`, count(`rdata`.`id`) as unread
        from (
                select threads.id,
                        threads.from,
                        threads.to,
                        threads.created_at
                from (
                        -- get the most recent message created at to/from each unique number, with the number
                        select uniq_numbers.number,
                                MAX(m.created_at) as max_created
                        from (
                                -- get a distinct list of numbers involved in messaging
                                select distinct(number)
                                from (
                                            select `from` as number
                                            from messages
                                            where user_id = {$user->id}

                                            UNION ALL

                                            select `to` as number
                                            from messages
                                            where user_id = {$user->id}
                                        ) all_user_numbers
                            ) uniq_numbers
                                join
                            messages m on
                                        m.from = uniq_numbers.number
                                    or m.to = uniq_numbers.number
                        group by uniq_numbers.number
                    ) locator_records
                        join messages as threads on
                        (locator_records.number = threads.to OR locator_records.number = threads.from)
                        AND locator_records.max_created = threads.created_at
                group by threads.id
                order by threads.created_at desc
            ) message_ids
        left join
            messages rdata on
                rdata.read = 0 AND
                    (
                        rdata.from IN(message_ids.from, message_ids.to) AND
                        rdata.to IN(message_ids.from, message_ids.to)
                    )
        group by
            message_ids.id
        order by
             message_ids.created_at desc
        SQL;

        return json_decode(json_encode(DB::select($sql)), true);
    }

    /**
     * Function to add the number of unread messages to an already
     * processed request for thread data
     *
     * @param array $array - processed query data
     * @param array $data - read count data
     * @return void
     */
    public static function addReadCountsForRecentThreads($array, $data)
    {
        $readData = collect($data);

        for ($i = 0; $i < count($array['data']); $i++) {
            $read = $readData->where('id', $array['data'][$i]['id'])->pluck('unread')->first();
            $array['data'][$i]['unread'] = $read;
        }

        return $array;
    }

    /**
     * Function to take a paginated response that has been
     * converted toArray() and format it for api return data
     *
     * @param array $array
     * @return array
     */
    public static function formatApiResponse($array)
    {
        return [
            'data' => static::formatApiResponseData($array['data']),
            'links' => static::formatApiResponseLinks($array),
            'meta' => static::formatApiResponseMeta($array),
        ];
    }

    /**
     * Function to take paginated response data and format it similar to
     * ResourceCollection and return
     *
     * @param array $array
     * @return array
     */
    public static function formatApiResponseMeta($array)
    {
        return [
            'current_page' => $array['current_page'],
            'from' => $array['from'],
            'last_page' => $array['last_page'],
            'links' => $array['links'],
            'path' => $array['path'],
            'per_page' => $array['per_page'],
            'to' => $array['to'],
            'total' => $array['total'],
        ];
    }

    /**
     * Function to take paginated response data and format it similar to
     * ResourceCollection and return
     *
     * @param array $array
     * @return array
     */
    public static function formatApiResponseLinks($array)
    {
        return [
            'first' => $array['first_page_url'],
            'last' => $array['last_page_url'],
            'prev' => $array['prev_page_url'],
            'next' => $array['next_page_url'],
        ];
    }

    /**
     * Thread formatting of 'data' for api response
     *
     * @param array $data
     * @return array
     */
    public static function formatApiResponseData($data)
    {
        return array_map(function ($m) {
            $return = [];

            $return['id'] = $m['id'];
            $return['unread'] = $m['unread'];
            $return['number_id'] = $m['number_id'];
            $return['number_phone_number'] = $m['number']['phone_number'];

            if ($m['number']['phone_number'] == $m['from']) {
                $return['phone_number'] = $m['to'];
            } else {
                $return['phone_number'] = $m['from'];
            }

            if (is_null($m['body']) && $m['num_media'] > 0) {
                $return['preview'] = $m['media'][0];
            } else {
                $return['preview'] = $m['body'];
            }

            $return['contact'] = $m['contact'];
            $return['last_updated_at'] = \Carbon\Carbon::parse($m['created_at'])
                ->timezone(request()->user()->time_zone)
                ->format(\DateTime::ISO8601);

            return $return;
        }, $data);
    }
}
