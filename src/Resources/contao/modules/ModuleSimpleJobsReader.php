<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules;

use Contao\BackendTemplate;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\Environment;
use Contao\Module;
use JanoschOltmanns\ContaoSimpleJobsBundle\Classes\StructuredJobPostingData;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Entity\JobPosting;

class ModuleSimpleJobsReader extends Module {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_simplejobsreader';


    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {

            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '##' . $GLOBALS['TL_LANG']['MOD']['simplejobsreader'][0] . '##';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && \Config::get('useAutoItem') && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

		// Return an empty string if "items" is not set (to combine list and reader on same page)
		if (!\Input::get('items'))
		{
			return '';
		}

        return parent::generate();
    }

    protected function compile()
    {

        /** @var \PageModel $objPage */
		global $objPage;

        $postingTemplate = new \FrontendTemplate($this->simplejobs_postingtemplate);

        $jobPostingModel = SimpleJobsPostingModel::findPublishedByIdOrAlias(\Input::get('items'));

        if (null === $jobPostingModel)
		{
			throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
		}

        $jobPosting = new JobPosting($jobPostingModel);

        if ($this->simplejobs_addstructureddata) {
            $structuredData = new StructuredJobPostingData($jobPosting);
            $GLOBALS['TL_HEAD']['JOB-POSTING'] = '<script type="application/ld+json">' . $structuredData->getJson() . '</script>';
        }

        if ($jobPosting->getTitle() != '')
		{
			$objPage->pageTitle = strip_tags(\StringUtil::stripInsertTags($jobPosting->getTitle()));
		}

        $postingTemplate->setData($jobPosting->getTemplateData());

        $this->Template->posting = $postingTemplate->parse();

    }

}
