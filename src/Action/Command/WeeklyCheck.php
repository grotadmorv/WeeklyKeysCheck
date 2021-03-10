<?php

namespace WeeklyCheckKeys\Action\Command;

use Prooph\Common\Messaging\Command;
use WeeklyCheckKeys\Action\Command\CommandInterface;

class WeeklyCheck extends Command implements CommandInterface
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $content;

    protected $messageName = 'WeeklyCheckKeys\Action\Command\WeeklyCheck';

    

    public function __construct()
    {
        $this->alias = 'weeklycheck';
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Return message payload as array
     */
    public function payload(): array
    {
        return ['text' => $this->text];
    }

    /**
     * This method is called when message is instantiated named constructor fromArray
     */
    protected function setPayload(array $payload): void
    {
        $this->text = $payload['text'];
    }

    public function addContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
