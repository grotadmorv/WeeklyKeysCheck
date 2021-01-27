<?php

namespace WeeklyCheckKeys;

use Dotenv\Dotenv;
use Discord\Discord;
use Discord\WebSockets\Event;
use Prooph\ServiceBus\QueryBus;
use Discord\Parts\Channel\Channel;
use WeeklyCheckKeys\Action\RunCommand;
use WeeklyCheckKeys\Utils\CommandValidator;
use WeeklyCheckKeys\Action\RunCommandHandler;
use Prooph\ServiceBus\Plugin\Router\QueryRouter;

class WeeklyCheckBot
{
    /**
     * @var string
     */
    private $discordToken;

    /**
     * @var Channel
     */
    private $channel = null;

    /**
     * @var string
     */
    private $message = null;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/..//', '.env.local');
        $dotenv->load();
        $this->discordToken = $_SERVER['DISCORD_BOT_TOKEN'];
    }

    public function onListening(): void
    {
        $discord = new Discord([
            'token' => $this->discordToken,
        ]);
        $discord->on('ready', function ($discord) {
            $discord->on('message', function ($message, $discord) {
                if(true === CommandValidator::verify($message->content)){
                    echo "{$message->author->username}: {$message->content}",PHP_EOL;
                    $commandBus = new QueryBus();
                    $router = new QueryRouter();
                    $router->route('WeeklyCheckKeys\Action\RunCommand')->to(new RunCommandHandler());
                    $router->attachToMessageBus($commandBus);
                    $promise = $commandBus->dispatch(new RunCommand($message->content));
                    $promise->then(function($result){
                        $this->message = $result;
                    });

                    $this->channel = $message->channel;
                    $this->channel->sendMessage($this->message, false)->done(function (Message $callback) {
                    }); 
                }
            });
        });
        
        $discord->run();
    }
}
