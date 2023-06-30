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
    name: 'app:get-holidays',
    description: 'This command adds to database all holidays from chosen country and year',
)]
class GetHolidaysCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::OPTIONAL, 'Country of holidays that you want to get')
            ->addArgument('year', InputArgument::OPTIONAL, 'Year of holidays that you want to get')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $country = $input->getArgument('country');
        $year = $input->getArgument('year');

        if ($country) {
            $io->note(sprintf('You passed an argument: %s', $country));
        }else $country = 'PL';

        if ($year) {
            $io->note(sprintf('You passed an argument: %s', $year));
        }else $year = '2021';

//        if ($input->getOption('option1')) {
//            // ...
//        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
