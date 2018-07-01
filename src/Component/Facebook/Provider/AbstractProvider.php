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
use SR\Exception\ExceptionInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;

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
     * @return AbstractModel
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
     * @return AbstractModel
     */
    public function get()
    {
        if ($this->hasCached()) {
            return $this->getCached();
        }

        return $this->refresh();
    }

    /**
     * @return AbstractModel|null
     *
     * @throws ExceptionInterface
     * @throws \Exception
     */
    protected function refresh()
    {
        if (null === $response = $this->getResponse()) {
            return null;
        }

        $item = $this->getCachedItem();
        $item->set($response);
        $item->expiresAfter(new \DateInterval($this->getCacheTtl()));
        $this->getCache()->save($item);

        return $response;
    }

    /**
     * @throws ExceptionInterface
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
     * @return CacheItem
     */
    protected function getCachedItem(): CacheItem
    {
        try {
            return $this->getCache()->getItem(
                sprintf('facebook.feed.%s.%s', hash('sha256', get_called_class()), hash('sha256', $this->getEndpoint()))
            );
        } catch (\Psr\Cache\InvalidArgumentException $exception) {
            throw new \InvalidArgumentException(sprintf(
                'Failed to create cache item for "%s:%s" facebook provider: %s',
                get_called_class(),
                $this->getEndpoint(),
                $exception->getMessage()
            ));
        }
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
