<?php

namespace App\DTO;

use App\Entity\Camera;
use App\Entity\Rover;
use Symfony\Component\Validator\Constraints as Assert;

class PhotosParamsInput
{
    public function __construct(
        #[Assert\Date]
        #[Assert\Expression(
            "this.getStartDate() === null || (this.getStartDate() !== null && this.getEndDate() !== null)",
            "Missing key end_date"
        )]
        public string|null $start_date,
        #[Assert\Date]
        #[Assert\Expression(
            "this.getEndDate() === null || (this.getStartDate() !== null && this.getEndDate() !== null)",
            "Missing key start_date"
        )]
        public string|null $end_date,
        #[Assert\Date]
        public string|null $date,
        #[Assert\Choice(Rover::RoversNames)]
        public string|null $rover,
        #[Assert\Choice(Camera::CameraNames)]
        public string|null $camera,
    )
    {
    }

    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    public function getEndDate(): ?string
    {
        return $this->end_date;
    }
}