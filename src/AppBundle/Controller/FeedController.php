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

use AppBundle\Component\Facebook\Model\Feed\Feed;
use AppBundle\Component\Facebook\Model\Feed\FeedPost;
use AppBundle\Component\Facebook\Model\Feed\MediaVideo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Category FeedController.
 */
class FeedController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:feed:index.html.twig', [
            '_c' => static::class,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fragmentAction()
    {
        $this->get('session')->save();

        return $this->render('AppBundle:feed:feed.html.twig', [
            '_c' => static::class,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fragmentPhotosAction()
    {
        $this->get('session')->save();

        return $this->render('AppBundle:feed:photos.html.twig', [
            '_c' => static::class,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fragmentVideoAction($post, $video)
    {
        $this->get('session')->save();

        return $this->render('AppBundle:fragment:feed-attachment-video.html.twig', [
            '_c' => static::class,
            'video' => $this->findVideo($this->get('app.fb.provider.page_feed')->get(), $post, $video),
        ]);
    }

    /**
     * @param Feed   $feed
     * @param string $postId
     * @param string $videoId
     *
     * @return MediaVideo
     */
    protected function findVideo(Feed $feed, $postId, $videoId)
    {
        $post = array_filter($feed->getPosts(), function (FeedPost $p) use ($postId) {
            return $p->getId() === $postId;
        });

        $video = array_filter(array_pop($post)->getAttachments(), function (MediaVideo $v) use ($videoId) {
            return $v->getId() === $videoId;
        });

        return array_pop($video);
    }
}

/* EOF */
