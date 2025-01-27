<?php
/**
 * @package    DPAttachments
 * @copyright  Copyright (C) 2013 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace DigitalPeak\Component\DPAttachments\Administrator\Controller;

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;

class AttachmentController extends FormController
{
	protected function allowEdit($data = [], $key = 'id')
	{
		$recordId = (int)isset($data[$key]) ? $data[$key] : 0;
		$user     = Factory::getUser();
		$userId   = $user->get('id');

		// Check general edit permission first
		if ($user->authorise('core.edit', 'com_dpattachments')) {
			return true;
		}

		// Fallback on edit.own
		// First test if the permission is available
		if ($user->authorise('core.edit.own', 'com_dpattachments')) {
			// Now test the owner is the user
			$ownerId = (int)isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId) {
				// Need to do a lookup from the model
				$record = $this->getModel()->getItem($recordId);

				if (empty($record)) {
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test
			if ($ownerId == $userId) {
				return true;
			}
		}

		// Since there is no asset tracking, revert to the component permissions
		return parent::allowEdit($data, $key);
	}
}
