<?php
/**
 * Labor -- the new look of wiki.das-labor.org
 * MediaWiki implementation based on MonoBook nouveau.
 *
 * Copyright (C) 2014-2016 Alex Legler <a3li@gentoo.org>
 */

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinLabor extends SkinTemplate {
	public $skinname  = 'labor';
	public $stylename = 'Labor';
	public $template  = 'LaborTemplate';

	function setupSkinUserCss(OutputPage $out) {
		$this->output = $out;

		parent::setupSkinUserCss($out);

                $CDN_URL = $this->getConfig()->get( 'LocalStylePath' ) .
                                 '/Labor';

		$out->addStyle($CDN_URL . '/css/bootstrap.min.css');
		$out->addStyle($CDN_URL . '/css/labor.css');
		$out->addStyle($CDN_URL . '/css/flatpickr/flatpickr.min.css');

		$out->addModuleStyles(array(
			'mediawiki.skinning.interface',
			'mediawiki.skinning.content.externallinks',
			'skins.labor.styles'
		));
	}
}
