<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules;

use Contao\BackendTemplate;
use Contao\Input;
use Contao\Module;
use Contao\StringUtil;
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
        $postings = [];
        $filters = [];

        $hasFilter = false;
        $arrOptions = [];

        // Maximum number of items
        if ($this->simplejobs_hardlimit > 0) {
            $arrOptions['limit'] = $this->simplejobs_hardlimit;
        }
        if (Input::get('location')) {
            $hasFilter = true;
            $filters['location'] = explode(',', Input::get('location'));
        }
        if (Input::get('employmentType')) {
            $hasFilter = true;
            $filters['employmentType'] = Input::get('employmentType');
        }
        if (Input::get('category')) {
            $hasFilter = true;
            $filters['category'] = Input::get('category');
        }
        if (Input::get('keyword')) {
            $hasFilter = true;
            $filters['keyword'] = Input::get('keyword');
        }
        if (Input::get('organisation')) {
            $hasFilter = true;
            $filters['organisation'] = Input::get('organisation');
        }

        if ($this->simplejobs_addCategoryFilter) {
            $categories = StringUtil::deserialize($this->simplejobs_categories, true);
            $jobPostings = SimpleJobsPostingModel::findPublishedByPidsAndCategories($this->simplejobs_organisations, $categories, $filters, $arrOptions);
        } else {
            $jobPostings = SimpleJobsPostingModel::findPublishedByPids($this->simplejobs_organisations, $filters, $arrOptions);
        }

        if (null !== $jobPostings) {
            while ($jobPostings->next()) {

                $jobPosting = new JobPosting($jobPostings->current());

                $postingTemplate = new \FrontendTemplate($this->simplejobs_postingtemplate);

                $postingTemplate->setData($jobPosting->getTemplateData());

                $postings[] = $postingTemplate->parse();
            }
        }

        $this->Template->hasFilter = $hasFilter;
        $this->Template->postings = $postings;

    }

}
