<?php

namespace WeeklyCheckKeys;

class CommandCollection
{
    private $commands;

    public function __construct()
    {
        foreach (glob(__DIR__.'/Action/Command/Weekly*.php') as $file)
        {
            $class = 'WeeklyCheckKeys\Action\Command\\'.basename($file, '.php');
            if (class_exists($class) && !strpos($class, 'Handler'))
            {
                $obj = new $class;
                $reflexionClass = new \ReflectionClass($obj);
                $this->commands[$obj->getAlias()] = $reflexionClass->getName();
            }
        }
    }

    public function add($command, string $alias)
    {
        $this->commands[$alias] = $command;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function support(string $alias): bool
    {
        return isset($this->commands[$alias]);
    }

    public function getCommand(string $alias)
    {
        return $this->commands[$alias];
    }
    public function getCommandHandler(string $alias)
    {
        $command = $this->commands[$alias];

        return $command.'Handler';
    }
}
