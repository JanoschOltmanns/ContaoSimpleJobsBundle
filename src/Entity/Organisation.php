<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Entity;

use Contao\Controller;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel;

class Organisation {

    /** @var SimpleJobsOrganisationModel|null  */
    private $contaoModel = null;

    private $name = '';

    private $website = '';

    private $logo = null;

    public function __construct(SimpleJobsOrganisationModel $contaoOrganisationModel)
    {
        $this->contaoModel = $contaoOrganisationModel;

        $this->name = Controller::replaceInsertTags($this->contaoModel->name);

        $this->website = Controller::replaceInsertTags(ampersand($this->contaoModel->sameAs));
        if (!preg_match('@^https?://@i', $this->website)) {
			$this->website = \Environment::get('base') . ltrim($this->website, '/');
		}

        $objFile = \FilesModel::findByUuid($this->contaoModel->logo);

        if ($objFile !== null)
        {
            $this->logo = \Environment::get('base') . $objFile->path;
        }
    }

    public function getName() {

        return $this->name;

    }

    public function getWebsite() {

        return $this->website;

    }

    public function getLogo() {
        return $this->logo;
    }


}
