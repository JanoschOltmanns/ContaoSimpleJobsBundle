<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules;


use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Entity\JobPosting;

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
				// Get the items
				$objItems = SimpleJobsPostingModel::findPublishedByPid($objOrganisations->id);

				if ($objItems !== null)
				{
					while ($objItems->next())
					{
					    // $objItems
                        $jobPosting = new JobPosting($objItems->current());
                        $arrPages[] = $jobPosting->getDetailLink(true);
					}
				}
			}
		}

		return $arrPages;
	}

	public function jobsReplaceInsertTags($strTag) {
        $arrSplit = explode('::', $strTag);
        if (isset($arrSplit[0]) && $arrSplit[0] === 'job') {
            $jobPostingModel = SimpleJobsPostingModel::findByIdOrAlias(\Input::get('items'));
            if ($jobPostingModel !== null) {
                return $jobPostingModel->{$arrSplit[1]};
            }
        }
        return false;
    }

}
