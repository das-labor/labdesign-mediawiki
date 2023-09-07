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
class SkinLabor extends SkinMustache
{
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

		# split out the toolbox portlet, so it can be rendered separately
		$nav_portlets = $data['data-portlets-sidebar']['array-portlets-rest'];
		$toolbox = array_values(array_filter($nav_portlets, function ($portlet) {
			return $portlet['id'] === 'p-tb';
		}))[0];
		$data['data-portlets-sidebar']['data-portlets-toolbox'] = $toolbox;
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

		return $data;
	}

	// modify menus before they're rendered as portlets
	public static function onSkinTemplateNavigationUniversal(SkinTemplate $sktemplate, array &$links) {
		// fix weird display of anonuserpage placeholder by making it a "link"
		if (isset($links['user-menu']['anonuserpage'])) {
			$links['user-menu']['anonuserpage']['href'] = '#';
			$links['user-menu']['anonuserpage']['active'] = false;
		}

		// sort content actions and views into primary/secondary for a cleaner look
		MWDebug::log(json_encode($links));
		$getprimary = function ($from): array {
			return array_filter($from, function ($view) {
				$is_primary = isset($view['primary']) && $view['primary'] === true;
				$is_redundant = isset($view['redundant']) && $view['redundant'] === true;
				return $is_primary && !$is_redundant;
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
		// don't really need to filter actions, they're all not marked primary.
		// ¯\_(ツ)_/¯
		foreach (['views', 'actions'] as $name) {
			$primary[$name] = $getprimary($links[$name]);
			$secondary[$name] = $getsecondary($links[$name]);
		}

		$links['portlets-content-primary'] = [];
		foreach ($primary as $name => $menu) {
			$links['portlets-content-primary'] += $menu;
		}

		$links['portlets-content-secondary'] = [];
		foreach ($secondary as $name => $menu) {
			$links['portlets-content-secondary'] += $menu;
		}

		// extensions' hooks run after this skin, and add their stuff in here.
		// to make them show up at all, we need to use the original portlet in our template
		// this also means we have no control over what they add where, unfortunately
		$links['views'] = $links['portlets-content-primary'];
		// this also means we're missing out on any added actions unfortunately...
	}
}
