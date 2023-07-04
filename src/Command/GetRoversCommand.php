<?php

namespace App\Command;

use App\Service\RoverProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:get-rovers',
    description: 'Gets rover and cameras data from NASA API and saves it to database',
)]
class GetRoversCommand extends Command
{
    public function __construct(
       private RoverProvider $roverProvider
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $rovers = $this->roverProvider->getRovers();
            $this->roverProvider->clearTables();
            $this->roverProvider->saveRovers($rovers);
        }catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $io->success(sprintf('You have successfully saved %s rovers and their cameras to database', count($rovers)));

        return Command::SUCCESS;
    }
}
