<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Entity;

use Contao\Controller;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel;

class Organisation {

    private string $name;
    private string $website;
    private ?string $logo = null;
    private string $teaser;

    private array $data;

    private function __construct(SimpleJobsOrganisationModel $organisationModel)
    {
        $this->data = $organisationModel->row();

        $this->name = Controller::replaceInsertTags($organisationModel->name);
        $this->data['name'] = $this->name;

        $this->website = Controller::replaceInsertTags(ampersand($organisationModel->sameAs));
        if (!preg_match('@^https?://@i', $this->website)) {
			$this->website = \Environment::get('base') . ltrim($this->website, '/');
		}
        $this->data['website'] = $this->website;

        $objFile = \FilesModel::findByUuid($organisationModel->logo);
        if ($objFile !== null) {
            $this->logo = \Environment::get('base') . $objFile->path;
            $this->data['logo'] = $this->logo;
        }

        $this->teaser = (string) $organisationModel->teaser;
    }

    /**
     * @return mixed|null
     */
    public function __get(string $key) {
        return $this->data[$key] ?? null;
    }

    /**
     * @param mixed $value
     */
    public function __set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function __isset(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public static function fromOrganisationModel(SimpleJobsOrganisationModel $organisationModel): Organisation
    {
        return new self($organisationModel);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function getTeaser(): string
    {
        return $this->teaser;
    }


}
