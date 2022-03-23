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
        'onload_callback' => array
        (
            //array('tl_simple_jobs_organisation', 'checkPermission')
        ),
        'oncreate_callback' => array
        (
            //array('tl_simple_jobs_organisation', 'adjustPermissions')
        ),
        'oncopy_callback' => array
        (
            //array('tl_simple_jobs_organisation', 'adjustPermissions')
        ),
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
                //'button_callback'     => array('tl_news_archive', 'manageFeeds')
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
                'button_callback' => array('tl_simple_jobs_organisation', 'editHeader')
            ),
            'copy' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.svg',
                'button_callback' => array('tl_simple_jobs_organisation', 'copyChannel')
            ),
            'delete' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_simple_jobs_organisation']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
                'button_callback' => array('tl_simple_jobs_organisation', 'deleteChannel')
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
        'default' => '{title_legend},name,sameAs,logo;{settings_legend},jumpTo;{publish_legend},published'
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

    /**
     * Check permissions to edit table tl_newsletter_channel
     *
     * @throws Contao\CoreBundle\Exception\AccessDeniedException
     */
    public function checkPermission()
    {
        if ($this->User->isAdmin) {
            return;
        }

        // Set root IDs
        if (empty($this->User->newsletters) || !\is_array($this->User->newsletters)) {
            $root = array(0);
        } else {
            $root = $this->User->newsletters;
        }

        $GLOBALS['TL_DCA']['tl_newsletter_channel']['list']['sorting']['root'] = $root;

        // Check permissions to add channels
        if (!$this->User->hasAccess('create', 'newsletterp')) {
            $GLOBALS['TL_DCA']['tl_newsletter_channel']['config']['closed'] = true;
            $GLOBALS['TL_DCA']['tl_newsletter_channel']['config']['notCreatable'] = true;
            $GLOBALS['TL_DCA']['tl_newsletter_channel']['config']['notCopyable'] = true;
        }

        // Check permissions to delete channels
        if (!$this->User->hasAccess('delete', 'newsletterp')) {
            $GLOBALS['TL_DCA']['tl_newsletter_channel']['config']['notDeletable'] = true;
        }

        /** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
        $objSession = System::getContainer()->get('session');

        // Check current action
        switch (Input::get('act')) {
            case 'select':
                // Allow
                break;

            case 'create':
                if (!$this->User->hasAccess('create', 'newsletterp')) {
                    throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to create newsletter channels.');
                }
                break;

            case 'edit':
            case 'copy':
            case 'delete':
            case 'show':
                if (!\in_array(Input::get('id'), $root) || (Input::get('act') == 'delete' && !$this->User->hasAccess('delete', 'newsletterp'))) {
                    throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to ' . Input::get('act') . ' newsletter channel ID ' . Input::get('id') . '.');
                }
                break;

            case 'editAll':
            case 'deleteAll':
            case 'overrideAll':
            case 'copyAll':
                $session = $objSession->all();
                if (Input::get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'newsletterp')) {
                    $session['CURRENT']['IDS'] = array();
                } else {
                    $session['CURRENT']['IDS'] = array_intersect((array)$session['CURRENT']['IDS'], $root);
                }
                $objSession->replace($session);
                break;

            default:
                if (\strlen(Input::get('act'))) {
                    throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to ' . Input::get('act') . ' newsletter channels.');
                }
                break;
        }
    }

    /**
     * Add the new channel to the permissions
     *
     * @param $insertId
     */
    public function adjustPermissions($insertId)
    {
        // The oncreate_callback passes $insertId as second argument
        if (\func_num_args() == 4) {
            $insertId = func_get_arg(1);
        }

        if ($this->User->isAdmin) {
            return;
        }

        // Set root IDs
        if (empty($this->User->forms) || !\is_array($this->User->forms)) {
            $root = array(0);
        } else {
            $root = $this->User->forms;
        }

        // The channel is enabled already
        if (\in_array($insertId, $root)) {
            return;
        }

        /** @var Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $objSessionBag */
        $objSessionBag = System::getContainer()->get('session')->getBag('contao_backend');

        $arrNew = $objSessionBag->get('new_records');

        if (\is_array($arrNew['tl_newsletter_channel']) && \in_array($insertId, $arrNew['tl_newsletter_channel'])) {
            // Add the permissions on group level
            if ($this->User->inherit != 'custom') {
                $objGroup = $this->Database->execute("SELECT id, newsletters, newsletterp FROM tl_user_group WHERE id IN(" . implode(',', array_map('\intval', $this->User->groups)) . ")");

                while ($objGroup->next()) {
                    $arrNewsletterp = StringUtil::deserialize($objGroup->newsletterp);

                    if (\is_array($arrNewsletterp) && \in_array('create', $arrNewsletterp)) {
                        $arrNewsletters = StringUtil::deserialize($objGroup->newsletters, true);
                        $arrNewsletters[] = $insertId;

                        $this->Database->prepare("UPDATE tl_user_group SET newsletters=? WHERE id=?")
                            ->execute(serialize($arrNewsletters), $objGroup->id);
                    }
                }
            }

            // Add the permissions on user level
            if ($this->User->inherit != 'group') {
                $objUser = $this->Database->prepare("SELECT newsletters, newsletterp FROM tl_user WHERE id=?")
                    ->limit(1)
                    ->execute($this->User->id);

                $arrNewsletterp = StringUtil::deserialize($objUser->newsletterp);

                if (\is_array($arrNewsletterp) && \in_array('create', $arrNewsletterp)) {
                    $arrNewsletters = StringUtil::deserialize($objUser->newsletters, true);
                    $arrNewsletters[] = $insertId;

                    $this->Database->prepare("UPDATE tl_user SET newsletters=? WHERE id=?")
                        ->execute(serialize($arrNewsletters), $this->User->id);
                }
            }

            // Add the new element to the user object
            $root[] = $insertId;
            $this->User->newsletter = $root;
        }
    }

    /**
     * Return the edit header button
     *
     * @param array $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function editHeader($row, $href, $label, $title, $icon, $attributes)
    {
        return $this->User->canEditFieldsOf('tl_newsletter_channel') ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)) . ' ';
    }

    /**
     * Return the copy channel button
     *
     * @param array $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function copyChannel($row, $href, $label, $title, $icon, $attributes)
    {
        return $this->User->hasAccess('create', 'newsletterp') ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)) . ' ';
    }

    /**
     * Return the delete channel button
     *
     * @param array $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function deleteChannel($row, $href, $label, $title, $icon, $attributes)
    {
        return $this->User->hasAccess('delete', 'newsletterp') ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)) . ' ';
    }
}
