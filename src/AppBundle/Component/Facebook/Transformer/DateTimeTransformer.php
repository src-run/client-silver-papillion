<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Transformer;

use AppBundle\Component\Facebook\Exception\FacebookException;

/**
 * Class DateTimeTransformer.
 */
class DateTimeTransformer implements TransformerInterface
{
    /**
     * @var string
     */
    const DATE_FORMAT = 'Y-m-d\TH:i:sO';

    /**
     * {@inheritdoc}
     *
     * @throws FacebookException
     *
     * @return \DateTime
     */
    public static function transform($data, ...$parameters)
    {
        $dateTime = \DateTime::createFromFormat(static::DATE_FORMAT, $data);

        if ($dateTime->format(static::DATE_FORMAT) == $data) {
            return $dateTime;
        }

        throw FacebookException::create()
            ->setMessage('Could not transform data value (%s) to \DateTime object instance.')
            ->with($data);
    }
}

/* EOF */
