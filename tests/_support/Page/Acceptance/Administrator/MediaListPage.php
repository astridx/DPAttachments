<?php
/**
 * @package    DPAttachments
 * @copyright  Copyright (C) 2022 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Page\Acceptance\Administrator;

class MediaListPage extends \AcceptanceTester
{
	public static $url = '/administrator/index.php?option=com_media&path=local-images:/test';
}
