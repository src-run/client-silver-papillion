<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Location\Model\Field;

final class CollectionField implements FieldInterface, \Countable
{
    /**
     * @var FieldInterface[]
     */
    private $fields;

    /**
     * @var \Closure
     */
    private $reducer;

    /**
     * @param FieldInterface[] ...$fields
     */
    public function __construct(FieldInterface ...$fields)
    {
        $this->fields = $fields;
        $this->reducer = function (FieldInterface $field, int $index) {
            return 0 === $index;
        };
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue() ?? '';
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getReducedFieldSet());
    }

    /**
     * @param \Closure $reducer
     *
     * @return self
     */
    public function setReducer(\Closure $reducer = null): self
    {
        $this->reducer = $reducer;

        return $this;
    }

    /**
     * @param string $localization
     *
     * @return self
     */
    public function setLocalization(string $localization): self
    {
        foreach ($this->getLocalizedFields() as $field) {
            $field->setLocalization($localization);
        }

        return $this;
    }

    /**
     * @param string $localization
     *
     * @return bool
     */
    public function hasLocalization(string $localization): bool
    {
        foreach ($this->getLocalizedFields() as $field) {
            if (!$field->hasLocalization($localization)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getLocalization(): string
    {
        $localizations = array_unique(array_map(function (LocalizedFieldInterface $field) {
            return $field->getLocalization();
        }, $this->getLocalizedFields()));

        return implode(':', $localizations);
    }

    /**
     * @return bool
     */
    public function hasValue(): bool
    {
        return null !== $this->getValue();
    }

    /**
     * @return FieldInterface|mixed|null
     */
    public function getValue()
    {
        $field = $this->getReducedFieldOne();

        return null !== $field && $field->hasValue() ? $field->getValue() : null;
    }

    /**
     * @return FieldInterface|CollectionField|ScalarField|LocalizedField|NullField
     */
    public function getField(): FieldInterface
    {
        return $this->getReducedFieldOne();
    }

    /**
     * @return FieldInterface[]
     */
    public function getFields(): array
    {
        return $this->getReducedFieldSet();
    }

    /**
     * @return LocalizedFieldInterface[]
     */
    private function getLocalizedFields(): array
    {
        return array_filter($this->fields, function (FieldInterface $field) {
            return $field instanceof LocalizedFieldInterface;
        });
    }

    /**
     * @return FieldInterface|null
     */
    private function getReducedFieldOne(): ?FieldInterface
    {
        $reduced = $this->getReducedFieldSet();

        if (0 === count($reduced)) {
            return null;
        }

        return array_shift($reduced);
    }

    /**
     * @return FieldInterface[]
     */
    private function getReducedFieldSet(): array
    {
        return array_filter($this->fields, $this->reducer, ARRAY_FILTER_USE_BOTH);
    }
}

/* EOF */
