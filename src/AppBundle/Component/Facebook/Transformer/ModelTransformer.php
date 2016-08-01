<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Transformer;

use AppBundle\Component\Facebook\Model\AbstractModel;
use SR\Utility\ClassInstantiator;

/**
 * Class AuthorTransformer.
 */
class ModelTransformer implements TransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @return AbstractModel
     */
    public static function transform($data, ...$parameters)
    {
        $key = isset($parameters[2]) && is_string($parameters[2]) ? $parameters[2] : $parameters[1];

        $instance = static::instantiateModel($parameters[0]);
        $instance->hydrate($data, $key);

        return $instance;
    }

    /**
     * @param string $model
     *
     * @return AbstractModel
     */
    protected static function instantiateModel($model)
    {
        return ClassInstantiator::instantiate($model);
    }
}

/* EOF */
