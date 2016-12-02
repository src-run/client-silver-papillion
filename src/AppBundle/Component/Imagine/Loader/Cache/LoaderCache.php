<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Imagine\Loader\Cache;

use Symfony\Component\Cache\Adapter\AbstractAdapter;

/**
 * Class LoaderCache.
 */
class LoaderCache extends AbstractAdapter implements LoaderCacheInterface
{
    /**
     * @var string
     */
    protected $directoryPath;

    /**
     * @param string   $directoryPath
     * @param int|null $defaultLifetime
     */
    public function __construct($directoryPath, $defaultLifetime = self::DEFAULT_LIFETIME)
    {
        parent::__construct('', $defaultLifetime);

        $this->directoryPath = static::ensureValidCacheDirectory($directoryPath);
    }

    /**
     * @param string $directory
     *
     * @return static
     */
    final public static function create($directory)
    {
        return new static($directory);
    }

    /**
     * @param string $directory
     *
     * @throws LoaderCacheException
     *
     * @return string
     */
    final public static function ensureValidCacheDirectory($directory)
    {
        if (!file_exists($directory)) {
            @mkdir($directory, 0777, true);
        }

        if (false === $realDirectory = realpath($directory)) {
            throw new LoaderCacheException(sprintf('Loader cache directory (%s) could not be created.', $directory));
        }

        if (false === is_writable($realDirectory)) {
            throw new LoaderCacheException(sprintf('Loader cache directory (%s) is not writable!', $realDirectory));
        }

        return $realDirectory.DIRECTORY_SEPARATOR;
    }

    /**
     * {@inheritdoc}
     */
    protected function doFetch(array $ids)
    {
        $results = array_map(function ($id) {
            return $this->doFetchSingle($id);
        }, $ids);

        return array_filter($results, function ($result) {
            return null !== $result;
        });
    }

    /**
     * @param string $id
     *
     * @return null|string
     */
    protected function doFetchSingle($id)
    {
        $filePath = $this->createItemFilePath($id);

        if (!$handle = @fopen($filePath, 'rb')) {
            return null;
        }

        $fileExp = fgets($handle);
        $fileKey = trim(rawurldecode(fgets($handle)));

        if (time() > $fileExp || $fileKey !== $id) {
            fclose($handle);
            $this->removeFilePath($filePath);

            return null;
        }

        $value = stream_get_contents($handle);
        fclose($handle);

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    protected function doHave($id)
    {
        $filePath = $this->createItemFilePath($id);

        return file_exists($filePath) && null !== $this->doFetchSingle($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function doClear($namespace)
    {
        $results = true;

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->directoryPath, \FilesystemIterator::SKIP_DOTS)) as $file) {
            $results = ($file->isDir() || @unlink($file) || !file_exists($file)) && $results;
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    protected function doDelete(array $ids)
    {
        $results = array_map(function ($id) {
            return $this->doDeleteSingle($id);
        }, $ids);

        return in_array(false, $results, true) !== true;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    protected function doDeleteSingle($id)
    {
        $filePath = $this->createItemFilePath($id);

        if (!file_exists($filePath) || ($this->removeFilePath($filePath) && file_exists($filePath))) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doSave(array $values, $lifetime)
    {
        $results = [];

        foreach ($values as $id => $value) {
            $results[] = $this->doSaveSingle($id, $value, $lifetime);
        }

        return in_array(false, $results, true) !== true;
    }

    /**
     * @param string $id
     * @param mixed  $value
     * @param int    $lifetime
     *
     * @return bool
     */
    protected function doSaveSingle($id, $value, $lifetime)
    {
        $lifetime = time() + ($lifetime ?: self::DEFAULT_LIFETIME);

        $saveValue = sprintf("%s\n%s\n", $lifetime, rawurlencode($id)).$value;
        $temporary = $this->directoryPath.uniqid('tmp_', true);

        if (false === @file_put_contents($temporary, $saveValue)) {
            return false;
        }

        return rename($temporary, $this->createItemFilePath($id, true));
    }

    /**
     * @param string $filePath
     *
     * @return bool
     */
    protected function removeFilePath($filePath)
    {
        return file_exists($filePath) ? @unlink($filePath) : false;
    }

    /**
     * @param string $id
     *
     * @return string
     */
    public static function getItemBasePath($id)
    {
        $hash = str_replace('/', '-', base64_encode(md5($id, true)));
        $path = $hash[0].DIRECTORY_SEPARATOR.$hash[1].DIRECTORY_SEPARATOR;

        return $path.substr(str_replace('/', '-', base64_encode(md5($id, true))), 2, -2);
    }

    /**
     * @param string $id
     * @param bool   $create
     *
     * @return string
     */
    protected function createItemFilePath($id, $create = false)
    {
        $directoryPath = $this->directoryPath.DIRECTORY_SEPARATOR.self::getItemBasePath($id);

        if (true === $create && false === file_exists(pathinfo($directoryPath, PATHINFO_DIRNAME))) {
            @mkdir(pathinfo($directoryPath, PATHINFO_DIRNAME), 0777, true);
        }

        return $directoryPath;
    }
}

/* EOF */
