<?php

namespace WeeklyCheckKeys\API\RaiderIO;

use Dotenv\Dotenv;
use GuzzleHttp\Client;

class RaiderIO
{
    /**
     * @var string
     */
    private $fields = 'mythic_plus_weekly_highest_level_runs';

    /**
     * @var string
     */
    private $apiUrlRaiderIo;

    /**
     * @var int
     */
    private $HTTPCode;

    /**
     * @var string
     */
    private $response;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../..//', '.env.local');
        $dotenv->load();
        $this->apiUrlRaiderIo = $_SERVER['RAIDER_IO_API_URL_PROFILE'];
    }

    public function search(string $region, string $realm, string $player): void
    {
        $client = new Client();
        $params = [
            'query' => [
               'region' => $region,
               'realm' => $realm,
               'name' => $player,
               'fields' => $this->fields,
            ]
         ];
        $response = $client->request('GET', $this->apiUrlRaiderIo, $params);
        $this->HTTPCode = $response->getStatusCode();
        $this->response = $response->getBody()->getContents();
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function getHTTPCode(): int
    {
        return $this->HTTPCode;
    }
}
