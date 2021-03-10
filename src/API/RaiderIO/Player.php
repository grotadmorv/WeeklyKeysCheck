<?php

namespace WeeklyCheckKeys\API\RaiderIO;

use WeeklyCheckKeys\Message\Template;
use WeeklyCheckKeys\API\RaiderIO\RaiderIO;

class Player
{
    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $realm;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $response;

    /**
     * @var int
     */
    private $totalDungeon;

    /**
     * @var int
     */
    private $bestMythicLevel;

    /**
     * @var string
     */
    private $bestDungeonName;

    /**
     * @var \DateTime
     */
    private $completedAt;

    /**
     * @var string
     */
    private $clearTime;

    /**
     * @var string || null
     */
    private $errorMessage = null;

    public function __construct(string $region, string $realm, string $name)
    {
        $this->region = $region;
        $this->realm = $realm;
        $this->name = $name;
        $this->response = '';
        $this->findWeeklyData();
        $this->formatResponse();
    }

    public function findWeeklyData(): void
    {
        $raiderIo = new RaiderIO();
        $statusCode;
        try {
            $raiderIo->search($this->region, $this->realm, $this->name);
            $statusCode = $raiderIo->getHTTPCode();
        } catch (\Throwable $th) {
            $responseError = $th->getResponse();
            $this->errorMessage = json_decode($responseError->getBody()->getContents(), true)['message'];
            $statusCode = json_decode($responseError->getBody()->getContents(), true)['statusCode'];
        }
        if($statusCode !== 200){
            return;
        }

        $response = json_decode($raiderIo->getResponse(), true);

        $this->totalDungeon = count($response['mythic_plus_weekly_highest_level_runs']);
        if($this->totalDungeon !== 0){
            $this->bestMythicLevel = $response['mythic_plus_weekly_highest_level_runs'][0]['mythic_level'];
            $this->bestDungeonName = $response['mythic_plus_weekly_highest_level_runs'][0]['dungeon'];
            $this->completedAt = new \DateTime($response['mythic_plus_weekly_highest_level_runs'][0]['completed_at']);
            $ms = $response['mythic_plus_weekly_highest_level_runs'][0]['clear_time_ms'];
            $this->clearTime =floor($ms/60000).':'.floor(($ms%60000)/1000).':'.str_pad(floor($ms%1000),3,'0', STR_PAD_LEFT);
        }
    }


    public function formatResponse(): void 
    {
        if($this->errorMessage !== null){
            $this->response = $this->errorMessage;
            return;
        }
        if($this->totalDungeon === 0){
            $this->response = Template::getNoKeysMessage();
            return;
        }
        $this->response = sprintf(
            Template::getMessageWeekly(), 
            $this->name,
            $this->totalDungeon, 
            $this->bestMythicLevel, 
            $this->bestDungeonName, 
            $this->completedAt->format('Y-m-d H:i:s'), 
            $this->clearTime
        );
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function getRealm(): string
    {
        return $this->realm;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function getTotalDungeon(): int
    {
        return $this->totalDungeon;
    }

    public function getBestMythicLevel(): int
    {
        return $this->bestMythicLevel;
    }
}
