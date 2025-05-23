<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata\Provider;

use Enhavo\Component\Metadata\Extension\Config;
use Enhavo\Component\Metadata\Metadata;
use Enhavo\Component\Metadata\ProviderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ConfigProvider implements ProviderInterface
{
    private PropertyAccessor $propertyAccessor;

    public function __construct(
        private string $name,
    ) {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function provide(Metadata $metadata, $normalizedData)
    {
        if (array_key_exists($this->name, $normalizedData) && is_array($normalizedData[$this->name])) {
            $configs = [];

            $values = $this->propertyAccessor->getValue($metadata, $this->name);
            foreach ($values as $value) {
                $configs[] = $value;
            }

            foreach ($normalizedData[$this->name] as $key => $config) {
                $configs[] = new Config($key, $config);
            }

            $this->propertyAccessor->setValue($metadata, $this->name, $configs);
        }
    }
}
