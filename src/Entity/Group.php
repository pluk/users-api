<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Group
 *
 * @ORM/Entity()
 * @ORM/Table()
 */
class Group
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
     * @ORM/Column(type="string", , unique=true)
     */
    private $name;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="User", mappedBy="group",
     *     cascade={"all"}, fetch="EXTRA_LAZY"
     * )
     */
    private $users;

    public function __construct()
    {
    }

    public function addUser(User $user)
    {
        $this->users->add($user);
    }
}