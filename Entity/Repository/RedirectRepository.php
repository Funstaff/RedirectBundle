<?php

namespace Funstaff\Bundle\RedirectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RedirectRepository.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectRepository extends EntityRepository
{
    /**
     * Get Destination From Source
     *
     * @param string    $source
     *
     * @return mixed    null or Redirect entity
     */
    public function getDestinationFromSource($source)
    {
        return $this->createQueryBuilder('r')
                ->where('r.source = :source AND r.isEnabled = :enabled')
                ->setParameters(array(
                    'source' => $source,
                    'enabled' => true
                ))
                ->getQuery()
                ->getOneOrNullResult();
    }
}