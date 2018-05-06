<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\FormatterBundle\Form\EventListener;

use Sonata\FormatterBundle\Formatter\Pool;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class FormatterListener
{
    /**
     * @var Pool
     */
    protected $pool;

    /**
     * @var string
     */
    protected $formatField;

    /**
     * @var string
     */
    protected $sourceField;

    /**
     * @var string
     */
    protected $targetField;

    public function __construct(Pool $pool, string $formatField, string $sourceField, string $targetField)
    {
        $this->pool = $pool;

        $this->formatField = $formatField;
        $this->sourceField = $sourceField;
        $this->targetField = $targetField;
    }

    public function postSubmit(FormEvent $event): void
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $format = $accessor->getValue($event->getData(), $this->formatField);
        $source = $accessor->getValue($event->getData(), $this->sourceField);

        // make sure the listener works with array
        $data = $event->getData();

        $accessor->setValue($data, $this->targetField, $this->pool->transform($format, $source));

        $event->setData($data);
    }
}
