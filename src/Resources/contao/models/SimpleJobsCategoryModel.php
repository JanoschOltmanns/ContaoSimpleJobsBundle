<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models;

use Contao\Model;

class SimpleJobsCategoryModel extends Model {

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_simple_jobs_category';

    public static function findByIds(array $categoryIds, array $arrOptions = []) {
        $t = static::$strTable;
        $arrColumns =array("$t.id IN(" . implode(',', array_map('\intval', $categoryIds)) . ")");

        if (!static::isPreviewMode($arrOptions)) {
            //$arrColumns[] = "$t.published='1'"; // Todo: implement published logic
        }

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = "$t.title ASC";
        }

        return static::findBy($arrColumns, null, $arrOptions);
    }

}
