<?php
/**
 * Labor -- the new look of wiki.das-labor.org, Based on Tyrian from gentoo.org
 *
 * Copyright (C) 2014-2016 Alex Legler <a3li@gentoo.org>
 */

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 *
 * @ingroup Skins
 */
class SkinLabor extends SkinMustache38Polyfill
{
	public $skinname = 'labor';
	public $stylename = 'Labor';
	public $template = 'LaborTemplate';

	private function a()
	{

	}

	public function getTemplateData(): array
	{
		$data = parent::getTemplateData();

		$data['pageLanguage'] = $this->getTitle()->getPageViewLanguage()->getHtmlCode();

		$data['newtalk'] = $this->getNewtalks();
		$data['newtalk'] = $data['newtalk'] !== '' ? $data['newtalk'] : false;

		// hide the copyright notice in the footer
		$data['data-footer']['data-info']['array-items'] =
			array_filter($data['data-footer']['data-info']['array-items'], function ($item) {
				return $item['name'] !== 'copyright';
			});


		// button labels for the search bar,
		// missing from the data for some reason
		$data["msg-searcharticle"] = $this->msg('searcharticle')->text();
		$data["msg-searchbutton"] = $this->msg('searchbutton')->text();
		$data["msg-toolbox"] = $this->msg('toolbox')->text();


		// apply another class on the selected item here
		// without rebuilding all of it
		// yes, this is kinda hacked up
		$namespaces_items = $data['data-portlets']['data-namespaces']['html-items'];
		$data['data-portlets']['data-namespaces']['html-items'] = str_replace('class="selected"', 'class="selected active"', $namespaces_items);


		# split out the toolbox portlet, so it can be rendered separately
		$nav_portlets = $data['data-portlets-sidebar']['array-portlets-rest'];
		$arr = array_values(array_filter($nav_portlets, function ($portlet) {
			return $portlet['id'] === 'p-tb';
		}))[0];
		$data['data-portlets-sidebar']['data-portlets-toolbox'] = $arr;
		$data['data-portlets-sidebar']['array-portlets-rest']
			= array_filter($nav_portlets, function ($portlet) {
			return $portlet['id'] !== 'p-tb';
		});

		// TODO we don't support divider elements for sidebar portlets?
		//  but maybe mw does them itself now?
		//  also not supported: dropdown headers
		//  not currently used in the labor skin though, so *shrug*
		// if ( $val['text'] === '---' ) {
		// 	   echo '<li role="presentation" class="divider"></li>';
		// } elseif ( substr( $val['text'], 0, 7 ) === 'header:' ) {
		//     echo '<li role="presentation" class="dropdown-header">' . htmlspecialchars( substr( $val['text'], 7 ) ) . '</li>';
		// } else {
		//     echo $this->makeListItem( $key, $val );
		// }


		// title for the rightmost (personal tools) navbar menu
		if ($this->getUser()->isRegistered()) {
			$personaltools_title = $this->getUser()->getName();
		} else {
			// generic 'User' label
			$personaltools_title = $this->msg('listfiles_user');
		}
		$data['data-portlets']['data-personal']['dynamic-label'] = $personaltools_title;


		// content actions/navs
		$contentNavigation = $this->buildContentNavigationUrls();

		$getprimary = function ($from): array {
			return array_filter($from, function ($view) {
				$is_primary = isset($view['primary']) && $view['primary'] === true;
				$is_view_button = $view['id'] === 'ca-view';
				return $is_primary && !$is_view_button;
			});
		};
		$getsecondary = function ($from): array {
			return array_filter($from, function ($view) {
				$is_primary = isset($view['primary']) && $view['primary'] === true;
				return !$is_primary;
			});
		};
		$primary = [];
		$secondary = [];
		// TODO critical: why do the actions not appear?
		foreach (['views', 'actions'] as $name) {
			$primary[$name] = $getprimary($contentNavigation[$name]);
			$secondary[$name] = $getsecondary($contentNavigation[$name]);
		}

		$data['data-portlets']['data-portlets-content-primary'] = [];
		foreach ($primary as $key => $items) {
			$data['data-portlets']['data-portlets-content-primary'] += $this->_getPortletData($key, $items);
		}

		$data['data-portlets']['data-portlets-content-secondary'] = [];
		foreach ($secondary as $key => $items) {
			$data['data-portlets']['data-portlets-content-secondary'] += $this->_getPortletData($key, $items);
		}

		return $data;
	}

	function setupSkinUserCss(OutputPage $out)
	{ // TODO look at gentoo skin and rework this w/o setupSkinUserCss
		// override getDefaultModules?
		$output = $this->getOutput();

		parent::setupSkinUserCss($output);

		$CDN_URL = $this->getConfig()->get('LocalStylePath') . '/Labor';

		//$output->addModuleStyles();
		$output->addStyle($CDN_URL . '/css/bootstrap.min.css');
		$output->addStyle($CDN_URL . '/css/labor.css');
		$output->addStyle($CDN_URL . '/css/flatpickr/flatpickr.min.css');
	}
}
