<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules;

use Contao\BackendTemplate;
use Contao\Environment;
use Contao\FormSelectMenu;
use Contao\Input;
use Contao\Module;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsCategoryModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsLocationModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel;

class ModuleSimpleJobsFilter extends Module {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_simplejobsfilter';


    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {

            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '##' . $GLOBALS['TL_LANG']['FMD']['simplejobsfilter'][0] . '##';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

		$this->simplejobs_organisations = StringUtil::deserialize($this->simplejobs_organisations);
		// Return if there are no organisations
		if (empty($this->simplejobs_organisations) || !\is_array($this->simplejobs_organisations)) {
			return '';
		}

        $this->simplejobs_filters = StringUtil::deserialize($this->simplejobs_filters);
        // Return if there are no filters
        if (empty($this->simplejobs_filters) || !\is_array($this->simplejobs_filters)) {
            return '';
        }

        return parent::generate();
    }

    protected function compile()
    {

        $arrOptions = [];
        if ($this->simplejobs_respectFilters) {
            // do stuff
        }

        $jobPostings = SimpleJobsPostingModel::findPublishedByPids($this->simplejobs_organisations, [], $arrOptions);

        // Gather the data
        $locationsIds = [];
        $needsLocationFilter = in_array('location', $this->simplejobs_filters, true);

        $employmentTypes = [];
        $needsEmploymentTypeFilter = in_array('employmentType', $this->simplejobs_filters, true);

        $categorieIds = [];
        $needsCategoryFilter = in_array('category', $this->simplejobs_filters, true);

        $keywords = [];
        $needsKeywordFilter = in_array('keyword', $this->simplejobs_filters, true);

        $organisationIds = [];
        $needsOrganisationFilter = in_array('organisation', $this->simplejobs_filters, true);

        while ($jobPostings->next()) {
            if ($needsLocationFilter) {
                $jobpostingLocations = StringUtil::deserialize($jobPostings->locations);
                if (is_array($jobpostingLocations)) {
                    foreach ($jobpostingLocations as $location) {
                        if (!in_array($location, $locationsIds, true)) {
                            $locationsIds[] = $location;
                        }
                    }
                }
            }
            if ($needsEmploymentTypeFilter) {
                $jobpostingEmploymentTypes = StringUtil::deserialize($jobPostings->employmentType);
                if (is_array($jobpostingEmploymentTypes)) {
                    foreach ($jobpostingEmploymentTypes as $employmentType) {
                        if (!in_array($employmentType, $employmentTypes, true)) {
                            $employmentTypes[] = $employmentType;
                        }
                    }
                }
            }
            if ($needsCategoryFilter && $jobPostings->category) {
                if (!in_array($jobPostings->category, $categorieIds, true)) {
                    $categorieIds[] = $jobPostings->category;
                }
            }
            if ($needsKeywordFilter) {
                $jobpostingKeywords = StringUtil::deserialize($jobPostings->keywords);
                if (is_array($jobpostingKeywords)) {
                    foreach ($jobpostingKeywords as $keyword) {
                        if ($keyword !== '' && !in_array($keyword, $keywords, true)) {
                            $keywords[] = $keyword;
                        }
                    }
                }
            }
            if ($needsOrganisationFilter && !in_array($jobPostings->pid, $organisationIds, true)) {
                $organisationIds[] = $jobPostings->pid;
            }
        }


        $templateFormFields = [];

        // Build the options
        if ($needsLocationFilter && count($locationsIds) > 0) {
            $locations = SimpleJobsLocationModel::findByIds($locationsIds);
            $locationOptions = [];
            if (null !== $locations) {
                while ($locations->next()) {
                    if (isset($locationOptions[$locations->addressLocality])) {
                        $id = $locationOptions[$locations->addressLocality] . ',' . $locations->id;
                    } else {
                        $id = $locations->id;
                    }
                    $locationOptions[$locations->addressLocality] = $id;
                }
            }
            $templateFormFields['location'] = $this->generateSelectField('location', $locationOptions);
        }

        if ($needsEmploymentTypeFilter && count($employmentTypes) > 0) {
            System::loadLanguageFile('tl_simple_jobs_posting');
            $employmentTypeOptions = [];
            foreach ($employmentTypes as $employmentType) {
                if ($GLOBALS['TL_LANG']['tl_simple_jobs_posting']['employmentTypeReference'][$employmentType]) {
                    $employmentTypeOptions[$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['employmentTypeReference'][$employmentType]] = $employmentType;
                }
            }
            $templateFormFields['employmentType'] = $this->generateSelectField('employmentType', $employmentTypeOptions);
        }

        if ($needsCategoryFilter && count($categorieIds) > 0) {
            $categories = SimpleJobsCategoryModel::findByIds($categorieIds);
            $categoryOptions = [];
            if (null !== $categories) {
                while ($categories->next()) {
                    $categoryOptions[$categories->title] = $categories->id;
                }
            }
            $templateFormFields['category'] = $this->generateSelectField('category', $categoryOptions);
        }

        if ($needsKeywordFilter && count($keywords) > 0) {
            $keywordOptions = [];
            asort($keywords);
            foreach ($keywords as $keyword) {
                $keywordOptions[$keyword] = $keyword;
            }
            $templateFormFields['keyword'] = $this->generateSelectField('keyword', $keywordOptions);
        }

        if ($needsOrganisationFilter && count($organisationIds) > 0) {
            $organisations = SimpleJobsOrganisationModel::findByIds($organisationIds);
            $organisationOptions = [];
            if (null !== $organisations) {
                while ($organisations->next()) {
                    $organisationOptions[$organisations->name] = $organisations->id;
                }
            }
            $templateFormFields['organisation'] = $this->generateSelectField('organisation', $organisationOptions);
        }

        if (($objJumpTo = $this->objModel->getRelated('jumpTo')) instanceof PageModel) {
            /** @var  PageModel $objJumpTo */
            $this->Template->formAction = $objJumpTo->getFrontendUrl();
        } else {
            $this->Template->formAction = '';
        }

        $this->Template->formId = 'form-filter--' . $this->id;

        $this->Template->formFields = $templateFormFields;

        [$pageUrl] = explode('?', Environment::get('request'), 2);

        $this->Template->resetHref = $pageUrl;
    }

    private function generateSelectField(string $name, array $options) {
        $fieldAttributes = [
            'id' => $name,
            'name' => $name,
            'label' => $GLOBALS['TL_LANG']['MSC']['simple_jobs_filterReference'][$name] ?? $name,
            'value' => Input::get($name)
        ];
        $select = new FormSelectMenu($fieldAttributes);
        $arrOptions = [
            [
                'value' => '',
                'label' => $GLOBALS['TL_LANG']['MSC']['simple_jobs_filterAllReference'][$name] ?? '-'
            ]
        ];
        foreach ($options as $label=>$value) {
            $arrOptions[] = [
                'value' => $value,
                'label' => $label
            ];
        }
        $select->addAttributes(['arrOptions' => $arrOptions]);
        return $select->parse();
    }

}
