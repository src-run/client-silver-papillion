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

final class LocalizedField implements LocalizedFieldInterface
{
    /**
     * @var string
     */
    private const DEFAULT_LOCALIZATION = 'en';

    /**
     * @var string
     */
    private $localization = self::DEFAULT_LOCALIZATION;

    /**
     * @var null|int
     */
    private $id;

    /**
     * @var null|string
     */
    private $code;

    /**
     * @var string[]
     */
    private $values;

    /**
     * @param int|null    $id
     * @param string|null $code
     * @param string[]    $values
     */
    public function __construct(int $id = null, string $code = null, array $values = [])
    {
        $this->id = $id;
        $this->code = $code;
        $this->values = $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue() ?? '';
    }

    /**
     * @param string $localization
     *
     * @return LocalizedFieldInterface|LocalizedField
     */
    public function setLocalization(string $localization): LocalizedFieldInterface
    {
        $this->localization = $localization;

        return $this;
    }

    /**
     * @param string $localization
     *
     * @return bool
     */
    public function hasLocalization(string $localization): bool
    {
        return array_key_exists($localization, $this->values) && null !== $this->values[$localization];
    }

    /**
     * @return string
     */
    public function getLocalization(): string
    {
        return $this->localization;
    }

    /**
     * @return bool
     */
    public function hasId(): bool
    {
        return null !== $this->id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function hasCode(): bool
    {
        return null !== $this->code;
    }

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function hasValue(): bool
    {
        return $this->hasLocalization($this->localization);
    }

    /**
     * @return null|string
     */
    public function getValue()
    {
        return $this->values[$this->localization] ?? null;
    }
}

/* EOF */
