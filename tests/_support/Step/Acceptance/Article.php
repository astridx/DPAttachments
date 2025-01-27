<?php
/**
 * @package    DPAttachments
 * @copyright  Copyright (C) 2020 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Step\Acceptance;

class Article extends \AcceptanceTester
{
	private $user = null;

	public function _inject(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Creates an article in the database and returns the article data
	 * as array including the id of the new article.
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function createArticle($data = null)
	{
		$I = $this;

		$article = [
			'catid'        => 2,
			'title'        => 'Test article',
			'alias'        => 'test-article',
			'introtext'    => 'Test description.',
			'featured'     => 1,
			'state'        => 1,
			'access'       => 1,
			'language'     => '*',
			'publish_up'   => date('Y-m-d'),
			'publish_down' => null,
			'created_by'   => $this->user->getLoggedInUserId()
		];

		if (is_array($data)) {
			$article = array_merge($article, $data);
		}

		$article['id'] = $I->haveInDatabase('content', $article);
		$I->haveInDatabase('content_frontpage', ['content_id' => $article['id'], 'ordering' => 1]);

		$I->haveInDatabase('workflow_associations', ['item_id' => $article['id'], 'stage_id' => 1, 'extension' => 'com_content.article']);

		return $article;
	}
}
