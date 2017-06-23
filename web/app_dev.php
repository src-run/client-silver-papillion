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
use Symfony\Component\Debug\Debug;

/** @var Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';

Debug::enable();

/** @var AppKernel $kernel */
$kernel = new AppKernel('dev', true);

/** @var Request $request */
$request = Request::createFromGlobals();

/** @var \Symfony\Component\HttpFoundation\Response $response */
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

/* EOF */
