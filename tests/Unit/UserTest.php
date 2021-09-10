<?php

namespace Tests\Unit;

use App\Models\User;
use Hashids\Hashids;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function hash_id_generates_as_expected()
    {
        $user = User::factory()->create();
        $hashids = new Hashids($user->created_at, 6);
        $hid = $hashids->encode($user->id);
        $this->assertEquals($hid, $user->getHashId());
    }
}
