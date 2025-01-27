<?php
/**
 * @package    DPAttachments
 * @copyright  Copyright (C) 2020 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use Codeception\Example;
use Step\Acceptance\Attachment;

class AttachmentViewCest extends \BasicDPAttachmentsCestClass
{
	private $url = '/index.php?option=com_dpattachments&view=attachment&id=';

	/**
	 * @dataProvider getImageFiles
	 */
	public function canOpenImageAttachmentDetailsPage(Attachment $I, Example $provider)
	{
		$I->wantToTest('that an image attachment can be displayed.');

		$attachment = $I->createAttachment(['path' => 'test.' . $provider['extension']]);

		$I->amOnPage($this->url . $attachment['id']);

		$I->see('test.' . $provider['extension']);
		$I->seeElement('.com-dpattachments-attachment-' . $provider['extension']);
		$I->seeElement('.com-dpattachments-attachment__content');
		$I->seeElement('img[src$="' . Attachment::ATTACHMENT_DIR . 'test.' . $provider['extension'] . '"]');
	}

	/**
	 * @dataProvider getTextFiles
	 */
	public function canOpenTextAttachmentDetailsPage(Attachment $I, Example $provider)
	{
		$I->wantToTest('that a text attachment can be displayed.');

		$attachment = $I->createAttachment(['path' => 'test.' . $provider['extension']]);

		$I->amOnPage($this->url . $attachment['id']);

		$I->see('test.' . $provider['extension']);
		$I->see('Test content');
		$I->seeElement('.com-dpattachments-attachment-' . $provider['extension']);
		$I->seeElement('.com-dpattachments-attachment__content');
	}

	protected function getImageFiles()
	{
		return [
			['extension' => 'gif'],
			['extension' => 'png'],
			['extension' => 'jpeg'],
			['extension' => 'jpg']
		];
	}

	protected function getTextFiles()
	{
		return [
			['extension' => 'txt'],
			['extension' => 'csv'],
			['extension' => 'patch'],
		];
	}
}
