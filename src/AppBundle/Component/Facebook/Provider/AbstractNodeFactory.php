<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\NodeFactory;

use AppBundle\Component\Facebook\Authentication\AuthenticationInterface;
use AppBundle\Component\Facebook\Exception\FacebookException;
use AppBundle\Component\Facebook\Factory\FacebookFactory;
use AppBundle\Component\Facebook\Model\AbstractModel;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Class AbstractNodeFactory.
 */
abstract class AbstractNodeFactory
{
    /**
     * @var EndpointRequest
     */
    private $request;

    /**
     * @var string
     */
    const ENDPOINT_ROOT = '/%ID%';

    /**
     * @var string[]
     */
    const ENDPOINT_FIELDS = [];

    /**
     * @var string
     */
    private $id;

    /**
     * @var AuthenticationInterface
     */
    private $authentication;

    /**
     * @var AdapterInterface
     */
    protected $cache;

    /**
     * @var string
     */
    private $cacheTtl = 'P1D';

    /**
     * @param AuthenticationInterface|null $authentication
     * @param int|null                     $id
     */
    public function __construct(AuthenticationInterface $authentication = null, $id = null)
    {
        if ($authentication) {
            $this->setAuthentication($authentication);
        }

        if ($id) {
            $this->setId($id);
        }
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param AuthenticationInterface $authentication
     */
    public function setAuthentication(AuthenticationInterface $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @return AuthenticationInterface
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * @param AdapterInterface $cache
     */
    public function setCache(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return AdapterInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return string
     */
    public function getCacheTtl()
    {
        return $this->cacheTtl;
    }

    /**
     * @param string $cacheTtl
     */
    public function setCacheTtl($cacheTtl)
    {
        $this->cacheTtl = $cacheTtl;
    }

    /**
     * @return bool
     */
    public function hasCached()
    {
        return $this->getCachedItem()->isHit();
    }

    /**
     * @return bool
     */
    public function getCached()
    {
        return $this->getCachedItem()->get();
    }

    /**
     * @return bool
     */
    public function has()
    {
        return $this->get() !== null;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if ($this->hasCached()) {
            return $this->getCached();
        }

        return $this->refresh();
    }

    /**
     * @return mixed
     */
    protected function refresh()
    {
        $response = null;
        $cache = $this->getCachedItem();

        try {
            $cache->set($response = $this->getResponse());
            $cache->expiresAfter(new \DateInterval($this->getCacheTtl()));
        }
        catch (\Exception $e) {
            $cache->set(null);
            $cache->expiresAfter(new \DateInterval('PT3H'));
        }
        finally {
            $this->getCache()->save($cache);
        }

        return $response;
    }

    /**
     * @return AbstractModel|null
     *
     * @throws FacebookException
     */
    protected function getResponse()
    {
        try {
            $facebook = FacebookFactory::create(
                $this->getAuthentication()->getAppId(),
                $this->getAuthentication()->getAppSecret(),
                $this->getAuthentication()->getGraphVersion()
            );

            $response = $facebook->get(
                $this->getEndpoint(),
                $this->getAuthentication()->getAuthorization()->getToken()
            );

            return $this->hydrate($response);
        }
        catch(FacebookSDKException $exception) {
            throw FacebookException::create()
                ->setMessage('An error occured while requesting a Facebook API endpoint "%s": "%s"')
                ->with($this->getEndpoint(), $exception->getMessage(), $exception);
        }
    }

    /**
     * @param FacebookResponse $response
     *
     * @return AbstractModel
     */
    abstract protected function hydrate(FacebookResponse $response);

    /**
     * @param FacebookResponse $response
     *
     * @return AbstractModel
     */
    protected function hydrateDataList(FacebookResponse $response)
    {
        $data = $response->getDecodedBody();

        if (!$this->isDataList($data)) {
            return null;
        }

        return $this->hydrateModel($this->resolveDataList($data['data'])) ?: null;
    }

    /**
     * @param mixed[] $data
     *
     * @return null|AbstractModel
     */
    protected function hydrateModel($data)
    {
        return null;
    }

    /**
     * @return CacheItemInterface
     */
    protected function getCachedItem()
    {
        static $cacheItem;

        if ($cacheItem === null) {
            $cacheItem = $this->getCache()->getItem(sprintf('facebook.feed.%s.%s'.time(), $this->getId(), md5($this->getEndpoint())));
        }

        return $cacheItem;
    }

    /**
     * @param string $id
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return sprintf('%s?%s', str_replace('%ID%', $this->id, static::ENDPOINT_ROOT), $this->getEndpointFields());
    }

    /**
     * @return string
     */
    protected function getEndpointFields()
    {
        return sprintf('fields=%s', implode(',', static::ENDPOINT_FIELDS));
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    protected function isDataList($data)
    {
        return (bool) (is_array($data) && isset($data['data']) && is_array($data['data']));
    }

    /**
     * @param string  $property
     * @param mixed[] $data
     *
     * @return mixed[]
     */
    protected function createDataList($property, array $data)
    {
        return [
            $property => [
                'data' => $data
            ]
        ];
    }

    /**
     * @param mixed[] $data
     *
     * @return mixed[]
     */
    protected function resolveDataList(array $data)
    {
        $data = array_map(function ($value) {
            return isset($value['id']) ? $this->resolveDataListItem($value['id']) : null;
        }, $data);

        return array_filter($data, function ($value) {
            return $value !== null;
        });
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    protected function resolveDataListItem($id)
    {
        if (null === $fqcn = $this->getDataListProviderClass()) {
            return $id;
        }

        $reflection = new \ReflectionClass($fqcn);
        $provider = $reflection->newInstanceArgs([$this->getAuthentication(), $id]);
        $provider->setCache($this->getCache());

        return $provider->get();
    }

    /**
     * @return null|string
     */
    protected function getDataListProviderClass()
    {
        return null;
    }
}

/* EOF */
