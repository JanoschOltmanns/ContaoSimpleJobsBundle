<?php


$GLOBALS['TL_DCA']['tl_simple_jobs_organisation'] = array
(

    // Config
    'config' => array
    (
        'dataContainer' => 'Table',
        'ctable' => array('tl_simple_jobs_location', 'tl_simple_jobs_posting'),
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode' => 1,
            'fields' => array('name'),
            'flag' => 1,
            'panelLayout' => 'search,limit'
        ),
        'label' => array
        (
            'fields' => array('name'),
            'format' => '%s'
        ),
        'global_operations' => array
        (
            'categories' => array
            (
                'href'                => 'table=tl_simple_jobs_category',
                'class'               => 'header_sync',
                'attributes'          => 'onclick="Backend.getScrollOffset()"',
            ),
            'all' => array
            (
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['edit'],
                'href' => 'table=tl_simple_jobs_posting',
                'icon' => 'edit.svg'
            ),
            'locations' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['locations'],
                'href' => 'table=tl_simple_jobs_location',
                'icon' => 'mgroup.svg'
            ),
            'editheader' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['editheader'],
                'href' => 'act=edit',
                'icon' => 'header.svg',
            ),
            'copy' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ),
            'delete' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['show'],
                'href' => 'act=show',
                'icon' => 'show.svg'
            ),
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default' => '{title_legend},name,sameAs,logo,teaser;{settings_legend},jumpTo;{publish_legend},published'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'name' => [
			'label'         => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['name'],
			'exclude'       => true,
			'search'        => true,
			'inputType'     => 'text',
			'eval'          => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
			'sql'           => "varchar(255) NOT NULL default ''"
        ],
        'sameAs' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['sameAs'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'dcaPicker'=>true, 'addWizardClass'=>false, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'logo' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['logo'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'extensions'=>Config::get('validImageTypes'), 'mandatory'=>true, 'tl_class'=>'clr'),
			'sql'                     => "binary(16) NULL"
        ],
        'teaser' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['teaser'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
            'sql'                     => "text NULL"
        ],
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'foreignKey'              => 'tl_page.title',
			'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
        'published' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
        ]
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 */
class tl_simple_jobs_organisation extends \Contao\Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }
}
