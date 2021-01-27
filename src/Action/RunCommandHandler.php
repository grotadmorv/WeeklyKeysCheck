<?php

namespace WeeklyCheckKeys\Action;

use React\Promise\Deferred;
use WeeklyCheckKeys\Message\Template;
use WeeklyCheckKeys\Action\RunCommand;
use WeeklyCheckKeys\API\RaiderIO\Player;
use WeeklyCheckKeys\Utils\CommandValidator;

class RunCommandHandler
{
    public function __invoke(RunCommand $command, Deferred $deferred)
    {
        $commandBase = CommandValidator::getCommandBase($command->getContent());
        if($commandBase === CommandValidator::COMMAND_WEEKLY_CHECK){
            $args = explode(' ', $command->getContent());
            if(count($args) !== 4){
                return $deferred->resolve(Template::getErrorArgsMessage());
            }
            $player = new Player(
                $args[1],
                $args[2],
                $args[3],
            );
        }
        $deferred->resolve($player->getResponse());
    }
}
