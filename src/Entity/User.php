<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 *
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User implements \JsonSerializable
{
    const USER_STATE_ACTIVE = 1;
    const USER_STATE_INACTIVE = 0;

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
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @var Group|null
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    public function __construct(string $firstName, string $lastName, string $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;

        $this->state = self::USER_STATE_ACTIVE;
        $this->creationDate = new \DateTime();
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->state === self::USER_STATE_ACTIVE;
    }

    public function deactivate(): self
    {
        $this->state = self::USER_STATE_INACTIVE;

        return $this;
    }

    public function activate(): self
    {
        $this->state = self::USER_STATE_ACTIVE;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'group' => $this->group,
            'state' => $this->state,
            'creation_date' => $this->creationDate->format(DATE_W3C),
        ];
    }
}