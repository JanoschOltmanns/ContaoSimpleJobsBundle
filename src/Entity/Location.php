<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Entity;

use Contao\Controller;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsLocationModel;

class Location {

    /** @var SimpleJobsOrganisationModel|null  */
    private $contaoModel = null;

    /** @var string  */
    private $street = '';

    /** @var string  */
    private $zipcode = '';

    /** @var string  */
    private $city = '';

    /** @var string  */
    private $region = '';

    /** @var string  */
    private $country = '';


    public function __construct(SimpleJobsLocationModel $contaoLocationnModel)
    {
        $this->contaoModel = $contaoLocationnModel;

        $this->street = Controller::replaceInsertTags($this->contaoModel->streetAddress);

        $this->zipcode = Controller::replaceInsertTags($this->contaoModel->postalCode);

        $this->city = Controller::replaceInsertTags($this->contaoModel->addressLocality);

        $this->region = Controller::replaceInsertTags($this->contaoModel->addressRegion);

        $this->country = strtoupper($this->contaoModel->addressCountry);

    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }




}
