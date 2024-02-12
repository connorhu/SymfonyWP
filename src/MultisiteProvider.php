<?php

namespace SymfonyWP;

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
