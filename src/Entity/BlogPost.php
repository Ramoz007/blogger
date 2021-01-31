<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class BlogPost
{
    protected $id;
    /**
     * @Assert\NotBlank
     */
    protected $title;
    /**
     * @Assert\NotBlank
     */
    protected $body;
    /**
     * @Assert\NotBlank
     */
    protected $rates;

    //
    public function getID(): int
    {
        return $this->id;
    }
    public function setID(string $id): void
    {
        $this->id = $id;
    }

    //
    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    //
    public function getBody(): string
    {
        return $this->body;
    }
    public function setBody(string $body): void
    {
        $this->body = $body;
    }
    //
    public function getRates(): int
    {
        return $this->rates;
    }
    public function setRates(string $rates): void
    {
        $this->rates = $rates;
    }
}