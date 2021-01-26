<?php

namespace WeeklyCheckKeys;

use Dotenv\Dotenv;
use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\Parts\Channel\Channel;
use WeeklyCheckKeys\Utils\CommandCheck;

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
            echo "Bot is ready!", PHP_EOL;
        
            $discord->on('message', function ($message, $discord) {
                if(true === CommandCheck::verify($message->content)){
                    echo "{$message->author->username}: {$message->content}",PHP_EOL;
                    $this->channel = $message->channel;

                    $this->channel->sendMessage('Hello, world!', false)->done(function (Message $callback) {
                        var_dump($callback);
                    });   
                }
            });
        });
        
        $discord->run();
    }
}
