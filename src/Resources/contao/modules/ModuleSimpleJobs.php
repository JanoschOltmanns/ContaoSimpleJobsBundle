<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules;


use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel;
use Symfony\Component\VarDumper\VarDumper;

class ModuleSimpleJobs extends \Contao\Frontend {

/**
	 * Add Jobpostings to the indexer
	 *
	 * @param array   $arrPages
	 * @param integer $intRoot
	 * @param boolean $blnIsSitemap
	 *
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0, $blnIsSitemap=false)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
		}

		$arrProcessed = array();
		$time = \Date::floorToMinute();

		// Get all categories
		$objOrganisations = SimpleJobsOrganisationModel::findAll();

		// Walk through each category
		if ($objOrganisations !== null)
		{
			while ($objOrganisations->next())
			{
				// Skip Organisations without target page
				if (!$objOrganisations->jumpTo)
				{
					continue;
				}

				// Skip Organisations outside the root nodes
				if (!empty($arrRoot) && !\in_array($objOrganisations->jumpTo, $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				if (!isset($arrProcessed[$objOrganisations->jumpTo]))
				{
					$objParent = \PageModel::findWithDetails($objOrganisations->jumpTo);

					// The target page does not exist
					if ($objParent === null)
					{
						continue;
					}

					// The target page has not been published (see #5520)
					if (!$objParent->published || ($objParent->start != '' && $objParent->start > $time) || ($objParent->stop != '' && $objParent->stop <= ($time + 60)))
					{
						continue;
					}

					if ($blnIsSitemap)
					{
						// The target page is protected (see #8416)
						if ($objParent->protected)
						{
							continue;
						}

						// The target page is exempt from the sitemap (see #6418)
						if ($objParent->sitemap == 'map_never')
						{
							continue;
						}
					}

					// Generate the URL
					$arrProcessed[$objOrganisations->jumpTo] = $objParent->getAbsoluteUrl(\Config::get('useAutoItem') ? '/%s' : '/items/%s');
				}

				$strUrl = $arrProcessed[$objOrganisations->jumpTo];

				// Get the items
				$objItems = SimpleJobsPostingModel::findPublishedByPid($objOrganisations->id);

				if ($objItems !== null)
				{
					while ($objItems->next())
					{
						$arrPages[] = sprintf(preg_replace('/%(?!s)/', '%%', $strUrl), ($objItems->alias ?: $objItems->id));
					}
				}
			}
		}

		return $arrPages;
	}

}
