<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Entity;

use Contao\Config;
use Contao\Controller;
use Contao\Date;
use Contao\StringUtil;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel;

class JobPosting {

    /** @var SimpleJobsPostingModel|null  */
    private $contaoModel = null;

    /** @var array  */
    private $locations = [];

    public function __construct(SimpleJobsPostingModel $contaoJobPostingModel)
    {
        $this->contaoModel = $contaoJobPostingModel;
    }

    public function getDescription() {

        return Controller::replaceInsertTags($this->contaoModel->description);

    }

    public function getKeywords() {

        return StringUtil::deserialize($this->contaoModel->keywords);

    }

    public function getTitle() {

        return Controller::replaceInsertTags($this->contaoModel->title);

    }

    public function getEmploymentTypes() {

        return StringUtil::deserialize($this->contaoModel->employmentType);

    }

    public function getPostingDate($dateFormat='') {

        if ('' == $dateFormat) {
            $dateFormat = Config::get('dateFormat');
        }

        return Date::parse($dateFormat, $this->contaoModel->datePosted);

    }

    public function getLocations() {

        $locations = $this->contaoModel->getRelated('locations');

        if (null !== $locations) {

            while ($locations->next()) {
                $this->locations[] = new Location($locations->current());
            }

        }

        return $this->locations;

    }

    public function getOrganisation() {

        $organisation = $this->contaoModel->getRelated('pid');

        /** @var Organisation $organisation */
        $organisation = $organisation->current();

        if (null !== $organisation) {

            return new Organisation($organisation);

        }

        return null;

    }

    public function getDetailLink($absolute=false) {

        $link = '';
        $organisation = $this->contaoModel->getRelated('pid');

        if (null !== $organisation) {

            if ( null !== ($page = \PageModel::findWithDetails($organisation->jumpTo)) )
            {
                if ($absolute) {
                    $link = $page->getAbsoluteUrl((\Config::get('useAutoItem') ? '/' : '/items/') . ($this->contaoModel->alias ?: $this->contaoModel->id));
                } else {
                    $link = $page->getFrontendUrl((\Config::get('useAutoItem') ? '/' : '/items/') . ($this->contaoModel->alias ?: $this->contaoModel->id));
                }
            }
        }

        return $link;

    }

    public function getTemplateData() {

        $templateData = [];
        $templateData['title'] = $this->contaoModel->title;
        $templateData['description'] = $this->contaoModel->description;
        $templateData['keywords'] = $this->getKeywords();
        $templateData['href'] = $this->getDetailLink();

        return $templateData;

    }

}
