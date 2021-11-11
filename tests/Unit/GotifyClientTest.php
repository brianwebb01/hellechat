<?php

namespace Tests\Unit;

use GuzzleHttp\Psr7\Response as Psr7Response;
use App\Services\Gotify\Client;
use Tests\TestCase;

class GotifyClientTest extends TestCase
{
    /** @test */
    public function creates_basic_auth_client_as_expected()
    {
        $client = Client::createWithBasicAuth('foo', 'bar', 'url');
        $this->assertEquals(Client::AUTH_TYPE_BASIC, $client->authType());
        $this->assertTrue($client->isBasicClient());
    }

    /** @test */
    public function creates_token_client_as_expected()
    {
        $client = Client::createWithTokenAuth('token', 'url');
        $this->assertEquals(Client::AUTH_TYPE_TOKEN, $client->authType());
        $this->assertTrue($client->isTokenClient());
    }

    /** @test */
    public function api_make_request_works_with_basic_auth()
    {
        $url = 'https://gotifyhost.com';
        $method = 'POST';
        $path = '/path/to/resource';
        $mResponse = new Psr7Response(200, ['Content-Type' => 'application/json'], '{"data": "something"}');

        $this->mock(\GuzzleHttp\Client::class)
            ->shouldReceive('request')->once()
            ->with($method, $url . $path, [
                'auth' => [
                    'foo',
                    'bar'
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode(['foo' => 'bar', 'biz' => 'bang']),
            ])
            ->andReturn($mResponse);

        $client = Client::createWithBasicAuth('foo', 'bar', $url);
        $response = $client->apiMakeRequest($method, $path, ['foo' => 'bar', 'biz' => 'bang']);
        $this->assertEquals($mResponse, $response);
    }

    /** @test */
    public function api_make_request_works_with_token_auth()
    {
        $url = 'https://gotifyhost.com';
        $method = 'POST';
        $path = '/path/to/resource';
        $mResponse = new Psr7Response(200, ['Content-Type' => 'application/json'], '{"data": "something"}');

        $this->mock(\GuzzleHttp\Client::class)
            ->shouldReceive('request')->once()
            ->with($method, $url . $path, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'X-Gotify-Key' => 'token'
                ],
                'body' => json_encode(['foo' => 'bar', 'biz' => 'bang']),
            ])
            ->andReturn($mResponse);

