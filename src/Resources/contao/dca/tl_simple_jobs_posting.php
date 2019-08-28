<?php


$GLOBALS['TL_DCA']['tl_simple_jobs_posting'] = array
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
			array('tl_simple_jobs_posting', 'adjustSitemap')
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
			'fields'                  => array('datePosted'),
			//'flag'                    => 1,
            'headerFields'            => array('name'),
			'panelLayout'             => 'filter;sort,search,limit',
            'child_record_callback'   => array('tl_simple_jobs_posting', 'listJob')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['edit'],
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
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.svg'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.svg'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				//'button_callback'     => array('tl_news', 'toggleIcon'),
				'showInHeader'        => true
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{posting_legend},title,alias,datePosted,employmentType,locations,keywords,description;{published_legend},published,start,stop'
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
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
        'title' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['title'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_simple_jobs_posting', 'generateAlias')
			),
			'sql'                     => "varchar(128) BINARY NOT NULL default ''"
        ),
        'datePosted' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['datePosted'],
			'default'                 => time(),
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard clr'),
			'load_callback' => array
			(
				array('tl_simple_jobs_posting', 'loadDate')
			),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'employmentType' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['employmentType'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('FULL_TIME', 'PART_TIME', 'CONTRACTOR', 'TEMPORARY', 'INTERN', 'VOLUNTEER', 'PER_DIEM', 'OTHER'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['employmentTypeReference'],
			'eval'                    => array('mandatory'=>true, 'includeBlankOption'=>true, 'multiple'=>true, 'nospace'=>true, 'tl_class'=>'w50', 'chosen'=>true),
			'sql'                     => "blob NULL",
        ),
        'locations' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['locations'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_simple_jobs_location.addressLocality',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'tl_class'=>'clr w50'),
			'sql'                     => "blob NULL",
			'relation'                => array('type'=>'hasMany', 'load'=>'eager')
        ),
        'keywords' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['keywords'],
            'exclude'                 => true,
			'inputType'               => 'listWizard',
			'eval'                    => array('allowHtml'=>true, 'tl_class'=>'w50'),
			'sql'                     => "blob NULL"
        ),
        'description' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['description'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'rte'=>'tinyMCE', 'helpwizard'=>true, 'tl_class'=>'clr'),
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NULL"
        ),
        'published' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'start' => array(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['start'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        'stop' => array(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['stop'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
        )
    ]
);


class tl_simple_jobs_posting extends \Contao\Backend {

    public function listJob($arrRow) {
		return '<div><span class="tl_gray">' . \Date::parse(\Config::get('dateFormat'), $arrRow['datePosted']) . ':</span> ' . \StringUtil::restoreBasicEntities($arrRow['title']) . '</div>';
    }

	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$slugOptions = array();

			// Read the slug options from the associated page
			if (($objFaqCategory = \JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel::findByPk($dc->activeRecord->pid)) !== null && ($objPage = PageModel::findWithDetails($objFaqCategory->jumpTo)) !== null)
			{
				$slugOptions = $objPage->getSlugOptions();
			}

			$varValue = System::getContainer()->get('contao.slug.generator')->generate(StringUtil::prepareSlug($dc->activeRecord->title), $slugOptions);

			// Prefix numeric aliases (see #1598)
			if (is_numeric($varValue))
			{
				$varValue = 'id-' . $varValue;
			}
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_simple_jobs_posting WHERE alias=? AND id!=?")
								   ->execute($varValue, $dc->id);

		// Check whether the FAQ alias exists
		if ($objAlias->numRows)
		{
			if (!$autoAlias)
			{
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}

			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}

    /**
	 * Set the timestamp to 00:00:00 (see #26)
	 *
	 * @param integer $value
	 *
	 * @return integer
	 */
	public function loadDate($value)
	{
		return strtotime(date('Y-m-d', $value) . ' 00:00:00');
	}

    public function adjustSitemap()
	{

		$this->import('Automator');
		$this->Automator->generateSitemap();

	}


}
