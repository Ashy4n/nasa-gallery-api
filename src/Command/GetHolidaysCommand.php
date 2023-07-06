<?php

namespace App\Command;

use App\Service\HolidaysProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;


#[AsCommand(
    name: 'app:get-holidays',
    description: 'This command adds to database all holidays from chosen country and year',
)]
class GetHolidaysCommand extends Command
{
    public function __construct(
        #[Autowire('%env(DEFAULT_YEAR)%')]
        private string           $defaultYear,
        #[Autowire('%env(DEFAULT_COUNTRY)%')]
        private string           $defaultCountry,
        private HolidaysProvider $holidaysProvider
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

        $country = $input->getOption('country');
        $year = $input->getOption('year');

        $io->info([
            sprintf("Country : %s", $country),
            sprintf("Year : %s", $year),
        ]);

        $question = new ConfirmationQuestion('Do you want to clear database before adding new records ?', true);
        $delete = $io->askQuestion($question);

        try {
            $holidays = $this->holidaysProvider->get($country, $year);
            $this->holidaysProvider->save($holidays, $delete);
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        $io->success(sprintf("Success ! Saved %s holidays", count($holidays)));

        return Command::SUCCESS;
    }
}
