<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules;

use Contao\BackendTemplate;
use Contao\Module;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Entity\JobPosting;

class ModuleSimpleJobsList extends Module {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_simplejobslist';


    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {

            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '##' . $GLOBALS['TL_LANG']['MOD']['simplejobslist'][0] . '##';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

		$this->simplejobs_organisations = \StringUtil::deserialize($this->simplejobs_organisations);

		// Return if there are no organisations
		if (empty($this->simplejobs_organisations) || !\is_array($this->simplejobs_organisations))
		{
			return '';
		}

        return parent::generate();
    }

    protected function compile()
    {

        $postings   = [];
        $arrOptions = [];

        // Maximum number of items
        if ($this->numberOfItems > 0) {
            $arrOptions['limit'] = $this->numberOfItems;
        }
        if (\Input::get('location')) {
            $arrOptions['location'] =\Input::get('location');
        }
        if (\Input::get('type')) {
            $arrOptions['employmentType'] =\Input::get('type');
        }

        $jobPostings = SimpleJobsPostingModel::findPublishedByPids($this->simplejobs_organisations);
        if (null !== $jobPostings) {
            while ($jobPostings->next()) {

                $jobPosting = new JobPosting($jobPostings->current());

                $postingTemplate = new \FrontendTemplate($this->simplejobs_postingtemplate);

                $postingTemplate->setData($jobPosting->getTemplateData());

                $postings[] = $postingTemplate->parse();
            }
        }

        $this->Template->postings = $postings;

    }

}
