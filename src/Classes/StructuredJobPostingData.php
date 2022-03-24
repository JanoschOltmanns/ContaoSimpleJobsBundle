<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Classes;

use JanoschOltmanns\ContaoSimpleJobsBundle\Entity\JobPosting;
use JanoschOltmanns\ContaoSimpleJobsBundle\Entity\Location;

class StructuredJobPostingData {

    /** @var JobPosting|null  */
    private $jobPosting = null;

    public function __construct(JobPosting $jobPosting)
    {
        $this->jobPosting = $jobPosting;
    }

    public function getJson() {
        $jsonData = [
            '@context' => 'http://schema.org',
            '@type' => 'JobPosting',
            'datePosted' => $this->jobPosting->getPostingDate('Y-m-d'),
            'description' => $this->jobPosting->getDescription(),
            'employmentType' => $this->jobPosting->getEmploymentTypes(),
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $this->jobPosting->getOrganisation()->getName(),
                'sameAs' => $this->jobPosting->getOrganisation()->getWebsite(),
                'logo' => $this->jobPosting->getOrganisation()->getLogo()
            ],
            'jobLocation' => [],
            'title' => $this->jobPosting->getTitle()
        ];

        if (null !== $validThrough = $this->jobPosting->getValidThrough('Y-m-d')) {
            $jsonData['validThrough'] = $validThrough;
        }

        if ($this->jobPosting->hasSalaryInformations()) {
            if ($this->jobPosting->hasSalaryRange()) {
                $jsonData['baseSalary'] = [
                    '@type' => 'MonetaryAmount',
                    'currency' => $this->jobPosting->getSalaryCurrency(),
                    'value' => [
                        '@type' => 'QuantitativeValue',
                        'minValue' => $this->jobPosting->getSalaryValueMin(),
                        'maxValue' => $this->jobPosting->getSalaryValueMax(),
                        'unitText' => $this->jobPosting->getSalaryUnitText()
                    ]
                ];
            } else {
                $jsonData['baseSalary'] = [
                    '@type' => 'MonetaryAmount',
                    'currency' => $this->jobPosting->getSalaryCurrency(),
                    'value' => [
                        '@type' => 'QuantitativeValue',
                        'value' => $this->jobPosting->getSalaryValue(),
                        'unitText' => $this->jobPosting->getSalaryUnitText()
                    ]
                ];
            }
        }

        $locations = $this->jobPosting->getLocations();

        foreach ($locations as $location) {
            /** @var Location $location */
            $jsonData['jobLocation'][] = [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $location->getStreet(),
                    'addressLocality' => $location->getCity(),
                    'addressRegion' => $location->getRegion(),
                    'postalCode' => $location->getZipcode(),
                    'addressCountry' => $location->getCountry()
                ]
            ];
        }

        return json_encode($jsonData);
    }

}
