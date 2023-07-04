<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:default-setup',
    description: 'Setup the default data for the app',
)]
class DefaultSetupCommand extends Command
{
    public function __construct(
        private GetHolidaysCommand $holidaysCommand,
        private GetRoversCommand   $roversCommand,
        private GetPhotosCommand   $photosCommand,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'country',
                InputArgument::OPTIONAL,
                'Country of holidays that you want to get',
                'PL'
            )
            ->addArgument(
                'year',
                InputArgument::OPTIONAL,
                'Year of holidays that you want to get',
                2022
            )
            ->addArgument(
                'isPublic',
                InputArgument::OPTIONAL,
                'Define if you want get only public holidays',
                true
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->holidaysCommand->execute($input, $output);
        $this->roversCommand->execute($input, $output);
        $this->photosCommand->execute($input, $output);

        $io->success('Successfully setup the default data!');

        return Command::SUCCESS;
    }
}
