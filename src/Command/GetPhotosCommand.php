<?php

namespace App\Command;

use App\Service\PhotoProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:get-photos',
    description: 'Add a short description for your command',
)]
class GetPhotosCommand extends Command
{
    public function __construct(private PhotoProvider $imageProvider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('public', 'p', InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

//        $img = $this->imageProvider->getImagesForHolidays();
//        dd($img);
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');


        return Command::SUCCESS;
    }
}
