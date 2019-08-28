<?php

// Add palettes to tl_content
$GLOBALS['TL_DCA']['tl_content']['palettes']['simple_jobs_entry'] = '{type_legend},type,headline;{include_legend},simplejobs_posting,simplejobs_addstructureddata;{template_legend:hide},simplejobs_postingtemplate,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';



// Add fields to tl_content
$GLOBALS['TL_DCA']['tl_content']['fields']['simplejobs_posting'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['simplejobs_posting'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'foreignKey'              => 'tl_simple_jobs_posting.title',
	'eval'                    => array('includeBlankOption'=>true, 'mandatory'=>true),
	'sql'                     => "int(10) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['simplejobs_addstructureddata'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['simplejobs_addstructureddata'],
    'exclude'                 => true,
    'filter'                  => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>''),
    'sql'                     => "char(1) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_content']['fields']['simplejobs_postingtemplate'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['simplejobs_postingtemplate'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_content_simplejobs', 'getJobPostingTemplates'),
    'eval'                    => array('mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
];

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 */
class tl_content_simplejobs extends \Backend
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
