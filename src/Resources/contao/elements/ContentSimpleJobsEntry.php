<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Elements;

use Contao\ContentElement;
use JanoschOltmanns\ContaoSimpleJobsBundle\Classes\StructuredJobPostingData;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Entity\JobPosting;

class ContentSimpleJobsEntry extends ContentElement {

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_simplejobsentry';

		/**
	 * Generate the content element
	 */
	protected function compile()
	{

        $postingTemplate = new \FrontendTemplate($this->simplejobs_postingtemplate);

        $jobPostingModel = SimpleJobsPostingModel::findByIdOrAlias($this->simplejobs_posting);

        if (null === $jobPostingModel)
		{
			return;
		}

        $jobPosting = new JobPosting($jobPostingModel);

        if ($this->simplejobs_addstructureddata) {
            $structuredData = new StructuredJobPostingData($jobPosting);
            $GLOBALS['TL_HEAD']['JOB-POSTING'] = '<script type="application/ld+json">' . $structuredData->getJson() . '</script>';
        }


        $postingTemplate->setData($jobPosting->getTemplateData());

        $this->Template->posting = $postingTemplate->parse();

    }
}
