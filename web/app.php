<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Symfony\Component\HttpFoundation\Request;

/** @var Composer\Autoload\ClassLoader */
$loader = require __DIR__.'/../app/autoload.php';

include_once __DIR__.'/../var/bootstrap.php.cache';

/** @var AppKernel $kernel */
$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();

/** @var AppCache $kernel */
$kernel = new AppCache($kernel);

Request::enableHttpMethodParameterOverride();

/** @var Request $request */
$request = Request::createFromGlobals();

/** @var \Symfony\Component\HttpFoundation\Response $response */
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

/* EOF */
