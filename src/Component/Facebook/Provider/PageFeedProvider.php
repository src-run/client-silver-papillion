<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Provider;

use AppBundle\Component\Facebook\Exception\FacebookException;
use AppBundle\Component\Facebook\Model\Feed\Page\PageFeed;
use Facebook\Authentication\AccessToken;
use Facebook\Authentication\OAuth2Client;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\FacebookApp;
use Facebook\FacebookClient;
use Facebook\FacebookResponse;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

/**
 * Category PageFeedProvider
 */
class PageFeedProvider implements FeedProviderInterface
{
    const REQUEST_ROOT = '/%PAGEID%';
    const REQUEST_FIELDS = [
        'feed' => [
            'id',
            'permalink_url',
            'created_time',
            'is_published',
            'admin_creator',
            'message',
            'caption',
            'description',
            'from',
            'icon',
            'link',
            'name',
            'picture',
            'source',
            'status_type',
            'type',
            'sharedposts',
            'story',
            'attachments',
            'comments',
            'reactions',
        ]
    ];

    /**
     * @var Facebook
     */
    private $fb;

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
     * @var Facebook
     */
    private $facebook;

    /**
     * @var FacebookApp
     */
    private $app;

    /**
     * @var FacebookClient
     */
    private $client;

    /**
     * @var OAuth2Client|null
     */
    private $oAuthClient;

    /**
     * @var AccessToken|null
     */
    private $accessToken;

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
        $this->pageId = $pageId;
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->graphVersion = $graphVersion;
    }

    public function setCache(AbstractAdapter $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return PageFeed
     */
    public function getFeed()
    {
        $response = $this->cache->getItem('facebook.sdk.response.feed');

        if (!$response->isHit()) {
            $response->set($this->getFacebookSdkEndpointRequest());
            $response->expiresAfter(new \DateInterval('P1D'));
            $this->cache->save($response);
        }

        return $this->hydrateResponse($response->get());
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return str_replace('%PAGEID%', $this->pageId, static::REQUEST_ROOT).$this->getFeedArgs();
    }

    /**
     * @param FacebookResponse $response
     *
     * @return PageFeed
     */
    private function hydrateResponse(FacebookResponse $response)
    {
        $feed = PageFeed::create($response->getDecodedBody(), $this->pageId);

        return $feed;
    }

    /**
     * @return string
     */
    private function getFeedArgs()
    {
        $argString = '?';

        foreach (static::REQUEST_FIELDS as $field => $args) {
            $argString .= sprintf('fields=%s{%s}&', $field, implode(',', $args));
        }

        return $argString;
    }

    /**
     * @return \Facebook\FacebookResponse
     *
     * @throws FacebookException
     */
    private function getFacebookSdkEndpointRequest()
    {
        $this->initializeFacebookSdkInstance();
        $response = null;

        try {
            $response = $this->facebook->get($this->getFeedUrl(), $this->app->getAccessToken());
        }
        catch(FacebookSDKException $exception) {
            throw FacebookException::create()
                ->setMessage('An error occured while requesting a Facebook API endpoint "%s"')
                ->with($this->getFeedUrl(), $exception);
        }

        if ($response !== null) {
            return $response;
        }

        throw FacebookException::create()
            ->setMessage('The Facebook API endpoint request returned a null result for endpoint "%s"')
            ->with($this->getFeedUrl());
    }

    /**
     * @return $this
     *
     * @throws FacebookException
     */
    private function initializeFacebookSdkInstance()
    {
        try {
            $facebook = (new Facebook([
                'app_id' => $this->appId,
                'app_secret' => $this->appSecret,
                'default_graph_version' => $this->graphVersion,
            ]));

            return $this->hydratePropertiesFromFacebookObjectInstance($facebook);
        }
        catch (FacebookSDKException $exception) {
            throw FacebookException::create()
                ->setMessage('Could not instantiate Facebook SDK object instance: %s.')
                ->with($exception->getMessage(), $exception);
        }
    }

    /**
     * @param Facebook $facebook
     *
     * @return $this
     *
     * @throws FacebookException
     */
    private function hydratePropertiesFromFacebookObjectInstance(Facebook $facebook)
    {
        $this->app = $app = $facebook->getApp();

        if ((int) $this->appId === (int) $app->getId()) {
            $this->facebook = $facebook;
            $this->client = $facebook->getClient();
            $this->oAuthClient = $facebook->getOAuth2Client();
            $this->accessToken = $app->getAccessToken();

            return $this;
        }

        throw FacebookException::create()
            ->setMessage('Locally configured app_id (%s), used to send the request, does not match the app_id (%s) that was returned in the response.')
            ->with($app->getId(), $this->appId);
    }
}

/* EOF */
