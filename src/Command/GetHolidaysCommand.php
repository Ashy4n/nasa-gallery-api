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




//        $client = HttpClient::create();
//        $response = $this->client->request('GET', 'https://holidayapi.com/v1/holidays?country=PL&year=2022&key=80d615c5-338b-45d0-a1c9-8d015e371904');
//        $statusCode = $response->getStatusCode();
//        $content = $response->toArray();
//        dd($content["holidays"]);


        if (!$country) {
            $country = 'PL';
        }
        if (!$year) {
            $year = '2022';
        }

        $this->holidaysProvider->getHolidaysFromAPI($country, $year);

        $io->info([sprintf("Country : %s", $country), sprintf("Year : %s", $year)]);



        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
