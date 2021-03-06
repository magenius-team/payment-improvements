<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\BundleGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\BundleGraphQl\Model\Resolver\Links\Collection;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Query\ResolverInterface;

/**
 * {@inheritdoc}
 */
class BundleItemLinks implements ResolverInterface
{
    /**
     * @var Collection
     */
    private $linkCollection;

    /**
     * @var ValueFactory
     */
    private $valueFactory;

    /**
     * @param Collection $linkCollection
     * @param ValueFactory $valueFactory
     */
    public function __construct(
        Collection $linkCollection,
        ValueFactory $valueFactory
    ) {
        $this->linkCollection = $linkCollection;
        $this->valueFactory = $valueFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null) : Value
    {
        if (!isset($value['option_id']) || !isset($value['parent_id'])) {
            $result = function () {
                return null;
            };
            return $this->valueFactory->create($result);
        }
        $this->linkCollection->addIdFilters((int)$value['option_id'], (int)$value['parent_id']);
        $result = function () use ($value) {
            return $this->linkCollection->getLinksForOptionId((int)$value['option_id']);
        };

        return $this->valueFactory->create($result);
    }
}
