<?php

namespace WeeklyCheckKeys\Action;

use React\Promise\Deferred;
use Prooph\ServiceBus\QueryBus;
use WeeklyCheckKeys\Action\RunCommand;
use WeeklyCheckKeys\CommandCollection;
use WeeklyCheckKeys\Utils\CommandValidator;
use Prooph\ServiceBus\Plugin\Router\QueryRouter;

class RunCommandHandler
{
    /**
     * String response in envelope commands
     *
     * @var string
     */
    private $response = '';

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
            $commandPath = new $commandPath;
            $commandPath->addContent($command->getContent());
            $promise = $commandBus->dispatch($commandPath);
            $promise->then(function($envelope){
                $this->response = $envelope;
            });

            return $deferred->resolve($this->response);
        }
    }
}
