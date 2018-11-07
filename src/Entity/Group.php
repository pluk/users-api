<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Group
 *
 * @ORM\Entity()
 * @ORM\Table(name="groups")
 */
class Group implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}