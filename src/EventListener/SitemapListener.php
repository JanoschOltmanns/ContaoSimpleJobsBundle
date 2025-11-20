<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\EventListener;

use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\SitemapEvent;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Entity\JobPosting;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(ContaoCoreEvents::SITEMAP)]
class SitemapListener
{
    public function __invoke(SitemapEvent $event): void
    {
        $sitemap = $event->getDocument();
        $urlSet = $sitemap->childNodes->item(0);

        $objOrganisations = SimpleJobsOrganisationModel::findAll();

        if ($objOrganisations !== null) {
            while ($objOrganisations->next()) {
                $objItems = SimpleJobsPostingModel::findPublishedByPid($objOrganisations->id);

                if ($objItems !== null) {
                    while ($objItems->next()) {
                        $jobPosting = new JobPosting($objItems->current());

                        $loc = $sitemap->createElement('loc');
                        $loc->appendChild($sitemap->createTextNode($jobPosting->getDetailLink(true)));

                        $urlEl = $sitemap->createElement('url');
                        $urlEl->appendChild($loc);
                        $urlSet->appendChild($urlEl);
                    }
                }
            }
        }
    }
}