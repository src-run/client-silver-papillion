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
use SR\Utility\ClassInstantiator;

/**
 * Category AbstractModel
 */
abstract class AbstractModel
{
    /**
     * @var string[]
     */
    const DATA_KEYS_REQUIRED = [];

    /**
     * @var string[]
     */
    const DATA_KEYS_EXCLUDED = [];

    /**
     * @var string[]
     */
    const DATA_KEYS_FILTERED = [];

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
     * @var AbstractModel
     */
    protected $parent;

    /**
     * @var string|null
     */
    protected $key;

    /**
     * @param array|null  $data
     * @param string|null $field
     *
     * @return static
     */
    public static function create(array $data = null, $key = null)
    {
        $instance = new static();

        if ($data) {
            $instance->hydrate($data, $key);
        }

        return $instance;
    }

    /**
     * @param array       $data
     * @param string|null $key
     *
     * @throws FacebookException
     *
     * @return $this
     */
    public function hydrate(array $data = null, $key = null)
    {
        $this->setKey($key);

        try {
            $this->assertNonConflictingModelConfig();
            $data = $this->removeExtraKeyDepth($data);
            $this->assertRequiredFieldsExist($data);
        }
        catch (FacebookException $exception) {
            throw FacebookException::create()
                ->setMessage('Model hydration failed in pre-hydration sanity check operations')
                ->with($exception);
        }

        $this->assignDataToModel($this->removeExcludedFields($data));

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
     * @throws FacebookException
     *
     * @return $this
     */
    protected function assertNonConflictingModelConfig()
    {
        foreach (static::DATA_KEYS_REQUIRED as $k) {
            if (!in_array($k, static::DATA_KEYS_EXCLUDED)) {
                continue;
            }

            throw FacebookException::create()
                ->setMessage('Invalid model (%s) config: conflict between "required" (%s) and "excluded" (%s) as both contain "%s" element')
                ->with(static::class, var_export(static::DATA_KEYS_REQUIRED, true), var_export(static::DATA_KEYS_EXCLUDED, true), $k);
        }
    }

    /**
     * @return $this
     */
    protected function removeExtraKeyDepth($data)
    {
        return array_map(function ($v) {
            return isset($v['data']) ? $v['data'] : $v;
        }, $data);
    }

    /**
     * @throws FacebookException
     *
     * @return $this
     */
    protected function assertRequiredFieldsExist($data)
    {
        foreach (static::DATA_KEYS_REQUIRED as $k) {
            if ($this->hasField($data, $k)) {
                continue;
            }

            throw FacebookException::create()
                ->setMessage('A required data key (%s) does not exit in the response data array.')
                ->with($k);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function removeExcludedFields($data)
    {
        return array_filter($data, function ($v) use ($data) {
            return $this->hasFields($data, ...static::DATA_KEYS_FILTERED);
        });
    }

    /**
     *@return $this
     */
    protected function assignDataToModel($data)
    {
        foreach ($data as $index => $value) {
            $this->assignFieldToModel($value, $index);
        }

        return $this;
    }

    /**
     * @param mixed $data
     * @param string $key
     *
     * @return $this
     */
    protected function assignFieldToModel($data, $key)
    {
        list($property, , ) = $this->buildFieldMappingDefinition($key);

        if (!$this->getInspector()->hasProperty($property)) {
            return $this;
        }

        $this->getInspector()->getProperty($property)
            ->setValue($this, $this->transformField($data, $key));

        return $this;
    }

    /**
     * @param mixed $data
     * @param string $key
     *
     * @return bool
     */
    protected function transformField($data, $key)
    {
        list(, $model, $isCollection) = $this->buildFieldMappingDefinition($key);

        if ($isCollection) {
            return $this->transformFieldToModelAsCollection($model, $data, $key);
        }

        return $this->transformFieldToModelAsScalar($model, $data, $key);
    }

    /**
     * @param string  $model
     * @param mixed[] $data
     * @param string  $key
     *
     * @return mixed[]|object[]
     */
    protected function transformFieldToModelAsCollection($model, $data, $key)
    {
        if ($model === null) {
            return $data;
        }

        array_walk($data, function (&$data, $dataKey) use ($model, $key) {
            $data = $this->transformFieldToModelAsScalar($model, $data, $key, $dataKey);
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
    protected function transformFieldToModelAsScalar($model, $data, $key, $dataKey = null)
    {
        if (array_key_exists($key, static::VALUE_TRANSFORMERS)) {
            $data = call_user_func_array([static::VALUE_TRANSFORMERS[$key], 'transform'], [$data]);
        }

        if ($model !== null) {
            $data = call_user_func_array([static::MODEL_TRANSFORMER, 'transform'], [$data, $model, $key, $dataKey]);
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
        ];

        if (array_key_exists($key, $modelDefinition)) {
            foreach (['to_property', 'object_fqcn', 'object_coll'] as $k) {
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
