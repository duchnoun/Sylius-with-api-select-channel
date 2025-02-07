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

namespace Sylius\Component\Core\Filesystem\Adapter;

interface FilesystemAdapterInterface
{
    public function has(string $location): bool;

    public function write(string $location, string $content): void;

    public function delete(string $location): void;
}
