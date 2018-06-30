<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Component\Location\GeoIpLookup;
use AppBundle\Manager\ConfigurationManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserLocationExtension extends AbstractExtension
{
    /**
     * @var GeoIpLookup
     */
    private $lookup;

    /**
     * @var ConfigurationManager
     */
    private $config;

    /**
     * @param GeoIpLookup          $lookup
     * @param ConfigurationManager $configuration
     */
    public function __construct(GeoIpLookup $lookup, ConfigurationManager $configuration)
    {
        $this->lookup = $lookup;
        $this->config = $configuration;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('client_is_taxable', function () {
                return in_array(
                    $this->lookupClientRegionName(),
                    explode(',', $this->config->get('taxable.states')->getValue())
                ) || $this->lookupClientRegionName() === null;
            }),
            new TwigFunction('client_region_name', function () {
                return $this->lookupClientRegionName();
            }),
        ];
    }

    /**
     * @return mixed
     */
    private function lookupClientRegionName()
    {
        return $this->lookup->lookupUsingClientIp()->getRegionName();
    }
}
