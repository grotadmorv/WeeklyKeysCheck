<?php

namespace WeeklyCheckKeys\Action\Command;

use React\Promise\Deferred;
use WeeklyCheckKeys\API\RaiderIO\Player;
use WeeklyCheckKeys\Action\Command\WeeklyCheckRoster;

class WeeklyCheckRosterHandler
{
    public function __invoke(WeeklyCheckRoster $command, Deferred $deferred)
    {
        $roster = json_decode(file_get_contents(__DIR__.'/../../../data/team/roster.json'), true);
        $response = '';
        foreach($roster['roster'] as $playerObject){
            $player = new Player(
                $playerObject['region'],
                $playerObject['realm'],
                $playerObject['name'],
            );
            $response .= $player->getName().' : ';
            $str = $player->getTotalDungeon() < 10 ? ':x: '. $player->getTotalDungeon()  : ':white_check_mark: ' . $player->getTotalDungeon() ;
            if($player->getTotalDungeon() === 0){
                $str .= "\n";
            }else{
                $str .= ', max level key : ' . $player->getBestMythicLevel() ."\n";
            }
            $response .= sprintf($str);
        }

        return $deferred->resolve($response);
    }
}
