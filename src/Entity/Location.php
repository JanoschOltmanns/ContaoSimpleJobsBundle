<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Entity;

use Contao\System;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsLocationModel;

class Location {

    /** @var SimpleJobsLocationModel|null  */
    private $contaoModel;

    /** @var string  */
    private $street;

    /** @var string  */
    private $zipcode;

    /** @var string  */
    private $city;

    /** @var string  */
    private $region;

    /** @var string  */
    private $country;


    public function __construct(SimpleJobsLocationModel $contaoLocationnModel)
    {
        $this->contaoModel = $contaoLocationnModel;

        $insertTagParser = System::getContainer()->get('contao.insert_tag.parser');

        $this->street = $insertTagParser->replace($this->contaoModel->streetAddress);

        $this->zipcode = $insertTagParser->replace($this->contaoModel->postalCode);

        $this->city = $insertTagParser->replace($this->contaoModel->addressLocality);

        $this->region = $insertTagParser->replace($this->contaoModel->addressRegion);

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
