<?php

/**
 * Makes various template data available so that data in 1.36 is available in 1.35.
 */
class SkinMustache35Polyfill extends SkinMustache {
	/**
	 * Get rows that make up the footer
	 * @return array for use in Mustache template describing the footer elements.
	 */
	private function _getFooterTemplateData() : array {
		$data = [];
		foreach ( $this->getFooterLinks() as $category => $links ) {
			$items = [];
			$rowId = "footer-$category";

			foreach ( $links as $key => $link ) {
				// Link may be null. If so don't include it.
				if ( $link ) {
					$items[] = [
						// Monobook uses name rather than id.
						// We may want to change monobook to adhere to the same contract however.
						'name' => $key,
						'id' => "$rowId-$key",
						'html' => $link,
					];
				}
			}

			$data['data-' . $category] = [
				'id' => $rowId,
				'className' => null,
				'array-items' => $items
			];
		}

		// If footer icons are enabled append to the end of the rows
		$footerIcons = $this->getFooterIcons();

		if ( count( $footerIcons ) > 0 ) {
			$icons = [];
			foreach ( $footerIcons as $blockName => $blockIcons ) {
				$html = '';
				foreach ( $blockIcons as $key => $icon ) {
					$html .= $this->makeFooterIcon( $icon );
				}
				// For historic reasons this mimics the `icononly` option
				// for BaseTemplate::getFooterIcons. Empty rows should not be output.
				if ( $html ) {
					$block = htmlspecialchars( $blockName );
					$icons[] = [
						'name' => $block,
						'id' => 'footer-' . $block . 'ico',
						'html' => $html,
					];
				}
			}

			// Empty rows should not be output.
			// This is how Vector has behaved historically but we can revisit later if necessary.
			if ( count( $icons ) > 0 ) {
				$data['data-icons'] = [
					'id' => 'footer-icons',
					'className' => 'noprint',
					'array-items' => $icons,
				];
			}
		}

		return [
			'data-footer' => $data,
		];
	}

	/**
	 * @since 1.36
	 * @stable for overriding
	 * @param string $name of the portal e.g. p-personal the name is personal.
	 * @param array $items that are accepted input to Skin::makeListItem
	 * @return array data that can be passed to a Mustache template that
	 *   represents a single menu.
	 */
	protected function _getPortletData( $name, array $items ) {
		// Monobook and Vector historically render this portal as an element with ID p-cactions
		// This inconsistency is regretful from a code point of view
		// However this ensures compatibility with gadgets.
		// In future we should port p-#cactions to #p-actions and drop this rename.
		if ( $name === 'actions' ) {
			$name = 'cactions';
		}
		$id = Sanitizer::escapeIdForAttribute( "p-$name" );
		$data = [
			'id' => $id,
			'class' => 'mw-portlet ' . Sanitizer::escapeClass( "mw-portlet-$name" ),
			'html-tooltip' => Linker::tooltip( $id ),
			'html-items' => '',
			// Will be populated by SkinAfterPortlet hook.
			'html-after-portal' => '',
		];
		// Run the SkinAfterPortlet
		// hook and if content is added appends it to the html-after-portal
		// for output.
		// Currently in production this supports the wikibase 'edit' link.
		$content = $this->getAfterPortlet( $name );
		if ( $content !== '' ) {
			$data['html-after-portal'] = Html::rawElement(
				'div',
				[
					'class' => [
						'after-portlet',
						Sanitizer::escapeClass( "after-portlet-$name" ),
					],
				],
				$content
			);
		}

		foreach ( $items as $key => $item ) {
			$data['html-items'] .= $this->makeListItem( $key, $item );
		}

		$data['label'] = $this->_getPortletLabel( $name );
		$data['class'] .= ( count( $items ) === 0 && $content === '' )
			? ' emptyPortlet' : '';
		return $data;
	}

	/**
	 * @param string $name of the portal e.g. p-personal the name is personal.
	 * @return string that is human readable corresponding to the menu
	 */
	private function _getPortletLabel( $name ) {
		// For historic reasons for some menu items,
		// there is no language key corresponding with its menu key.
		$mappings = [
			'tb' => 'toolbox',
			'personal' => 'personaltools',
			'lang' => 'otherlanguages',
		];
		$msgObj = $this->msg( $mappings[ $name ] ?? $name );
		// If no message exists fallback to plain text (T252727)
		$labelText = $msgObj->exists() ? $msgObj->text() : $name;
		return $labelText;
	}

	/**
	 * @return array of portlet data for all portlets
	 */
	private function _getPortletsTemplateData() {
		$portlets = [];
		$contentNavigation = $this->buildContentNavigationUrls();
		$sidebar = [];
		$sidebarData = $this->buildSidebar();
		foreach ( $sidebarData as $name => $items ) {
			if ( is_array( $items ) ) {
				// Numeric strings gets an integer when set as key, cast back - T73639
				$name = (string)$name;
				switch ( $name ) {
					// ignore search
					case 'SEARCH':
						break;
					// Map toolbox to `tb` id.
					case 'TOOLBOX':
						$sidebar[] = $this->_getPortletData( 'tb', $items );
						break;
					// Languages is no longer be tied to sidebar
					case 'LANGUAGES':
						// The language portal will be added provided either
						// languages exist or there is a value in html-after-portal
						// for example to show the add language wikidata link (T252800)
						$portal = $this->_getPortletData( 'lang', $items );
						if ( count( $items ) || $portal['html-after-portal'] ) {
							$portlets['data-languages'] = $portal;
						}
						break;
					default:
						$sidebar[] = $this->_getPortletData( $name, $items );
						break;
				}
			}
		}
		foreach ( $contentNavigation as $name => $items ) {
			$portlets['data-' . $name] = $this->_getPortletData( $name, $items );
		}
		$portlets['data-personal'] = $this->_getPortletData( 'personal',
			self::getPersonalToolsForMakeListItem(
				$this->buildPersonalUrls()
			)
		);
		return [
			'data-portlets' => $portlets,
			'data-portlets-sidebar' => [
				'data-portlets-first' => $sidebar[0] ?? null,
				'array-portlets-rest' => array_slice( $sidebar, 1 ),
			],
		];
	}

	/**
	 * @return array of logo data localised to the current language variant
	 */
	private function _getLogoData() : array {
		$logoData = ResourceLoaderSkinModule::getAvailableLogos( $this->getConfig() );
		// check if the logo supports variants
		$variantsLogos = $logoData['variants'] ?? null;
		if ( $variantsLogos ) {
			$preferred = $this->getOutput()->getTitle()
				->getPageViewLanguage()->getCode();
			$variantOverrides = $variantsLogos[$preferred] ?? null;
			// Overrides the logo
			if ( $variantOverrides ) {
				foreach ( $variantOverrides as $key => $val ) {
					$logoData[$key] = $val;
				}
			}
		}
		return $logoData;
	}

	/**
	 * @inheritDoc
	 * Polyfills 1.35
	 */
	function getTemplateData() {
		global $wgVersion;
		$data = parent::getTemplateData();
		if ( version_compare( $wgVersion, '< 1.36') ) {
			$data += [
				'data-logos' => $this->_getLogoData(),
				'link-mainpage' => Title::newMainPage()->getLocalUrl(),
			];

			foreach ( $this->options['messages'] ?? [] as $message ) {
				$data["msg-{$message}"] = $this->msg( $message )->text();
			}
			return $data + $this->_getPortletsTemplateData() + $this->_getFooterTemplateData();
			return $data;
		} else {
			// Nothing to do.
			return $data;
		}
	}
}
