<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * method to search events by city and/or category
     *
     * @param [type] $city
     * @param [type] $category
     * @return Event[]
     */
    public function searchEventByCity($city, $category, $date)
    {
         $query = $this->createQueryBuilder('event')
            // we join the city property from the event table to the city table
            ->join('event.city', 'city')
            // we join the category property from the event table to the category table
            ->join('event.category', 'category')
            // we add a condition to find events by city
            ->where('city.name LIKE :name')
            ->setParameter(':name', "%$city%")
            ->orderBy('event.id','DESC');
                // if category is null
                if (!empty($category)) {
                    // we add condition to find event by category
                    $query->andWhere('category.id LIKE :category')
                    ->setParameter(':category', $category);
                };
                if (!empty($date)) {
                    // we add condition to find event by category
                    $query->andWhere('event.startAt LIKE :date')
                    ->setParameter(':date', $date);
                };
                return $query
            ->getQuery()
            ->getResult();
    }
}
