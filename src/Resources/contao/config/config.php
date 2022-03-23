<?php

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['simple_jobs'] = [
    'tables' => [
        'tl_simple_jobs_organisation',
        'tl_simple_jobs_location',
        'tl_simple_jobs_posting',
        'tl_simple_jobs_category'
    ],
];


/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['simple_jobs'] = [
    'simplejobslist'   => \JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules\ModuleSimpleJobsList::class,
    'simplejobsreader' => \JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules\ModuleSimpleJobsReader::class,
];

// Content elements
$GLOBALS['TL_CTE']['includes']['simple_jobs_entry'] = \JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Elements\ContentSimpleJobsEntry::class;

/**
 * Models
 */

$GLOBALS['TL_MODELS']['tl_simple_jobs_organisation'] = \JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel::class;
$GLOBALS['TL_MODELS']['tl_simple_jobs_location'] = \JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsLocationModel::class;
$GLOBALS['TL_MODELS']['tl_simple_jobs_posting'] = \JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel::class;
$GLOBALS['TL_MODELS']['tl_simple_jobs_category'] = \JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsCategoryModel::class;

/**
 * Register Hooks
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('JanoschOltmanns\\ContaoSimpleJobsBundle\\Contao\\Modules\\ModuleSimpleJobs', 'getSearchablePages');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('JanoschOltmanns\\ContaoSimpleJobsBundle\\Contao\\Modules\\ModuleSimpleJobs', 'jobsReplaceInsertTags');
