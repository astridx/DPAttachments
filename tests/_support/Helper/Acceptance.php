<?php
/**
 * @package    DPAttachments
 * @copyright  Copyright (C) 2020 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Helper;

class Acceptance extends \Codeception\Module
{
	public function getConfiguration($element = null)
	{
		if (is_null($element)) {
			throw new \InvalidArgumentException('empty value or non existing element was requested from configuration');
		}

		return $this->config[$element];
	}

	public function amOnPage($link)
	{
		/** @var Joomla\Browser\JoomlaBrowser $browser */
		$browser = $this->getModule('Joomla\Browser\JoomlaBrowser');
		$browser->amOnPage($link);

		$browser->dontSeeInPageSource('Deprecated:');
		$browser->dontSeeInPageSource('<b>Deprecated</b>:');
		$browser->checkForPhpNoticesOrWarnings();
		$this->checkForJsErrors();
	}

	public function checkForJsErrors()
	{
		$logs = $this->getModule('Joomla\Browser\JoomlaBrowser')->webDriver->manage()->getLog('browser');
		foreach ($logs as $log) {
			// Only look for internal JS errors
			if (strpos($log['message'], $this->getModule('Joomla\Browser\JoomlaBrowser')->_getConfig()['url']) !== 0) {
				continue;
			}

			// J4 throws some CORS warnings
			if (strpos($log['message'], 'The Cross-Origin-Opener-Policy header has been ignored') !== 0) {
				continue;
			}

			$this->assertNotEquals('SEVERE', $log['level'], 'Some error in JavaScript: ' . json_encode($log));
		}
	}

	public function setExtensionParam($key, $value, $extension = 'com_dpattachments')
	{
		$db     = $this->getModule('Helper\\JoomlaDb');
		$params = $db->grabFromDatabase('extensions', 'params', ['name' => $extension]);

		$params       = json_decode($params);
		$params->$key = $value;
		$db->updateInDatabase('extensions', ['params' => json_encode($params)], ['name' => $extension]);
	}

	public function createCat($title)
	{
		/** @var Joomla\Browser\JoomlaBrowser $browser */
		$I = $this->getModule('Joomla\Browser\JoomlaBrowser');

		$I->doAdministratorLogin(null, null, false);
		$I->amOnPage('administrator/index.php?option=com_categories&extension=com_content');
		$I->click('New');
		$I->fillField(['id' => 'jform_title'], $title);
		$I->click('Save & Close');

		$db = $this->getModule('Helper\\JoomlaDb');

		return $db->grabFromDatabase('categories', 'id', ['title' => $title, 'extension' => 'com_content']);
	}

	/**
	 * Lightweight variant of plugin enable.
	 *
	 * @param $pluginName
	 */
	public function enablePlugin($pluginName, $enable = true)
	{
		$this->getModule('Helper\\JoomlaDb')
			->updateInDatabase('extensions', ['enabled' => $enable ? 1 : 0], ['name' => $pluginName]);
	}
}
