<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Doctrine\ORM;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Repository\ProductImageRepositoryInterface;

final class ProductImageRepository extends EntityRepository implements ProductImageRepositoryInterface
{
    public function findOneByOwnerId(string $id): ?ImageInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.owner = :ownerId')
            ->setParameter('ownerId', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
