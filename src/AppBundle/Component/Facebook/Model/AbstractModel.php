<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Model;

use AppBundle\Component\Facebook\Exception\FacebookException;
use AppBundle\Component\Facebook\Transformer\TransformerInterface;
use SR\Reflection\Inspect;

/**
 * Category AbstractModel
 */
abstract class AbstractModel
{
    /**
     * @var TransformerInterface[]
     */
    const VALUE_TRANSFORMERS = [];

    /**
     * @var string
     */
    const MODEL_TRANSFORMER = '\AppBundle\Component\Facebook\Transformer\ModelTransformer';

    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [];

    /**
     * @var mixed[]
     */
    protected $data;

    /**
     * @var mixed[]
     */
    protected $dataOriginal;

    /**
     * @var string|null
     */
    protected $key;

    /**
     * @var mixed[]
     */
    protected $parameters;

    /**
     * @param array|null  $data
     * @param string|null $field
     * @param mixed       $parameters
     *
     * @return static
     */
    public static function create(array $data = null, $key = null, ...$parameters)
    {
        $instance = new static();

        if ($data) {
            $instance->hydrate($data, $key, ...$parameters);
        }

        return $instance;
    }

    /**
     * @param array       $data
     * @param string|null $key
     * @param mixed       $parameters
     *
     * @throws FacebookException
     *
     * @return $this
     */
    public function hydrate(array $data = null, $key = null, ...$parameters)
    {
        $this->setKey($key);
        $this->setData($data);
        $this->setParameters($parameters);

        $this->assignDataToModel();

        return $this;
    }

    /**
     * @param mixed[] $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $this->dataOriginal = $data;

        return $this;
    }

    /**
     * @param string|null $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param mixed[] $parameters
     *
     * @return $this
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return $this
     */
    protected function removeExtraKeyDepth()
    {
        $this->data = array_map(function ($value) {
            return ($value instanceof AbstractModel || !isset($value['data'])) ? $value : $value['data'];
        }, $this->data);

        return $this;
    }

    /**
     *@return $this
     */
    protected function assignDataToModel()
    {
        foreach ($this->data as $key => $data) {
            $this->assignFieldToModel($data, $key);
        }
        dump($this);
        die('FUCKIEIEIEIE');

        return $this;
    }

    /**
     * @param mixed  $data
     * @param string $key
     */
    protected function assignFieldToModel($data, $key)
    {
        $name = $this->buildFieldMappingDefinition($key)[0];
        dump('NAME:'.$name);

        if ($this->getInspector()->hasProperty($name)) {
            $this->getInspector()->getProperty($name)->setValue($this, $this->transformField($data, $key));
        }
    }

    /**
     * @param mixed $data
     * @param string $key
     *
     * @return mixed
     */
    protected function transformField($data, $key)
    {
        if (isset($data['data'])) {
            $data = $data['data'];
        }

        if (is_array($data)) {
            return $this->transformFieldToModelAsCollection($data, $key);
        }

        return $this->transformFieldToModelAsScalar($data, $key);
    }

    /**
     * @param mixed[] $data
     * @param string  $key
     *
     * @return mixed[]|object[]
     */
    protected function transformFieldToModelAsCollection($data, $key)
    {
        if (null === $model = $this->buildFieldMappingDefinition($key)[1]) {
            return $data;
        }

        array_walk($data, function (&$d, $k) use ($key) {
            $d = $this->transformFieldToModelAsScalar($d, $key, $k);
        });

        return array_values($data);
    }

    /**
     * @param string|null $model
     * @param mixed       $data
     * @param string      $key
     * @param string|null $dataKey
     *
     * @return string|int|object
     */
    protected function transformFieldToModelAsScalar($data, $key, $dataKey = null)
    {
        if ($data instanceof AbstractModel) {
            return $data;
        }

        if (array_key_exists($key, static::VALUE_TRANSFORMERS)) {
            $data = call_user_func_array([static::VALUE_TRANSFORMERS[$key], 'transform'], [$data]);
        }

        if (null !== $model = $this->buildFieldMappingDefinition($key)[1]) {
            if (false !== strpos($model, 'FeedAttachment')) {
                dump('BAILING FUCKER');
                dump($data);
                dump($key);
                dump($dataKey);
                dump($model);
                dump($this);
            }
            $data = call_user_func_array([static::MODEL_TRANSFORMER, 'transform'], [$data, $model, $key, $dataKey]);
            if (false !== strpos($model, 'FeedAttachment')) {
                dump($data);
                die('BAILING, FUCKER');
            }
        }

        return $data;
    }

    /**
     * @param string $key
     *
     * @return string[]
     */
    protected function buildFieldMappingDefinition($key)
    {
        $modelDefinition = static::MAPPING_DEFINITION;
        $fieldDefinition = [
            'to_property' => $key,
            'object_fqcn' => null,
            'object_coll' => false,
            'pre_fetched' => false,
        ];

        if (array_key_exists($key, $modelDefinition)) {
            foreach (['to_property', 'object_fqcn', 'object_coll', 'pre_fetched'] as $k) {
                if (isset($modelDefinition[$key][$k])) {
                    $fieldDefinition[$k] = $modelDefinition[$key][$k];
                }
            }
        }

        return array_values($fieldDefinition);
    }

    /**
     * @param array $data
     * @param array ...$keys
     *
     * @return bool
     */
    protected function hasFields(array $data, ...$keys)
    {
        $unavailableKeys = array_filter($keys, function ($k) use ($data) {
            return !$this->hasField($data, $k);
        });

        return count($unavailableKeys) === 0;
    }

    /**
     * @param mixed[] ...$data
     * @param string  $key
     *
     * @return bool
     */
    public function hasField(array $data, $key)
    {
        return $this->getField($data, $key) !== null;
    }

    /**
     * @param mixed[] ...$data
     * @param string  $key
     *
     * @return null|mixed[]
     */
    public function getField(array $data, $key)
    {
        $part = explode('.', $key);
        $size = count($part);

        for ($i = 0; $i < $size; $i++) {
            if (!array_key_exists($part[$i], $data)) {
                return null;
            }

            $data = $data[$part[$i]];
        }

        return isset($data['data']) ? $data['data'] : $data;
    }

    /**
     * @param string|null $what
     *
     * @return \SR\Reflection\Introspection\ClassIntrospection|\SR\Reflection\Introspection\InterfaceIntrospection|\SR\Reflection\Introspection\ObjectIntrospection|\SR\Reflection\Introspection\TraitIntrospection
     */
    protected function getInspector($what = null)
    {
        static $inspector;

        if ($what === null) {
            $what = $this;
        }

        if ($inspector === null) {
            $inspector = Inspect::this($what);
        }

        return $inspector;
    }
}

/* EOF */
