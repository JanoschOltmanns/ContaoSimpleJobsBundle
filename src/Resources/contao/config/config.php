<?php

/**
 * Back end modules
 */

use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Elements\ContentSimpleJobsEntry;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsCategoryModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsLocationModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsPostingModel;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules\ModuleSimpleJobsFilter;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules\ModuleSimpleJobsList;
use JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Modules\ModuleSimpleJobsReader;

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
    'simplejobslist'   => ModuleSimpleJobsList::class,
    'simplejobsreader' => ModuleSimpleJobsReader::class,
    'simplejobsfilter' => ModuleSimpleJobsFilter::class,
];

// Content elements
$GLOBALS['TL_CTE']['includes']['simple_jobs_entry'] = ContentSimpleJobsEntry::class;

/**
 * Models
 */

$GLOBALS['TL_MODELS']['tl_simple_jobs_organisation'] = SimpleJobsOrganisationModel::class;
$GLOBALS['TL_MODELS']['tl_simple_jobs_location'] = SimpleJobsLocationModel::class;
$GLOBALS['TL_MODELS']['tl_simple_jobs_posting'] = SimpleJobsPostingModel::class;
$GLOBALS['TL_MODELS']['tl_simple_jobs_category'] = SimpleJobsCategoryModel::class;

/**
 * Register Hooks
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('JanoschOltmanns\\ContaoSimpleJobsBundle\\Contao\\Modules\\ModuleSimpleJobs', 'getSearchablePages');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('JanoschOltmanns\\ContaoSimpleJobsBundle\\Contao\\Modules\\ModuleSimpleJobs', 'jobsReplaceInsertTags');
