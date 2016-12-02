<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Provider;

use AppBundle\Component\Facebook\Authentication\AuthenticationInterface;
use AppBundle\Component\Facebook\Exception\FacebookException;
use AppBundle\Component\Facebook\Factory\FacebookFactory;
use AppBundle\Component\Facebook\Model\AbstractModel;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;

/**
 * Class AbstractProvider.
 */
abstract class AbstractProvider implements ProviderInterface
{
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
    protected $cacheTtl = 'P1D';

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
        $cache = $this->getCachedItem();
        $cache->set($response = $this->getResponse());
        $cache->expiresAfter(new \DateInterval($this->getCacheTtl()));
        $this->getCache()->save($cache);

        return $response;
    }

    /**
     * @throws FacebookException
     *
     * @return AbstractModel|null
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
        } catch (FacebookSDKException $exception) {
            throw FacebookException::create(
                'An error occured while requesting a Facebook API endpoint "%s": "%s"',
                $this->getEndpoint(),
                $exception->getMessage(),
                $exception
            );
        }
    }

    /**
     * @param FacebookResponse $response
     *
     * @return AbstractModel
     */
    abstract protected function hydrate(FacebookResponse $response);

    /**
     * @return CacheItemInterface
     */
    protected function getCachedItem()
    {
        return new CacheItem();
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
}

/* EOF */
