<?php
/**
 * @package    DPAttachments
 * @copyright  Copyright (C) 2021 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Page\Acceptance\Administrator;

class AttachmentsListPage extends \AcceptanceTester
{
	public static $url       = '/administrator/index.php?option=com_dpattachments&view=attachments';
	public static $rootClass = '.com-dpattachments-attachments';
}
