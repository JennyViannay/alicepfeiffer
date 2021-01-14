<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait Author
{
    /**
     * @ORM\Column(type="string")
     */
    private $author;

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

}