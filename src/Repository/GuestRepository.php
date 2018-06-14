<?php

namespace App\Repository;

use App\Entity\Guest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Guest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guest[]    findAll()
 * @method Guest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Guest::class);
    }

    public function getGuestList()
    {
        return $this->formatGuestList(
            $this->getGuests()
        );
    }

    public function getHeadCount()
    {
        return count(
            $this->getGuests()
        );
    }
    
    private function getGuests()
    {
        return $this->findBy(['attending' => true]);
    }

    /**
     * @var array $guests
     *
     * @return string $textFormat
     */
    private function formatGuestList($guests)
    {
        $textFormat = '';

        foreach ($guests as $guest) {
            $textFormat .= $guest->getFirstName();
            $textFormat .= ' ';
            $textFormat .= $guest->getLastName();

            if ($guest !== end($guests)) {
                $textFormat .= ', ';
            }
        }

        return $textFormat;
    }
}
