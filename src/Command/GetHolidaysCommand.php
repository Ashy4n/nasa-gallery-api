<?php

namespace App\Command;

use App\Service\HolidaysProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:get-holidays',
    description: 'This command adds to database all holidays from chosen country and year',
)]
class GetHolidaysCommand extends Command
{
    public  function __construct(private HolidaysProvider $holidaysProvider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::OPTIONAL, 'Country of holidays that you want to get')
            ->addArgument('year', InputArgument::OPTIONAL, 'Year of holidays that you want to get')//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $country = $input->getArgument('country');
        $year = $input->getArgument('year');

        if (!$country) {
            $country = 'PL';
        }
        if (!$year) {
            $year = 2022;
        }

        $io->info([sprintf("Country : %s", $country), sprintf("Year : %s", $year)]);

        try {
            $holidays = $this->holidaysProvider->get($country, $year);
            $this->holidaysProvider->save($holidays);
        }catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        $io->success(sprintf("Success ! Saved %s holidays", count($holidays)));

        return Command::SUCCESS;
    }
}
