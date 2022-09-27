<?php

Contao\System::loadLanguageFile('tl_content');

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
			//array('tl_simple_jobs_posting', 'adjustSitemap')
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
            array('tl_simple_jobs_posting', 'adjustSitemap')
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
				'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_simple_jobs_posting', 'toggleIcon'),
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
	    '__selector__' => array('addSalary', 'addEnclosure', 'addImage', 'overwriteMeta'),
		'default' => '{posting_legend},title,category,alias,datePosted,employmentType,locations,keywords,teaser,description;{salary_legend},addSalary;{image_legend},addImage;{enclosures_legend},addEnclosure;{published_legend},validThrough,published,start,stop'
    ),

    // Subpalettes
    'subpalettes' => array(
        'addEnclosure' => 'enclosure',
        'addSalary' => 'salaryValue,salaryValueMax,salaryUnit',
        'addImage' => 'singleSRC,overwriteMeta',
        'overwriteMeta' => 'alt,imageTitle,imageUrl,caption'
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
        'category' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['category'],
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_simple_jobs_category.title',
            'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default 0",
            'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
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
        'teaser' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['teaser'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
            'sql'                     => "text NULL"
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
        'addSalary' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['addSalary'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'salaryValue' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['salaryValue'],
            'exclude'                 => true,
            'inputType'               => 'inputUnit',
            'options'                 => array('EUR', 'USD'),
            'eval'                    => array('mandatory'=>true, 'includeBlankOption'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'salaryValueMax' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['salaryValueMax'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
            'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        'salaryUnit' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['salaryUnit'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('HOUR', 'DAY', 'WEEK', 'MONTH', 'YEAR'),
            'reference'               => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['salaryUnitReference'],
            'eval'                    => array('mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(5) NOT NULL default ''"
        ),
        'addImage' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['addImage'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'overwriteMeta' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_content']['overwriteMeta'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'w50 clr'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'singleSRC' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_content']['singleSRC'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'extensions'=>Contao\Config::get('validImageTypes'), 'mandatory'=>true),
            'sql'                     => "binary(16) NULL"
        ),
        'alt' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_content']['alt'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'imageTitle' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imageTitle'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'imageUrl' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imageUrl'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'dcaPicker'=>true, 'addWizardClass'=>false, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'caption' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_content']['caption'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'allowHtml'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'addEnclosure' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['addEnclosure'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'enclosure' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['enclosure'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox', 'filesOnly'=>true, 'isDownloads'=>true, 'extensions'=>\Contao\Config::get('allowedDownload'), 'mandatory'=>true, 'orderField'=>'orderEnclosure'),
            'sql'                     => "blob NULL"
        ),
        'orderEnclosure' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['MSC']['sortOrder'],
            'sql'                     => "blob NULL"
        ),
        'validThrough' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['validThrough'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'date', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "int(10) unsigned NULL"
        ),
        'published' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_simple_jobs_posting']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class' => 'clr'),
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

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('Contao\BackendUser', 'User');
    }

    /**
     * List a job posting
     *
     * @param array $arrRow
     * @return string
     */
    public function listJob($arrRow) {
		return '<div><span class="tl_gray">' . \Date::parse(\Config::get('dateFormat'), $arrRow['datePosted']) . ':</span> ' . \StringUtil::restoreBasicEntities($arrRow['title']) . '</div>';
    }

    /**
     * @param string $varValue
     * @param \Contao\DataContainer $dc
     * @return string
     */
	public function generateAlias(string $varValue, \Contao\DataContainer $dc)
	{
        $aliasExists = function (string $alias) use ($dc): bool
        {
            return $this->Database->prepare("SELECT id FROM tl_simple_jobs_posting WHERE alias=? AND id!=?")->execute($alias, $dc->id)->numRows > 0;
        };

        // Generate alias if there is none
        if (!$varValue)
        {
            $varValue = \Contao\System::getContainer()->get('contao.slug')->generate(
                $dc->activeRecord->title,
                \JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models\SimpleJobsOrganisationModel::findByPk($dc->activeRecord->pid)->jumpTo,
                $aliasExists
            );
        }
        elseif ($aliasExists($varValue))
        {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        return $varValue;
	}

    /**
	 * Set the timestamp to 00:00:00
	 *
	 * @param integer $value
	 * @return integer
	 */
	public function loadDate($value)
	{
		return strtotime(date('Y-m-d', $value) . ' 00:00:00');
	}

    /**
     *
     */
    public function adjustSitemap()
	{
		$this->import('Automator');
		$this->Automator->generateSitemap();
	}

    /**
     * Return the "toggle visibility" button
     *
     * @param array  $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (\Contao\Input::get('tid'))
        {
            $this->toggleVisibility(\Contao\Input::get('tid'), (\Contao\Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->hasAccess('tl_simple_jobs_posting::published', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published'])
        {
            $icon = 'invisible.svg';
        }

        return '<a href="' . $this->addToUrl($href) . '" title="' . \Contao\StringUtil::specialchars($title) . '"' . $attributes . '>' . \Contao\Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }

    /**
     * Disable/enable a job posting
     *
     * @param integer              $intId
     * @param boolean              $blnVisible
     * @param \Contao\DataContainer $dc
     */
    public function toggleVisibility($intId, $blnVisible, \Contao\DataContainer $dc=null)
    {
        // Set the ID and action
        \Contao\Input::setGet('id', $intId);
        \Contao\Input::setGet('act', 'toggle');

        if ($dc)
        {
            $dc->id = $intId; // see #8043
        }

        // Check the field access
        if (!$this->User->hasAccess('tl_simple_jobs_posting::published', 'alexf'))
        {
            throw new \Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish job posting ID ' . $intId . '.');
        }

        $objRow = $this->Database->prepare("SELECT * FROM tl_simple_jobs_posting WHERE id=?")
            ->limit(1)
            ->execute($intId);

        if ($objRow->numRows < 1)
        {
            throw new \Contao\CoreBundle\Exception\AccessDeniedException('Invalid job posting ID ' . $intId . '.');
        }

        // Set the current record
        if ($dc)
        {
            $dc->activeRecord = $objRow;
        }

        $objVersions = new \Contao\Versions('tl_simple_jobs_posting', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_simple_jobs_posting']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_simple_jobs_posting']['fields']['published']['save_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                }
                elseif (is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $this->Database->prepare("UPDATE tl_simple_jobs_posting SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
            ->execute($intId);

        if ($dc)
        {
            $dc->activeRecord->tstamp = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (is_array($GLOBALS['TL_DCA']['tl_simple_jobs_posting']['config']['onsubmit_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_simple_jobs_posting']['config']['onsubmit_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();

        if ($dc)
        {
            $dc->invalidateCacheTags();
        }
    }
}
