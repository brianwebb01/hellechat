<?php

namespace Tests\Unit;

use App\Models\User;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function hash_id_generates_as_expected()
    {
        $user = User::factory()->create();
        $hashids = new Hashids(config('app.key'), 6);
        $hid = $hashids->encode($user->id);
        $this->assertEquals($hid, $user->getHashId());
    }

    /**
     * @test
     */
    public function find_by_hash_id_works_as_expected()
    {
        $user = User::factory()->create();
        $hid = $user->getHashId();
        $found = User::findByHashId($hid);
        $this->assertEquals($user->id, $found->id);

        $this->expectException(ModelNotFoundException::class);
        User::findByHashId('fubar');
    }
}
