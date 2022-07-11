<?php

namespace VKolegov\DolyameAPI\Entities;

use DateTime;
use InvalidArgumentException;
use VKolegov\DolyameAPI\Contracts\Arrayable;

class ClientInfo implements Arrayable
{
    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $middleName = null;
    private ?DateTime $birthDate = null;
    private ?string $phone = null;
    private string $email;

    public function setFirstName(string $firstName): ClientInfo
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName(string $lastName): ClientInfo
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setMiddleName(string $middleName): ClientInfo
    {
        $this->middleName = $middleName;
        return $this;
    }

    public function setBirthDate(string $birthDate): ClientInfo
    {
        $d = DateTime::createFromFormat('Y-m-d', $birthDate);

        if (!$d) {
            throw new InvalidArgumentException(
                "wrong date format"
            );
        }

        $this->birthDate = $d;
        return $this;
    }

    public function setPhone(string $phone): ClientInfo
    {
        if (strpos($phone, '+') !== 0) {
            throw new InvalidArgumentException(
                "wrong phone format, should start with '+' sign"
            );
        }
        $this->phone = $phone;
        return $this;
    }

    public function setEmail(string $email): ClientInfo
    {
        $this->email = $email;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'middle_name' => $this->middleName,
            'birthdate' => $this->birthDate ? $this->birthDate->format('Y-m-d') : null,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
}