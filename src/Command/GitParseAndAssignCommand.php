<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GitParseAndAssignCommand extends Command
{
    protected static $defaultName = 'app:git-parse-and-assign';

    protected function configure()
    {
        $this
            ->setDescription('Shortcut to run app:git-parse and app:assign-participation-user one by one');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Starting process');
        $this->runCommand('app:git-parse', $input, $output);
        $this->runCommand('app:assign-participation-user', $input, $output);
    }

    protected function runCommand($commandName, InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find($commandName);

        $input = new ArrayInput([
            'command' => $commandName,
        ]);

        return $command->run($input, $output);
    }
}
