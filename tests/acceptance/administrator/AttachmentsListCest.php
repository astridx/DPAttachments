<?php
/**
 * @package    DPCalendar
 * @copyright  Copyright (C) 2021 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

use Page\Acceptance\Administrator\AttachmentsListPage;
use Step\Acceptance\Attachment;

class AttachmentsListCest extends \BasicDPAttachmentsCestClass
{
	public function _before(\AcceptanceTester $I)
	{
		parent::_before($I);

		$I->doAdministratorLogin(null, null, false);
	}

	public function canNavigateToAttachmentForm(Attachment $I)
	{
		$I->wantToTest('that it is possible to edit an attachment.');

		$I->createAttachment(['path' => 'test.jpg']);

		$I->amOnPage(AttachmentsListPage::$url);
		$I->waitForElement(AttachmentsListPage::$rootClass);
		$I->click('test.jpg');
		$I->waitForElement('.com-dpattachments-attachment-form');

		$I->seeInCurrentUrl('view=attachment');
	}

	public function seeAttachmentsInList(Attachment $I)
	{
		$I->wantToTest('that attachments are displayed in the list.');

		$I->createAttachment(['path' => 'test.jpg']);
		$I->createAttachment(['path' => 'test.png']);

		$I->amOnPage(AttachmentsListPage::$url);

		$I->seeNumberOfElements(AttachmentsListPage::$rootClass . ' .dp-attachment', 2);
	}

	public function canDeleteAttachment(Attachment $I)
	{
		$I->wantToTest('that attachments are displayed in the list.');

		$I->createAttachment(['path' => 'test.jpg', 'state' => -2]);
		$I->createAttachment(['path' => 'test.png', 'state' => -2]);

		$I->amOnPage(AttachmentsListPage::$url);
		$I->click('Filter Options');
		$I->selectOption('#filter_state', 'Trashed');
		$I->click('input[name="checkall-toggle"]');
		$I->clickToolbarButton('Empty Trash');

		$I->dontSeeInDatabase('dpattachments', []);
		$I->dontSeeFileFound($I->getConfiguration('home_dir') . Attachment::ATTACHMENT_DIR . 'test.jpg');
		$I->dontSeeFileFound($I->getConfiguration('home_dir') . Attachment::ATTACHMENT_DIR . 'test.png');
	}
}
