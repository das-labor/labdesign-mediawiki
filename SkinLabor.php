<?php
/**
 * Labor -- the new look of wiki.das-labor.org
 * MediaWiki implementation based on MonoBook nouveau.
 *
 * Copyright (C) 2014-2015 Alex Legler <a3li@gentoo.org>
 */

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinLabor extends SkinTemplate {
	public $skinname  = 'labor';
	public $stylename = 'Labor';
	public $template  = 'LaborTemplate';

	const CDN_URL = '/skins/Labor';

	function setupSkinUserCss(OutputPage $out) {
		parent::setupSkinUserCss($out);

		$out->addStyle(SkinLabor::CDN_URL . '/css/bootstrap.min.css');
		$out->addStyle(SkinLabor::CDN_URL . '/css/labor.css');

		$out->addModuleStyles(array(
			'mediawiki.skinning.interface',
			'mediawiki.skinning.content.externallinks',
			'skins.labor.styles'
		));
	}
}
