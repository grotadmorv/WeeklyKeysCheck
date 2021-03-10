<?php

namespace WeeklyCheckKeys\Action\Command;

use React\Promise\Deferred;
use WeeklyCheckKeys\Message\Template;
use WeeklyCheckKeys\API\RaiderIO\Player;
use WeeklyCheckKeys\Action\Command\WeeklyCheck;

class WeeklyCheckHandler
{
    public function __invoke(WeeklyCheck $command, Deferred $deferred)
    {
        $args = explode(' ', $command->getContent());
        if(count($args) !== 4){
            return $deferred->resolve(Template::getErrorArgsMessage());
        }
        $player = new Player(
            $args[1],
            $args[2],
            $args[3],
        );

        return $deferred->resolve($player->getResponse());
    }
}
