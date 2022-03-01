<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\SweepGotifyServerMessages;
use App\Jobs\DeleteGotifyServerMessageJob;
use App\Models\User;
use App\Services\Gotify\Client;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\TestCase;

class SweepGotifyServerMessagesTest extends TestCase
{
    /** @test */
    public function command_runs_as_expected()
    {
        User::where('id', '>', 0)->delete();
        User::factory()->create([
            'gotify_client_token' => 'c_token_123',
            'gotify_app_id' => 123,
        ]);

        $knownDate = Carbon::parse('2018-02-27T19:36:10.5045044+01:00');
        Carbon::setTestNow($knownDate);

        $mGotify = \Mockery::mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('getApplicationMessages')
                ->once()
                ->with(123, 100)
                ->andReturn(json_decode(
                '{
                "messages": [
                    {
                    "appid": 123,
                    "date": "2018-02-17T19:36:10.5045044+01:00",
                    "extras": {
                        "home::appliances::lighting::on": {
                        "brightness": 15
                        },
                        "home::appliances::thermostat::change_temperature": {
                        "temperature": 23
                        }
                    },
                    "id": 25,
                    "message": "**Backup** was successfully finished.",
                    "priority": 2,
                    "title": "Backup"
                    },
                    {
                    "appid": 123,
                    "date": "2018-02-25T19:36:10.5045044+01:00",
                    "extras": {
                        "home::appliances::lighting::on": {
                        "brightness": 15
                        },
                        "home::appliances::thermostat::change_temperature": {
                        "temperature": 23
                        }
                    },
                    "id": 26,
                    "message": "**Backup** was successfully finished.",
                    "priority": 2,
                    "title": "Backup"
                    }
                ],
                "paging": {
                    "limit": 100,
                    "since": 5,
                    "size": 5
                }
                }', true));
        });
        $this->app->instance(Client::class, $mGotify);

        Queue::fake();

        $cmd = new SweepGotifyServerMessages();
        $cmd->handle();

        Queue::assertPushed(DeleteGotifyServerMessageJob::class, 1);
        Queue::assertPushed(function (DeleteGotifyServerMessageJob $job) {
            return $job->clientToken == 'c_token_123' &&
                $job->messageId == 25;
        });
    }

    /** @test */
    public function command_uses_pagination_as_expected()
    {
        User::where('id', '>', 0)->delete();
        User::factory()->create([
            'gotify_client_token' => 'c_token_123',
            'gotify_app_id' => 123,
        ]);

        $knownDate = Carbon::parse('2018-02-27T19:36:10.5045044+01:00');
        Carbon::setTestNow($knownDate);

        $mGotify = \Mockery::mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('getApplicationMessages')
                ->once()
                ->with(123, 100)
                ->andReturn(json_decode(
                '{
                "messages": [
                    {
                    "appid": 123,
                    "date": "2018-02-26T19:36:10.5045044+01:00",
                    "extras": {
                        "home::appliances::lighting::on": {
                        "brightness": 15
                        },
                        "home::appliances::thermostat::change_temperature": {
                        "temperature": 23
                        }
                    },
                    "id": 25,
                    "message": "**Backup** was successfully finished.",
                    "priority": 2,
                    "title": "Backup"
                    },
                    {
                    "appid": 123,
                    "date": "2018-02-25T19:36:10.5045044+01:00",
                    "extras": {
                        "home::appliances::lighting::on": {
                        "brightness": 15
                        },
                        "home::appliances::thermostat::change_temperature": {
                        "temperature": 23
                        }
                    },
                    "id": 26,
                    "message": "**Backup** was successfully finished.",
                    "priority": 2,
                    "title": "Backup"
                    }
                ],
                "paging": {
                    "limit": 100,
                    "next": "http://example.com/message?limit=50&since=123456",
                    "since": 5,
                    "size": 5
                }
                }',
                    true
                ));

            $mock->shouldReceive('getApplicationMessages')
            ->once()
                ->with(123, 100, 5)
                ->andReturn(json_decode(
                    '{
                "messages": [
                    {
                    "appid": 123,
                    "date": "2018-02-26T19:36:10.5045044+01:00",
                    "extras": {
                        "home::appliances::lighting::on": {
                        "brightness": 15
                        },
                        "home::appliances::thermostat::change_temperature": {
                        "temperature": 23
                        }
                    },
                    "id": 27,
                    "message": "**Backup** was successfully finished.",
                    "priority": 2,
                    "title": "Backup"
                    },
                    {
                    "appid": 123,
                    "date": "2018-02-16T19:36:10.5045044+01:00",
                    "extras": {
                        "home::appliances::lighting::on": {
                        "brightness": 15
                        },
                        "home::appliances::thermostat::change_temperature": {
                        "temperature": 23
                        }
                    },
                    "id": 28,
                    "message": "**Backup** was successfully finished.",
                    "priority": 2,
                    "title": "Backup"
                    }
                ],
                "paging": {
                    "limit": 100,
                    "since": 10,
                    "size": 5
                }
                }',
                    true
                ));
        });
        $this->app->instance(Client::class, $mGotify);

        Queue::fake();

        $cmd = new SweepGotifyServerMessages();
        $cmd->handle();

        Queue::assertPushed(DeleteGotifyServerMessageJob::class, 1);
        Queue::assertPushed(function (DeleteGotifyServerMessageJob $job) {
            return $job->clientToken == 'c_token_123' &&
            $job->messageId == 28;
        });
    }
}
