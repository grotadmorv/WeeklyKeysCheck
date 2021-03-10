<?php

namespace WeeklyCheckKeys\Action;

use React\Promise\Deferred;
use Prooph\ServiceBus\QueryBus;
use WeeklyCheckKeys\Message\Template;
use WeeklyCheckKeys\Action\RunCommand;
use WeeklyCheckKeys\CommandCollection;
use WeeklyCheckKeys\API\RaiderIO\Player;
use WeeklyCheckKeys\Utils\CommandValidator;
use Prooph\ServiceBus\Plugin\Router\QueryRouter;

class RunCommandHandler
{
    public function __invoke(RunCommand $command, Deferred $deferred)
    {
        $commandBase = CommandValidator::getCommandBase($command->getContent());
        $commandCollection = new CommandCollection();
        if($commandCollection->support($commandBase)){
            $commandPath = $commandCollection->getCommand($commandBase);
            $commandPathHandler = $commandCollection->getCommandHandler($commandBase);;
            $commandBus = new QueryBus();
            $router = new QueryRouter();
            $router->route($commandPath)->to(new $commandPathHandler);
            $router->attachToMessageBus($commandBus);
            $promise = $commandBus->dispatch(new $commandPath());
            $promise->then(function($result){
                $this->message = $result;
            });
        }


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
        if($commandBase === CommandValidator::COMMAND_WEEKLY_CHECK_ROSTER) {
            $roster = json_decode(file_get_contents(__DIR__.'/../../data/team/roster.json'), true);
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
        $deferred->resolve($player->getResponse());
    }
}
