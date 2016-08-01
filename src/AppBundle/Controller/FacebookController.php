<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Category FacebookController.
 */
class FacebookController extends Controller
{
    /**
     * @param Request $request
     */
    public function loginCallbackAction(Request $request)
    {
        $fb = new Facebook([
            'app_id' => $this->getParameter('facebook_app_id'),
            'app_secret' => $this->getParameter('facebook_app_secret'),
            'default_graph_version' => $this->getParameter('facebook_graph_version'),
        ]);

        $t = $fb->getApp()->getAccessToken();
        dump($t);
        $page = $fb->get('/167313273354229?fields=posts.include_hidden(false){created_time,message,comments,likes{name},attachments,is_published,permalink_url,privacy,reactions}', $t);
        dump($page);

        return;

        $h = $fb->getRedirectLoginHelper();

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['manage_pages'];
//        $l = $h->getLoginUrl($this->generateUrl('app_fb_login_callback', [], UrlGeneratorInterface::ABSOLUTE_URL), $permissions);
//        $o = $h->getLogoutUrl($h->getAccessToken(), $this->generateUrl('app_fb_login_callback', [], UrlGeneratorInterface::ABSOLUTE_URL));
        dump($h);
//        dump($l);
//        dump($o);

        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
            dump($e);
        } catch(FacebookSDKException $e) {
            dump($e);
        }

        if (! isset($accessToken)) {
            dump($helper->getError());
        } else {
            dump($accessToken->getValue());

            $oAuth2Client = $fb->getOAuth2Client();
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);
            dump($tokenMetadata);

            // Validation (these will throw FacebookSDKException's when they fail)
            $tokenMetadata->validateAppId($this->getParameter('629342713896404'));
            // If you know the user ID this access token belongs to, you can validate it here
            //$tokenMetadata->validateUserId('123');
            $tokenMetadata->validateExpiration();

            if (! $accessToken->isLongLived()) {
                // Exchanges a short-lived access token for a long-lived one
                try {
                    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch (FacebookSDKException $e) {
                    dump($e);
                }

                var_dump($accessToken);
            }

            //$_SESSION['fb_access_token'] = (string) $accessToken;

            //dump($_SESSION);
        }
    }
}

/* EOF */
