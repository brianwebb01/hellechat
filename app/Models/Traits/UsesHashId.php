<?php

namespace App\Models\Traits;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait UsesHashId
{
    public function getHashId()
    {
        $hashids = new Hashids(config('app.key'), 6);
        $hid = $hashids->encode($this->id);

        return $hid;
    }

    public static function findByHashId($hashId)
    {
        $hashids = new Hashids(config('app.key'), 6);
        $decoded = $hashids->decode($hashId);

        if (empty($decoded)) {
            throw new ModelNotFoundException();
        }

        return static::find($decoded[0]);
    }
}
