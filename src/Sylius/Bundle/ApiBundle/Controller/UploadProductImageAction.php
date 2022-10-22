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

namespace Sylius\Bundle\ApiBundle\Controller;

use ApiPlatform\Core\Api\IriConverterInterface;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Bundle\ApiBundle\Serializer\ContextKeys;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Repository\ProductImageRepositoryInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

/** @experimental */
final class UploadProductImageAction
{
    public function __construct(
        private FactoryInterface $productImageFactory,
        private ProductImageRepositoryInterface $productImageRepository,
        private ImageUploaderInterface $imageUploader,
        private IriConverterInterface $iriConverter,
        private ProductRepositoryInterface $productRepository,
    ) {
    }

    public function __invoke(Request $request): ImageInterface
    {

        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        /** @var ImageInterface $image */
        $image = $this->productImageFactory->createNew();
        $image->setFile($file);

//        /** @var string $ownerIri */
        $code = $request->request->get('code');
        Assert::notEmpty($code);

        $product = $this->productRepository->findOneByCode($code);

        Assert::isInstanceOf($product, ProductInterface::class);
        $image->setOwner($product);

        $this->imageUploader->upload($image);

        return $image;
    }
}
