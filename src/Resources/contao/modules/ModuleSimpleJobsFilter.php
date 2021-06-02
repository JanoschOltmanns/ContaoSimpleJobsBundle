<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules;

use Contao\BackendTemplate;
use Contao\Module;
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

            $objTemplate->wildcard = '##' . $GLOBALS['TL_LANG']['MOD']['simplejobsfilter'][0] . '##';
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
      $arrOptions   = [];
      $arrLocations = [];
      $arrEmploymentTypes        = [];
      $arrOrganisationsLocations = [];
      $arrOrganisationsTypes     = [];

      $objOrganisationsLocations = SimpleJobsPostingModel::findLocationsByPids($this->simplejobs_organisations, $arrOptions);
      $objAllLocations           = \Database::getInstance()->query("SELECT id, addressLocality FROM tl_simple_jobs_location WHERE published = 1");

      while($objAllLocations->next()) {
        $arrAllLocations[$objAllLocations->id] = $objAllLocations->addressLocality;
      }
      while($objOrganisationsLocations->next()) {
        $arrOrganisationsLocations = array_merge($arrOrganisationsLocations, \StringUtil::deserialize($objOrganisationsLocations->locations));
        $arrOrganisationsTypes     = array_merge($arrOrganisationsLocations, \StringUtil::deserialize($objOrganisationsLocations->employmentType));
      }

      foreach ($arrOrganisationsLocations as $value) {
        $arrLocations[$value] = $arrAllLocations[$value];
      }
      asort($arrLocations);

      $this->Template->locations = $arrLocations;

      \Contao\System::loadLanguageFile('tl_simple_jobs_posting');
      foreach ($arrOrganisationsTypes as $value) {
        if($GLOBALS['TL_LANG']['tl_simple_jobs_posting']['employmentTypeReference'][$value]) {
          $arrEmploymentTypes[$value] = $GLOBALS['TL_LANG']['tl_simple_jobs_posting']['employmentTypeReference'][$value];
        }
      }

      $this->Template->employmentTypes = $arrEmploymentTypes;


    }

}
