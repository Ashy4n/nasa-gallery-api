<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class HolidayParamsInput
{
    public function __construct(

        #[Assert\Expression(
            "this.getStartDate() === null || (this.getStartDate() !== null && this.getEndDate() !== null)",
            "Missing key end_date"
        )]
        #[Assert\Date]
        public string|null $start_date,
        #[Assert\Expression(
            "this.getEndDate() === null || (this.getStartDate() !== null && this.getEndDate() !== null)",
            "Missing key start_date"
        )]
        #[Assert\Date]
        public string|null $end_date,
        #[Assert\Date]
        public string|null $date,
        #[Assert\Choice(['curiosity', 'spirit', 'opportunity','perseverance'])]
        public string|null $rover,
        #[Assert\Choice(['fhaz', 'rhaz'])]
        public string|null $camera
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