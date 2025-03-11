<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Entity;

use Contao\Environment;
use Contao\FilesModel;
use Contao\StringUtil;
use Contao\System;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel;

class Organisation {

    /** @var SimpleJobsOrganisationModel|null  */
    private $contaoModel = null;

    private $name = '';
    private $website = '';
    private $logo = null;
    private $teaser = '';

    public function __construct(SimpleJobsOrganisationModel $contaoOrganisationModel)
    {
        $this->contaoModel = $contaoOrganisationModel;

        $insertTagParser = System::getContainer()->get('contao.insert_tag.parser');

        $this->name = $insertTagParser->replace($this->contaoModel->name);

        $this->website = $insertTagParser->replace(StringUtil::ampersand($this->contaoModel->sameAs));
        if (!preg_match('@^https?://@i', $this->website)) {
			$this->website = Environment::get('base') . ltrim($this->website, '/');
		}

        $objFile = FilesModel::findByUuid($this->contaoModel->logo);

        if ($objFile !== null)
        {
            $this->logo = Environment::get('base') . $objFile->path;
        }

        $this->teaser = (string) $this->contaoModel->teaser;
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

    public function getTeaser() {
        return $this->teaser;
    }


}
