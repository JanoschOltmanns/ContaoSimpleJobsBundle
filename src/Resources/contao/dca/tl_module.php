<?php

// Add palettes to tl_module
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'simplejobs_addCategoryFilter';

$GLOBALS['TL_DCA']['tl_module']['palettes']['simplejobslist'] = '{title_legend},name,headline,type;{config_legend},simplejobs_organisations,simplejobs_hardlimit,simplejobs_addCategoryFilter;{template_legend:hide},simplejobs_postingtemplate,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['palettes']['simplejobsreader'] = '{title_legend},name,headline,type;{config_legend},simplejobs_organisations,simplejobs_addstructureddata;{template_legend:hide},simplejobs_postingtemplate,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['palettes']['simplejobsfilter'] = '{title_legend},name,headline,type;{config_legend},simplejobs_organisations,simplejobs_filters,simplejobs_respectFilters,jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['simplejobs_addCategoryFilter'] = 'simplejobs_categories';

// Add fields to tl_module
$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_organisations'] = [
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'foreignKey'              => 'tl_simple_jobs_organisation.name',
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_addCategoryFilter'] = array(
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_categories'] = [
    'exclude'                 => true,
    'inputType'               => 'checkboxWizard',
    'foreignKey'              => 'tl_simple_jobs_category.title',
    'eval'                    => array('multiple'=>true, 'mandatory'=>true),
    'sql'                     => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_addstructureddata'] = [
    'exclude'                 => true,
    'filter'                  => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>''),
    'sql'                     => "char(1) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_postingtemplate'] = [
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_simplejobs', 'getJobPostingTemplates'),
    'eval'                    => array('mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_hardlimit'] = [
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'rgxp'=>'natural', 'tl_class'=>'w50'),
    'sql'                     => "smallint(5) unsigned NOT NULL default 0"
];
$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_filters'] = [
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'options'                 => array('location', 'employmentType', 'category', 'keyword', 'organisation'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module']['filterReferences'],
    'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'tl_class'=>'clr'),
    'sql'                     => "blob NULL",
];
$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_respectFilters'] = [
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''"
];

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 */
class tl_module_simplejobs extends \Contao\Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Return all job popstings templates as array
     */
    public function getJobPostingTemplates(): array
    {
        return self::getTemplateGroup('jobposting_');
    }
}