        $client = Client::createWithTokenAuth('token', $url);
        $response = $client->apiMakeRequest($method, $path, ['foo' => 'bar', 'biz' => 'bang']);
        $this->assertEquals($mResponse, $response);
    }

    /** @test */
    public function create_user_works_as_expected()
    {
        $mResponse = new Psr7Response(
            200,
            ['Content-Type' => 'application/json'],
            '{
                "admin": false,
                "id": 25,
                "name": "unicorn"
            }'
        );

        $payload = [
            'admin' => false,
            'name' => 'unicorn',
            'pass' => 'password'
        ];

        $client = \Mockery::mock(Client::class)->makePartial();
        $client->shouldReceive('apiMakeRequest')->once()
            ->with('POST', '/user', $payload)
            ->andReturn($mResponse);

        $response = $client->createUser('unicorn', 'password');
        $this->assertEquals('unicorn', $response['name']);
        $this->assertEquals(25, $response['id']);
        $this->assertEquals(false, $response['admin']);
    }

    /** @test */
    public function create_application_works_as_expected()
    {
        $mResponse = new Psr7Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                "id" => 2,
                "token" => "somethingspecial",
                "name" => "ChatApp",
                "description" => "",
                "internal" => false,
                "image" => "static/defaultapp.png",
            ])
        );

        $payload = [
            'name' => 'ChatApp',
            'description' => null
        ];

        $client = \Mockery::mock(Client::class)->makePartial();
        $client->shouldReceive('apiMakeRequest')->once()
            ->with('POST', '/application', $payload)
            ->andReturn($mResponse);

        $response = $client->createApplication('ChatApp');
        $this->assertEquals('somethingspecial', $response['token']);
        $this->assertEquals(2, $response['id']);
    }

    /** @test */
    public function create_client_works_as_expected()
    {
        $mResponse = new Psr7Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                "id" => 20,
                "token" => "somethingspecial",
                "name" => "ChatClient"
            ])
        );

        $payload = [
            'name' => 'ChatClient'
        ];

        $client = \Mockery::mock(Client::class)->makePartial();
        $client->shouldReceive('apiMakeRequest')->once()
            ->with('POST', '/client', $payload)
            ->andReturn($mResponse);

        $response = $client->createClient('ChatClient');
        $this->assertEquals('somethingspecial', $response['token']);
        $this->assertEquals(20, $response['id']);
    }

    /** @test */
    public function create_message_works_as_expected()
    {
        $mResponse = new Psr7Response(
            200,
            ['Content-Type' => 'application/json'],
            '{
                "appid": 5,
                "date": "2018-02-27T19:36:10.5045044+01:00",
                "extras": {
                    "client::notification": {
                        "click": {
                            "url": "http://google.com"
                        }
                    }
                },
                "id": 25,
                "message": "**Backup** was successfully finished.",
                "priority": 8,
                "title": "Backup"
            }'
        );

        $payload = [
            'title' => "Backup",
            'message' => "**Backup** was successfully finished.",
            'priority' => 8,
            'extras' => [
                'client::notification' => [
                    'click' => [
                        'url' => "http://google.com"
                    ]
                ]
            ]
        ];

        $client = \Mockery::mock(Client::class)->makePartial();
        $client->shouldReceive('apiMakeRequest')->once()
            ->with('POST', '/message', $payload)
            ->andReturn($mResponse);

        $response = $client->createMessage("Backup", "**Backup** was successfully finished.", "http://google.com");
        $this->assertEquals(25, $response['id']);
    }

    /** @test */
    public function get_app_messages_works_as_expected()
    {
        $mResponse = new Psr7Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(
                [
                    "paging" => [
                        "size" => 1,
                        "since" => 0,
                        "limit" => 100,
                    ],
                    "messages" => [
                        [
                            "id" => 2,
                            "appid" => 400,
                            "message" => "fancy some spam?",
                            "title" => "SMS from +15610415878",
                            "priority" => 8,
                            "extras" => [
                                "client::notification" => [
                                    "click" => [
                                        "url" => "https://application.url.com/messages",
                                    ],
                                ],
                            ],
                            "date" => "2021-11-02T09:44:50Z",
                        ],
                    ],
                ]
            )
        );

        $client = \Mockery::mock(Client::class)->makePartial();
        $client->shouldReceive('apiMakeRequest')->once()
            ->with('GET', '/application/400/message?limit=1000')
            ->andReturn($mResponse);

        $response = $client->getApplicationMessages(400, 1000);
        $this->assertEquals(1, count($response['messages']));
        $this->assertEquals(400, $response['messages'][0]['appid']);
        $this->assertEquals(2, $response['messages'][0]['id']);
    }

    /** @test */
    public function get_app_messages_with_since_works_as_expected()
    {
        $mResponse = new Psr7Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(
                [
                    "paging" => [
                        "size" => 1,
                        "since" => 0,
                        "limit" => 100,
                    ],
                    "messages" => [
                        [
                            "id" => 2,
                            "appid" => 400,
                            "message" => "fancy some spam?",
                            "title" => "SMS from +15610415878",
                            "priority" => 8,
                            "extras" => [
                                "client::notification" => [
                                    "click" => [
                                        "url" => "https://application.url.com/messages",
                                    ],
                                ],
                            ],
                            "date" => "2021-11-02T09:44:50Z",
                        ],
                    ],
                ]
            )
        );

        $client = \Mockery::mock(Client::class)->makePartial();
        $client->shouldReceive('apiMakeRequest')->once()
            ->with('GET', '/application/400/message?limit=1000&since=100')
            ->andReturn($mResponse);

        $response = $client->getApplicationMessages(400, 1000, 100);
    }

    /** @test */
    public function delete_message_works_as_expected()
    {
        $mResponse = new Psr7Response(
            200,
            ['Content-Type' => 'application/json'],
            'Ok'
        );

        $client = \Mockery::mock(Client::class)->makePartial();
        $client->shouldReceive('apiMakeRequest')->once()
            ->with('DELETE', '/message/25')
            ->andReturn($mResponse);

        $response = $client->deleteMessage(25);
        $this->assertTrue($response);
    }

    /** @test */
    public function delete_user_works_as_expected()
    {
        $mResponse = new Psr7Response(
            200,
            ['Content-Type' => 'application/json'],
            'Ok'
        );

        $client = \Mockery::mock(Client::class)->makePartial();
        $client->shouldReceive('apiMakeRequest')->once()
            ->with('DELETE', '/user/2')
            ->andReturn($mResponse);

        $response = $client->deleteUser(2);
        $this->assertTrue($response);
    }

    /** @test */
    public function update_application_image_works_as_expected()
    {
        $this->markTestSkipped("Skipping b/c fopen() mocking");

        //sample response from gotify POST /application/ID/image
        $response = [
            "id" => 2,
            "token" => "--token--",
            "name" => "FooBar",
            "description" => "",
            "internal" => false,
            "image" => "image/KlwnqHH98yEwzNme4GXiPl2.N.png",
        ];
    }
}
