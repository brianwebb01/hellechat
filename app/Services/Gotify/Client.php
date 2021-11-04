<?php

namespace App\Services\Gotify;

class Client
{
    const AUTH_TYPE_TOKEN = 'token';
    const AUTH_TYPE_BASIC = 'basic';

    private $url;
    private $token;
    private $basicUser;
    private $basicPass;


    public static function createWithBasicAuth($user, $pass, $url)
    {
        $client = app(self::class);
        $client->basicUser = $user;
        $client->basicPass = $pass;
        $client->url = $url;

        return $client;
    }


    public static function createWithTokenAuth($token, $url)
    {
        $client = app(self::class);
        $client->token = $token;
        $client->url = $url;

        return $client;
    }


    public function isTokenClient()
    {
        return $this->authType() == static::AUTH_TYPE_TOKEN;
    }


    public function isBasicClient()
    {
        return $this->authType() == static::AUTH_TYPE_BASIC;
    }


    public function authType()
    {
        if(isset($this->token))
            return static::AUTH_TYPE_TOKEN;

        if(isset($this->basicUser) && isset($this->basicPass)){
            return static::AUTH_TYPE_BASIC;
        }

        return null;
    }


    public function apiMakeRequest($method, $path, array $params = [], $strictParams=false)
    {
        $endpoint = $this->url . $path;

        if($strictParams === true){
            $payload = $params;
        } else {
            $payload = [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params),
            ];

            if ($this->isBasicClient()) {
                $payload['auth'] =  [
                    $this->basicUser,
                    $this->basicPass
                ];
            } elseif ($this->isTokenClient()) {
                $payload['headers']['X-Gotify-Key'] = $this->token;
            }
        }

        $client = app(\GuzzleHttp\Client::class);
        $response = $client->request($method, $endpoint, $payload);

        return $response;
    }


    public function createUser($username, $password, $admin=false)
    {
        $path = '/user';
        $payload = [
            'admin' => $admin,
            'name' => $username,
            'pass' => $password
        ];

        $response = $this->apiMakeRequest('POST', $path, $payload);

        return json_decode($response->getBody(), true);
    }


    public function deleteUser($id)
    {
        $path = '/user/' . $id;
        $response = $this->apiMakeRequest('DELETE', $path);

        return $response->getStatusCode() == 200;
    }


    public function createApplication($name, $description=null)
    {
        $path = '/application';
        $payload = [
            'name' => $name,
            'description' => $description
        ];

        $response = $this->apiMakeRequest('POST', $path, $payload);

        return json_decode($response->getBody(), true);
    }


    public function updateApplicationImage($id, $imagePath)
    {
        $path = "/application/{$id}/image";
        $payload = [
            'auth' => [$this->basicUser, $this->basicPass],
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($imagePath, 'r')
                ]
            ],
        ];

        $response = $this->apiMakeRequest('POST', $path, $payload, true);

        return json_decode($response->getBody(), true);
    }


    public function createClient($name)
    {
        $path = '/client';
        $payload = [
            'name' => $name
        ];

        $response = $this->apiMakeRequest('POST', $path, $payload);

        return json_decode($response->getBody(), true);
    }


    public function createMessage($title, $message, $url, $priority=8)
    {
        $path = '/message';
        $payload = [
            'title' => $title,
            'message' => $message,
            'priority' => $priority,
            'extras' => [
                'client:notification' => [
                    'click' => [
                        'url' => $url
                    ]
                ]
            ]
        ];

        $response = $this->apiMakeRequest('POST', $path, $payload);

        return json_decode($response->getBody(), true);
    }


    public function deleteMessage($id)
    {
        $path = '/message/'.$id;
        $response = $this->apiMakeRequest('DELETE', $path);

        return $response->getStatusCode() == 200;
    }


    public function getApplicationMessages($appId, $limit=100, $since=null)
    {
        $path = "/application/{$appId}/message?limit={$limit}";
        if($since)
            $path .= "&since={$since}";

        $response = $this->apiMakeRequest('GET', $path);

        return json_decode($response->getBody(), true);
    }
}