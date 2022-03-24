<?php

namespace JanoschOltmanns\ContaoSimpleJobsBundle\Contao\Models;

use Contao\Date;
use Contao\Model;

class SimpleJobsPostingModel extends Model {

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_simple_jobs_posting';

	/**
	 * Find all published job postings by their parent IDs
	 *
	 * @param array $arrPids    An array of organisation IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return Model\Collection|SimpleJobsPostingModel[]|SimpleJobsPostingModel|null A collection of models or null if there are no job postings
	 */
	public static function findPublishedByPids($arrPids, array $filters=[], array $arrOptions=[])
	{
		if (empty($arrPids) || !\is_array($arrPids)) {
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")");
		$varValue   = null;

		if (!static::isPreviewMode($arrOptions)) {
			$arrColumns[] = "$t.published='1'";
		}

		if (!isset($arrOptions['order'])) {
			$arrOptions['order'] = "$t.datePosted DESC";
		}

        if (isset($filters['location'])) {
            $locationOrs = [];
            foreach ($filters['location'] as $locationId) {
                $locationOrs[] = "$t.locations LIKE ?";
                $varValue[] = "%\"" . $locationId . "\"%";
            }
            $arrColumns[] = "(" . implode(" OR ", $locationOrs) . ")";
        }
        if (isset($filters['employmentType'])) {
            $arrColumns[] = "$t.employmentType LIKE ?";
            $varValue[] = "%\"" . $filters['employmentType'] . "\"%";
        }
        if (isset($filters['category'])) {
            $arrColumns[] = "$t.category=?";
            $varValue[] = $filters['category'];
        }
        if (isset($filters['keyword'])) {
            $arrColumns[] = "$t.keywords LIKE ?";
            $varValue[] = "%\"" . $filters['keyword'] . "\"%";
        }
        if (isset($filters['organisation'])) {
            $arrColumns[] = "$t.pid=?";
            $varValue[] = $filters['organisation'];
        }

		return static::findBy($arrColumns, $varValue, $arrOptions);
	}

    /**
     * Find all published job postings by their parent IDs
     *
     * @param array $arrPids    An array of organisation IDs
     * @param array $categories An array of category IDs
     * @param array $arrOptions An optional options array
     *
     * @return Model\Collection|SimpleJobsPostingModel[]|SimpleJobsPostingModel|null A collection of models or null if there are no job postings
     */
    public static function findPublishedByPidsAndCategories($arrPids, array $categories, array $filters=[], array $arrOptions=[])
    {
        if (empty($arrPids) || !\is_array($arrPids))
        {
            return null;
        }

        $t = static::$strTable;

        // Todo: hier passt was noch nicht
        $arrColumns = array("$t.pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")");
        $arrColumns[] = "$t.category IN(" . implode(',', array_map('\intval', $categories)) . ")";

        if (!static::isPreviewMode($arrOptions)) {
            $arrColumns[] = "$t.published='1'";
        }

        if (!isset($arrOptions['order'])) {
            $arrOptions['order'] = "$t.datePosted DESC";
        }

        if (isset($filters['location'])) {
            $locationOrs = [];
            foreach ($filters['location'] as $locationId) {
                $locationOrs[] = "$t.locations LIKE ?";
                $varValue[] = "%\"" . $locationId . "\"%";
            }
            $arrColumns[] = "(" . implode(" OR ", $locationOrs) . ")";
        }
        if (isset($filters['employmentType'])) {
            $arrColumns[] = "$t.employmentType LIKE ?";
            $varValue[] = "%\"" . $filters['employmentType'] . "\"%";
        }
        if (isset($filters['category'])) {
            $arrColumns[] = "$t.category=?";
            $varValue[] = $filters['category'];
        }
        if (isset($filters['keyword'])) {
            $arrColumns[] = "$t.keywords LIKE ?";
            $varValue[] = "%\"" . $filters['keyword'] . "\"%";
        }
        if (isset($filters['organisation'])) {
            $arrColumns[] = "$t.pid=?";
            $varValue[] = $filters['organisation'];
        }

        return static::findBy($arrColumns, null, $arrOptions);
    }

	/**
	 * Find all published FAQs by their parent ID
	 *
	 * @param int   $intPid     The parent ID
	 * @param array $arrOptions An optional options array
	 *
	 * @return Model\Collection|SimpleJobsPostingModel[]|SimpleJobsPostingModel|null A collection of models or null if there are no FAQs
	 */
	public static function findPublishedByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!static::isPreviewMode($arrOptions))
		{
			$arrColumns[] = "$t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.datePosted DESC";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}

	/**
	* @param $varId
	* @param array $arrOptions
	* @return Model|Model[]|Model\Collection|SimpleJobsPostingModel|null
	*/
    public static function findPublishedByIdOrAlias($varId, array $arrOptions=array())
    {
        $t = static::$strTable;
        $arrColumns = !preg_match('/^[1-9]\d*$/', $varId) ? array("$t.alias=?") : array("$t.id=?");

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
        }

        return static::findOneBy($arrColumns, $varId, $arrOptions);
	}
    
}
