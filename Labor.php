<?php
/**
 * Labor -- the new look of wiki.das-labor.org.  Based on Tyrian from gentoo.org
 * MediaWiki implementation based on MonoBook nouveau.
 *
 * Copyright (C) 2014-2015 Alex Legler <a3li@gentoo.org>
 */

$wgExtensionCredits['skin'][] = array(
	'path' => __FILE__,
	'name' => 'Labor',
	'namemsg' => 'skinname-labor',
	'descriptionmsg' => 'labor-desc',
	'url' => 'https://wiki.das-labor.org/',
	'author' => array('Marcus Brinkmann'),
	'license-name' => 'GPLv2',
);

// Register files
$wgAutoloadClasses['SkinLabor'] = __DIR__ . '/SkinLabor.php';
$wgAutoloadClasses['LaborTemplate'] = __DIR__ . '/LaborTemplate.php';
$wgMessagesDirs['Labor'] = __DIR__ . '/i18n';

// Register skin
$wgValidSkinNames['labor'] = 'Labor';

// Register modules
$wgResourceModules['skins.labor.styles'] = array(
	'styles' => array(
		'main.css' => array('media' => 'screen'),
	),
	'remoteSkinPath' => 'Labor',
	'localBasePath' => __DIR__,
);

$wgHooks['OutputPageBeforeHTML'][] = 'injectMetaTags';

function injectMetaTags($out) {
	$out->addMeta('viewport', 'width=device-width, initial-scale=1.0');
	$out->addMeta('theme-color', '#54487a');
	return true;
}
