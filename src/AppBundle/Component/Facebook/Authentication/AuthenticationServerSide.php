<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Authentication;

use AppBundle\Component\Facebook\Factory\FacebookFactory;
use Facebook\Facebook;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

/**
 * Category AuthenticationServerSide
 */
class AuthenticationServerSide implements AuthenticationInterface
{
    /**
     * @var string
     */
    const OAUTH_ENDPOINT = '/oauth/access_token';

    /**
     * @var int
     */
    private $pageId;

    /**
     * @var int
     */
    private $appId;

    /**
     * @var string
     */
    private $appSecret;

    /**
     * @var string
     */
    private $graphVersion;

    /**
     * @var AbstractAdapter
     */
    private $cache;

    /**
     * @param int    $pageId
     * @param int    $appId
     * @param string $appSecret
     * @param string $graphVersion
     */
    public function __construct($pageId, $appId, $appSecret, $graphVersion = 'v2.6')
    {
        $this->setPageId($pageId);
        $this->setAppId($appId);
        $this->setAppSecret($appSecret);
        $this->setGraphVersion($graphVersion);
    }

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @param int $pageId
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * @return int
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param int $appId
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }

    /**
     * @return string
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }

    /**
     * @param string $appSecret
     */
    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;
    }

    /**
     * @return string
     */
    public function getGraphVersion()
    {
        return $this->graphVersion;
    }

    /**
     * @param string $graphVersion
     */
    public function setGraphVersion($graphVersion)
    {
        $this->graphVersion = $graphVersion;
    }

    /**
     * @param AbstractAdapter $cache
     */
    public function setCache(AbstractAdapter $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return TokenInterface
     */
    public function getAuth()
    {
        try {
            $facebook = FacebookFactory::create($this->getAppId(), $this->getAppSecret(), $this->getGraphVersion());

            return Token::create($this->getTokenRequest($facebook));
        }
        catch (\Exception $e) {
            return Token::create(null);
        }
    }

    /**
     * @param Facebook $facebook
     *
     * @return string
     */
    private function getTokenRequest(Facebook $facebook)
    {
        $request = $facebook->get($this->getTokenEndpoint(), $facebook->getApp()->getAccessToken());
        $graph = $request->getGraphNode();

        return $graph->getField('access_token');
    }

    /**
     * @return string
     */
    private function getTokenEndpoint()
    {
        $query = http_build_query([
            'client_id' => $this->appId,
            'client_secret' => $this->appSecret,
            'grant_type' => 'client_credentials',
        ]);

        return sprintf('%s?%s', self::OAUTH_ENDPOINT, $query);
    }
}

/* EOF */
