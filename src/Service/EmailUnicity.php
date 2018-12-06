<?php
/**
 * Created by PhpStorm.
 * User: wilder21
 * Date: 22/11/18
 * Time: 13:02
 */

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class EmailUnicity
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @param EntityManagerInterface $em
     */
    public function setEm(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function verify(string $email):bool
    {
        $repository = $this->getEm()->getRepository(User::class);
        $user = $repository->findOneBy(['email' => $email]);
        if ($user!=null) {
            return false;
        } else {
            return true;
        }
    }
}
