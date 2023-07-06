<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

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
        #[Autowire('%env(DEFAULT_YEAR)%')]
        private string             $defaultYear,
        #[Autowire('%env(DEFAULT_COUNTRY)%')]
        private string             $defaultCountry,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'year',
                'y',
                InputArgument::OPTIONAL,
                'Year of holidays that you want to get',
                $this->defaultYear
            )
            ->addOption(
                'country',
                'c',
                InputArgument::OPTIONAL,
                'Country of holidays that you want to get',
                $this->defaultCountry
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->holidaysCommand->execute($input, $output);
        $this->roversCommand->execute($input, $output);
        $this->photosCommand->execute($input, $output);

        $io->success('Successfully added data to database!');

        return Command::SUCCESS;
    }
}
