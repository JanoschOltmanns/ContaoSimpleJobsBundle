<?php

/*
 * This file is part of contao article classes bundle.
 *
 * (c) Janosch Oltmanns
 *
 * @license LGPL-3.0-or-later
 */

namespace JanoschOltmanns\ContaoSimpleJobsBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use JanoschOltmanns\ContaoSimpleJobsBundle\JanoschOltmannsContaoSimpleJobsBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(JanoschOltmannsContaoSimpleJobsBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
