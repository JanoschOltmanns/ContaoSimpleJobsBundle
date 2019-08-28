<?php


$GLOBALS['TL_DCA']['tl_simple_jobs_location'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_simple_jobs_organisation',
		//'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			//array('tl_news', 'checkPermission'),
			//array('tl_news', 'generateFeed')
		),
		'oncut_callback' => array
		(
			//array('tl_news', 'scheduleUpdate')
		),
		'ondelete_callback' => array
		(
			//array('tl_news', 'scheduleUpdate')
		),
		'onsubmit_callback' => array
		(
			//array('tl_news', 'adjustTime'),
			//array('tl_news', 'scheduleUpdate')
		),
        'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
                //'published' => 'index'
			)
		)

    ),

    'list' => array
	(
		/*'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('addressLocality'),
			'headerFields'            => array('addressLocality'),
			'panelLayout'             => 'filter;sort,search,limit',
			//'child_record_callback'   => array('tl_news', 'listNewsArticles'),
			'child_record_class'      => 'no_padding'
		),
		'label' => array
		(
			'fields'                  => array('addressLocality'),
			'showColumns'             => true,
			//'label_callback'          => array('tl_user', 'addIcon')
		),*/
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('addressLocality'),
			//'flag'                    => 1,
            'headerFields'            => array('name'),
			'panelLayout'             => 'filter;sort,search,limit',
            'child_record_callback'   => array('tl_simple_jobs_location', 'listLocations')
		),
		/*'label' => array
		(
			'fields'                  => array('addressLocality'),
			//'showColumns'             => true,
			//'label_callback'          => array('tl_member', 'addIcon')
		),*/
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			/*'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news']['editmeta'],
				'href'                => 'act=edit',
				'icon'                => 'header.svg'
			),*/
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.svg'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.svg'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				//'button_callback'     => array('tl_news', 'toggleIcon'),
				'showInHeader'        => true
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{address_legend},streetAddress,postalCode,addressLocality,addressRegion,addressCountry;{published_legend},published'
	),

    // Fields
    'fields' => [
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_simple_jobs_organisation.name',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
        'type' => array(

        ),
        'streetAddress' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['street'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'postalCode' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['postal'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(32) NOT NULL default ''"
        ),
        'addressLocality' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['city'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'addressRegion' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['state'],
			'exclude'                 => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'addressCountry' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['country'],
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'inputType'               => 'select',
			'eval'                    => array('mandatory'=>true, 'includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
			'options_callback' => function ()
			{
				return System::getCountries();
			},
			'sql'                     => "varchar(2) NOT NULL default ''"
        ),
        'published' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_location']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
        )
    ]
);


class tl_simple_jobs_location extends \Contao\Backend {

    public function listLocations($arrRow) {
		return '<div>' . $arrRow['postalCode'] . ' ' . $arrRow['addressLocality'] . ', ' . $arrRow['streetAddress'] . '</div>';
    }

}
