<?php

namespace App\DTO;

use App\Entity\User;

class SmsResponse
{
    public string $fullName;
    public string $phoneNumber;
    public function __construct(User $user)
    {
        $this->fullName = $user->getFirstname(). ' '. $user->getLastname();
        $this->phoneNumber = $user->getTelephone();
    }
}