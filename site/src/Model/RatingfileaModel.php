<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
namespace Phoca\Component\Phocadownload\Site\Model;
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Phoca\Component\Phocadownload\Administrator\Rate\Rate;


class RatingFileAModel extends BaseDatabaseModel
{

	public function rate($data) {
		$row = $this->getTable('Filevotes');

		if (!$row->bind($data)) {
			//throw new \Exception('Database error');
			$this->setError($row->getError());
			return false;
		}

		$row->date 		= gmdate('Y-m-d H:i:s');

		$row->published = 1;

		if (!$row->id) {
			$where = 'fileid = ' . (int) $row->fileid ;
			$row->ordering = $row->getNextOrder( $where );
		}

		if (!$row->check()) {
			//throw new \Exception('Database error');
			$this->setError($row->getError());
			return false;
		}

		if (!$row->store()) {
			//throw new \Exception('Database error');
			$this->setError($row->getError());
			return false;
		}

		// Update the Vote Statistics
		if (!Rate::updateVoteStatisticsFile( $data['fileid'])) {
			return false;
		}

		return true;
	}
}
?>
