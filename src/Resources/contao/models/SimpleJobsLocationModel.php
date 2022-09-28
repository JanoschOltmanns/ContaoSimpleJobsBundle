<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models;

use Contao\Model;

class SimpleJobsLocationModel extends Model {

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_simple_jobs_location';

    public static function findByIds(array $locationIds, array $arrOptions = []) {
        $t = static::$strTable;
        $arrColumns =array("$t.id IN(" . implode(',', array_map('\intval', $locationIds)) . ")");

        if (!static::isPreviewMode($arrOptions)) {
            $arrColumns[] = "$t.published='1'";
        }

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = "$t.addressLocality ASC";
        }

        return static::findBy($arrColumns, null, $arrOptions);
    }

    /**
     * @return string
     */
    public static function getDisplayName(SimpleJobsLocationModel $jobsLocationModel) {
        $name = $jobsLocationModel->addressLocality;
        if ($jobsLocationModel->streetAddress) {
            $name .= ', ' . $jobsLocationModel->streetAddress;
        }
        return $name;
    }

}
