<?php

namespace SymfonyWP;

// kell egy olyan cucc amivel ki lehet választani, hogy melyik multisite aktív
// kell egy olyan cucc ami a táblaneveket tudja átalakítani a megfelelő formátumba a kiválasztott multisite függvényében

class MultisiteProvider
{
    private int $multisiteNumber = 0;

    public function __construct()
    {
    }

    /**
     * @param int $multisiteNumber
     */
    public function setMultisiteNumber(int $multisiteNumber): void
    {
        $this->multisiteNumber = $multisiteNumber;
    }

    public function getMultisiteNumber(): int
    {
        return $this->multisiteNumber;
    }
}
