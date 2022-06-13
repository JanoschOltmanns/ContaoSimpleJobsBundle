<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Entity;

use Contao\Config;
use Contao\Controller;
use Contao\Date;
use Contao\StringUtil;
use Contao\System;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel;

class JobPosting {

    /** @var SimpleJobsPostingModel|null  */
    private $contaoModel = null;

    /** @var array  */
    private $locations = [];

    public function __construct(SimpleJobsPostingModel $contaoJobPostingModel)
    {
        System::loadLanguageFile('tl_simple_jobs_posting');
        $this->contaoModel = $contaoJobPostingModel;
    }

    public function getDescription() {
        return Controller::replaceInsertTags($this->contaoModel->description);
    }

    public function getKeywords() {
        $keywords = StringUtil::deserialize($this->contaoModel->keywords);
        if (sizeof($keywords) === 1 && $keywords[0] === '') {
            return null;
        }
        return $keywords;
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

    /**
     * @param string $dateFormat
     * @return string|null
     */
    public function getValidThrough($dateFormat='') {
        if ('' == $dateFormat) {
            $dateFormat = Config::get('dateFormat');
        }
        if ($this->contaoModel->validThrough) {
            return Date::parse($dateFormat, $this->contaoModel->validThrough);
        }
        return null;
    }

    public function hasSalaryInformations() {
        return $this->contaoModel->addSalary;
    }

    public function hasSalaryRange() {
        return $this->hasSalaryInformations() && $this->contaoModel->salaryValueMax;
    }

    public function getSalaryValue() {
        $value = StringUtil::deserialize($this->contaoModel->salaryValue);
        return (float) isset($value['value']) ? $value['value'] : 0;
    }

    public function getSalaryCurrency() {
        $value = StringUtil::deserialize($this->contaoModel->salaryValue);
        return isset($value['unit']) ? $value['unit'] : '';
    }

    public function getSalaryValueMin() {
        $value = StringUtil::deserialize($this->contaoModel->salaryValue);
        return (float) isset($value['value']) ? $value['value'] : 0;
    }

    public function getSalaryValueMax() {
        return (float) $this->contaoModel->salaryValueMax;
    }

    public function getSalaryUnitText() {
        return $this->contaoModel->salaryUnit;
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

    public function getReadableEmploymentTypes() {
        $readableEmploymentTypes = [];
        foreach ($this->getEmploymentTypes() as $employmentType) {
            $readableEmploymentTypes[] = $GLOBALS['TL_LANG']['tl_simple_jobs_posting']['employmentTypeReference'][$employmentType];
        }
        return $readableEmploymentTypes;
    }

    public function getDetailLink($absolute=false) {
        $link = '';
        $page = null;

        $category = $this->contaoModel->getRelated('category');
        if (null !== $category && $category->jumpTo) {
            $page = \PageModel::findWithDetails($category->jumpTo);
        };

        if (null === $page) {
            $organisation = $this->contaoModel->getRelated('pid');
            if (null !== $organisation) {
                $page = \PageModel::findWithDetails($organisation->jumpTo);
            }
        }

        if (null !== $page) {
            if ($absolute) {
                $link = $page->getAbsoluteUrl((\Config::get('useAutoItem') ? '/' : '/items/') . ($this->contaoModel->alias ?: $this->contaoModel->id));
            } else {
                $link = $page->getFrontendUrl((\Config::get('useAutoItem') ? '/' : '/items/') . ($this->contaoModel->alias ?: $this->contaoModel->id));
            }
        }

        return $link;
    }

    public function getTemplateData(bool $withContaoModel = false) {
        $templateData = [];
        $templateData['id'] = $this->contaoModel->id;
        $templateData['title'] = $this->contaoModel->title;
        $templateData['description'] = $this->contaoModel->description;
        $templateData['teaser'] = $this->contaoModel->teaser;
        $templateData['singleSRC'] = $this->contaoModel->singleSRC;
        $templateData['keywords'] = $this->getKeywords();
        $templateData['locations'] = $this->getLocations();
        $templateData['href'] = $this->getDetailLink();
        $templateData['datePosted']     = $this->getPostingDate();
        $templateData['employmentTypes'] = $this->getReadableEmploymentTypes();
        $organisation = $this->getOrganisation();
        if (null !== $organisation) {
            $templateData['organisation'] = [
                'name' => $organisation->getName(),
                'website' => $organisation->getWebsite(),
                'logo' => $organisation->getLogo(),
                'teaser' => $organisation->getTeaser()
            ];
        }

        if ($withContaoModel) {
            $templateData['contaoModel'] = $this->contaoModel;
        }

        return $templateData;
    }

}
