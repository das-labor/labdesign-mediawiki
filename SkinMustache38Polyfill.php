<?php

/**
 * Makes some template data available so that data (we need) from 1.38 is available in 1.35.
 * Not exhaustive by far, this only does what we need for this skin.
 */
class SkinMustache38Polyfill extends SkinMustache35Polyfill
{
	/**
	 * taken from 1.38 Skin.php
	 */
	protected function _getSearchInputAttributes($attrs = [])
	{
		$autoCapHint = $this->getConfig()->get('CapitalLinks');
		$realAttrs = [
			'type' => 'search',
			'name' => 'search',
			'placeholder' => $this->msg('searchsuggest-search')->text(),
			'aria-label' => $this->msg('searchsuggest-search')->text(),
			// T251664: Disable autocapitalization of input
			// method when using fully case-sensitive titles.
			'autocapitalize' => $autoCapHint ? 'sentences' : 'none',
		];

		return array_merge($realAttrs, Linker::tooltipAndAccesskeyAttribs('search'), $attrs);
	}

	/**
	 * @inheritDoc
	 * Polyfills 1.38
	 */
	function getTemplateData(): array
	{
		global $wgVersion;
		$data = parent::getTemplateData();
		if (version_compare($wgVersion, '< 1.38')) {
			$searchButtonAttributes = [
				'class' => 'searchButton',
			];
			$fallbackButtonAttributes = [
				'class' => 'searchButton mw-fallbackSearchButton',
			];
			$buttonAttributes = [
				'type' => 'submit',
			];
			$extra_search_props = [
				'html-button-go-attributes' => Html::expandAttributes(
					$searchButtonAttributes + $buttonAttributes + [
						'name' => 'go',
					] + Linker::tooltipAndAccesskeyAttribs('search-go')
				),
				'html-button-fulltext-attributes' => Html::expandAttributes(
					$fallbackButtonAttributes + $buttonAttributes + [
						'name' => 'fulltext',
					] + Linker::tooltipAndAccesskeyAttribs('search-fulltext')
				),
				'html-input-attributes' => Html::expandAttributes(
					$this->_getSearchInputAttributes([])
				),
			];

			$data['data-search-box'] += $extra_search_props;
		}
		return $data;
	}
}
