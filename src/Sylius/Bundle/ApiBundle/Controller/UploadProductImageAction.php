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
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Repository\ProductImageRepositoryInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

/** @experimental */
final class UploadProductImageAction
{
    public function __construct(
        private FactoryInterface $productImageFactory,
        private ProductImageRepositoryInterface $productImageRepository,
        private ImageUploaderInterface $imageUploader,
        private IriConverterInterface $iriConverter,
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
        $ownerIri = $request->request->get('owner');
        Assert::notEmpty($ownerIri);

//        /** @var ResourceInterface|AdminUserInterface $owner */
//        dd($ownerIri);

        $owner = $this->iriConverter->getItemFromIri($ownerIri); // Voir pourquoi le channel est prit en compte ?
        Assert::isInstanceOf($owner, ProductInterface::class);
//
        $image->setOwner($owner);
//        $oldImage = $owner->getImage();
//        if ($oldImage !== null) {
//            $this->productImageRepository->remove($oldImage);
//        }
//        $owner->setImage($image);

        $this->imageUploader->upload($image);

        return $image;
    }
}
