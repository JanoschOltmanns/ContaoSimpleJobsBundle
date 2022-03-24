<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models;

use Contao\Model;

class SimpleJobsOrganisationModel extends Model {

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_simple_jobs_organisation';

    public static function findByIds(array $organisationIds, array $arrOptions = []) {
        $t = static::$strTable;
        $arrColumns =array("$t.id IN(" . implode(',', array_map('\intval', $organisationIds)) . ")");

        if (!static::isPreviewMode($arrOptions)) {
            $arrColumns[] = "$t.published='1'";
        }

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = "$t.name ASC";
        }

        return static::findBy($arrColumns, null, $arrOptions);
    }

}
