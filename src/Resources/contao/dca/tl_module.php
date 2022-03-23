<?php

// Add palettes to tl_module
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'simplejobs_addCategoryFilter';
$GLOBALS['TL_DCA']['tl_module']['palettes']['simplejobslist']   = '{title_legend},name,headline,type;{config_legend},simplejobs_organisations,numberOfItems,simplejobs_addCategoryFilter;{template_legend:hide},simplejobs_postingtemplate,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['simplejobsreader'] = '{title_legend},name,headline,type;{config_legend},simplejobs_organisations,simplejobs_addstructureddata;{template_legend:hide},simplejobs_postingtemplate,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['simplejobsfilter'] = '{title_legend},name,headline,type;{config_legend},simplejobs_organisations;{template_legend:hide},simplejobs_filtertemplate,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['simplejobs_addCategoryFilter'] = 'simplejobs_categories';

// Add fields to tl_module
$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_organisations'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['simplejobs_organisations'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'foreignKey'              => 'tl_simple_jobs_organisation.name',
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_addCategoryFilter'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['simplejobs_addCategoryFilter'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_categories'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['simplejobs_categories'],
    'exclude'                 => true,
    'inputType'               => 'checkboxWizard',
    'foreignKey'              => 'tl_simple_jobs_category.title',
    'eval'                    => array('multiple'=>true, 'mandatory'=>true),
    'sql'                     => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_addstructureddata'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['simplejobs_addstructureddata'],
    'exclude'                 => true,
    'filter'                  => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>''),
    'sql'                     => "char(1) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_module']['fields']['simplejobs_postingtemplate'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['simplejobs_postingtemplate'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_simplejobs', 'getJobPostingTemplates'),
    'eval'                    => array('mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
];

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 */
class tl_module_simplejobs extends \Backend
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
     *
     * @return array
     */
    public function getJobPostingTemplates()
    {
        return $this->getTemplateGroup('jobposting_');
    }
}
