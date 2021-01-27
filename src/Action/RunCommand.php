<?php

namespace WeeklyCheckKeys\Action;

use Prooph\Common\Messaging\Command;

class RunCommand extends Command
{
    /**
     * @var string
     */
    private $content;
    
    protected $messageName = 'WeeklyCheckKeys\Action\RunCommand';
    
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
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
