<?php

namespace App\Command;

use App\Repository\CameraRepository;
use App\Repository\RoverRepository;
use App\Service\PhotoProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:get-photos',
    description: 'Gets photos for defined holidays from NASA API'
)]
class GetPhotosCommand extends Command
{
    private const DEFAULT_CAMERAS = ['FHAZ', 'RHAZ'];

    public function __construct(
        private PhotoProvider    $photoProvider,
        private RoverRepository  $roverRepository,
        private CameraRepository $cameraRepository,
        #[Autowire('%env(DEFAULT_YEAR)%')]
        private string           $defaultYear,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->
        addOption(
            'year',
            'y',
            InputArgument::OPTIONAL,
            'Year of holidays that you want to get',
            $this->defaultYear
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $year = $input->getOption('year');

        $cameras = $this->cameraRepository->findBy(['name' => self::DEFAULT_CAMERAS]);
        $rovers = $this->roverRepository->findRoversByYear($year);

        try {
            $this->photoProvider->getPhotosFromHolidays($rovers, $cameras);
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        $io->success('You have successfully get photos from NASA API');

        return Command::SUCCESS;
    }
}
