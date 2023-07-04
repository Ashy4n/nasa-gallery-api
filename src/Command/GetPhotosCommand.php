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
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'isPublic',
                InputArgument::OPTIONAL,
                'Define if you want get only public holidays',
                true
            )
            ->addArgument(
                'year',
                InputArgument::OPTIONAL,
                'Define rovers work year',
                2022
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $isPublic = $input->getArgument('isPublic');
        $year = $input->getArgument('year');

        $cameras = $this->cameraRepository->findBy(['name' => self::DEFAULT_CAMERAS]);
        $rovers = $this->roverRepository->findRoversByYear($year);

        try {
            $this->photoProvider->getPhotosFromHolidays($isPublic, $rovers, $cameras);
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        $io->success('You have successfully get photos from NASA API');

        return Command::SUCCESS;
    }
}
