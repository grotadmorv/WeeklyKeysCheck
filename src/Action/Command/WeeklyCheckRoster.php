<?php

namespace WeeklyCheckKeys\Action\Command;

use Prooph\Common\Messaging\Command;
use WeeklyCheckKeys\Action\Command\CommandInterface;

class WeeklyCheckRoster extends Command implements CommandInterface
{
    /**
     * @var string
     */
    private $alias;

    protected $messageName = 'WeeklyCheckKeys\Action\Command\WeeklyCheckRoster';

    

    public function __construct()
    {
        $this->alias = 'weeklycheckroster';
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
}
