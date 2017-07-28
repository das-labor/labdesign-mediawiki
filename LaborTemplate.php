<?php
/**
 * Labor -- the new look of wiki.das-labor.org
 * MediaWiki implementation based on MonoBook nouveau.
 *
 * Copyright (C) 2014-2016 Alex Legler <a3li@gentoo.org>
 */
class LaborTemplate extends BaseTemplate {
	function execute() {
		wfSuppressWarnings();

		$this->html( 'headelement' );

		$this->header();
		?>

		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div id="content" class="mw-body" role="main">
						<a id="top"></a>
						<?php if ( $this->data['sitenotice'] ) { ?>
							<div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
						<?php	}	?>

						<h1 id="firstHeading" class="first-header" lang="<?php $this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode(); $this->text( 'pageLanguage' );	?>">
							<span dir="auto"><?php $this->html( 'title' ) ?></span>
						</h1>

						<div id="bodyContent" class="mw-body-content">
							<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
							<div id="contentSub"<?php	$this->html( 'userlangattributes' ) ?>>
								<?php $this->html( 'subtitle' )	?>
							</div>
							<?php if ( $this->data['undelete'] ) { ?>
								<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
							<?php } ?>
							<?php	if ( $this->data['newtalk'] ) {	?>
								<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
							<?php	}	?>
							<div id="jump-to-nav" class="mw-jump">
								<?php	$this->msg( 'jumpto' ) ?>
								<a href="#column-one"><?php	$this->msg( 'jumptonavigation' ) ?></a><?php $this->msg( 'comma-separator' ) ?>
								<a href="#searchInput"><?php $this->msg( 'jumptosearch' )	?></a>
							</div>

							<!-- start content -->
							<?php $this->html( 'bodytext' ) ?>
							<?php	if ( $this->data['catlinks'] ) { ?>
								<?php $this->html( 'catlinks' ); ?>
							<?php }	?>
							<!-- end content -->

							<?php	if ( $this->data['dataAfterContent'] ) {
								$this->html( 'dataAfterContent'	);
							}	?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php $this->footer(); ?>

                <script src="<?php $this->text('stylepath') ?>/Labor/js/jquery-2.2.4.min.js"></script>
		<script src="<?php $this->text('stylepath') ?>/Labor/js/bootstrap.min.js"></script>
		<script src="<?php $this->text('stylepath') ?>/Labor/js/flatpickr/flatpickr.min.js"></script>
		<script>
			$(".make-me-a-date-time-picker").flatpickr({
					 enableTime: true,
					 dateFormat: "Y/m/d H:i:S",
					 time_24hr: true
			});
			$(".make-me-a-date-picker").flatpickr({
					 dateFormat: "Y/m/d"
			});
		</script>

		<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
		wfRestoreWarnings();
	} // end of execute() method

	/*************************************************************************************************/

	function header() {
	?>
	<div class="mw-jump sr-only">
		<?php	$this->msg( 'jumpto' ) ?>
		<a href="#top">content</a>
	</div>
	<header>
		<div class="site-title">
			<div class="container">
				<div class="row">
					<div class="site-title-buttons">
						<a href="/w/Status-Bot"><span class="room_status"></span></a>
						<div class="btn-group btn-group-md">
							<div class="btn-group btn-group-md">
								<a class="btn gentoo-org-sites dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
									<span class="fa fa-fw fa-map-o"></span> <span class="hidden-xs">Labor e.V. Webseiten</span> <span class="caret"></span>
								</a>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="https://github.com/das-labor/" title="Software, Vorlagen, Archive"><span class="fa fa-code fa-fw"></span> Github/Software</a></li>
									<li><a href="https://www.flickr.com/groups/daslabor/" title="Fotos"><span class="fa fa-picture-o fa-fw"></span> Fotos auf Flickr</a></li>
									<li><a href="https://das-labor.org/mailman/listinfo/announce" title="Ank�ndigungen für Veranstaltungen"><span class="fa fa-envelope fa-fw"></span> eMail-Newsletter</a></li>
                                                                        <li><a href="https://twitter.com/dasLabor" title="Twitter"><span class="fa fa-twitter fa-fw"></span> Twitter</a></li>
                                                                        <li><a href="https://chaos.social/@daslabor" title="Mastodon"><span class="fa fa-heart fa-fw"></span> Mastodon</a></li>
                                                                        <li><a href="https://ruhrspora.de/u/daslabor" title="Diaspora*"><span class="fa fa-asterisk fa-fw"></span> Diaspora*</a></li>

									<li class="divider"></li>
									<li><a href="https://chaos-west.de/wiki/index.php?title=Hauptseite" title="Chaos-West (Partner)"><span class="fa fa-group fa-fw"></span> Chaos-West</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="logo">
						<a href="/" title="Zurück zur Hauptseite" class="site-logo"><a href="" alt="Labor Logo"></a>
						<!-- <span class="site-label">das labor</span> -->
						<span class="laborlogo laborlogomain">das la<span class="laborlogohigh">bo</span>r</span>
					</div>
				</div>
			</div>
		</div>
		<nav class="tyrian-navbar" role="navigation">
			<div class="container">
				<div class="row">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<div class="collapse navbar-collapse navbar-main-collapse">
						<ul class="nav navbar-nav">
							<?php
							$this->renderPortals( $this->data['sidebar'] );
							?>
						</ul>
						<ul class="nav navbar-nav navbar-right hidden-xs">
							<?php
							$this->toolbox();
							$this->personaltools();
							?>
						</ul>
					</div>
				</div>
			</div>
		</nav>
		<?php $this->cactions(); ?>
	</header>
	<?php
	}

	function footer() {
		$validFooterIcons = $this->getFooterIcons( "icononly" );
		$validFooterLinks = $this->getFooterLinks( "flat" ); // Additional footer links
	?>
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php if ( count( $validFooterLinks ) > 0 ) { ?>
						<div class="spacer"></div>
            <table id="footer-table">
              <tr>
							  <?php foreach ( $validFooterLinks as $aLink ) { ?>
								  <?php if ($aLink === 'copyright') continue; ?>
								  <td id="<?php echo $aLink ?>" class="footer-item"><?php $this->html( $aLink ) ?></td>
                <?php } ?>
              </tr>
						</table>
					<?php } ?>
				</div>
				<div class="col-xs-12 col-md-3">
					<!-- No questions or comments, the Wiki has enough information on how to contact us. -->
				</div>
			</div>
	<!--		<div class="row">
				<div class="copyright c12">
					Creative Commons: Namensnennung Deutschland 3.0; 2015
					<a href="https://www.das-labor.org/?lang=de">das labor</a>. Einige Rechte vorbehalten.
					<a href="https://www.das-labor.org/?page_id=1679">Impressum</a>,
					<a href="https://www.das-labor.org/?page_id=1723">Datenschutz</a>
				</div>
			</div>
	-->
		</div>
	</footer>
	<?php
		echo $footerEnd;
	}

	/**
	 * @param array $sidebar
	 */
	protected function renderPortals( $sidebar ) {
		// These are already rendered elsewhere
		$sidebar['SEARCH'] = false;
		$sidebar['TOOLBOX'] = false;
		$sidebar['LANGUAGES'] = false;

		foreach ( $sidebar as $boxName => $content ) {
			if ( $content === false ) {
				continue;
			}

			$this->customBox( $boxName, $content );
		}
	}

	function searchBox() {
		?>
		<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform" class="navbar-form navbar-right" role="search">
			<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>

				<div class="input-group">
					<?php echo $this->makeSearchInput( array( "id" => "searchInput", "class" => "form-control", "placeholder" => $this->getMsg( 'search' )->escaped() ) ); ?>
					<div class="input-group-btn"><?php
						echo $this->makeSearchButton(
						"go",
						array( "id" => "searchGoButton", "class" => "searchButton btn btn-default" )
					);

					echo $this->makeSearchButton(
						"fulltext",
						array( "id" => "mw-searchButton", "class" => "searchButton btn btn-default" )
					);
				?></div>
				</div>
		</form>
	<?php
	}

	function cactions() {
		$context_actions = array();
		$primary_actions = array();
		$secondary_actions = array();

		foreach ( $this->data['content_actions'] as $key => $tab ) {
			// TODO: handling form_edit separately here is a hack, no idea how it works in Vector.
			if ( isset($tab['primary']) && $tab['primary'] == '1' || $key == 'form_edit' ) {
				if ( isset($tab['context']) ) {
					$context_actions[$key] = $tab;

					if ( strpos( $tab['class'], 'selected' ) !== false ) {
						$context_actions[$key]['class'] .= ' active';
					}
				} else {
					$primary_actions[$key] = $tab;

					if ( strpos( $tab['class'], 'selected' ) !== false ) {
						$primary_actions[$key]['class'] .= ' active';
					}
				}
			} else {
				$secondary_actions[$key] = $tab;

				if ( strpos( $tab['class'], 'selected' ) !== false ) {
					$secondary_actions[$key]['class'] .= ' active';
				}
			}
		}
		?>

		<nav class="navbar navbar-grey navbar-stick" id="wiki-actions" role="navigation">
			<div class="container"><div class="row">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#gw-toolbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="gw-toolbar">
					<ul class="nav navbar-nav">
					<?php
						foreach ( $context_actions as $key => $tab ) {
							echo $this->makeListItem( $key, $tab );
						}
					?>
					</ul>
					<?php
						$this->searchBox();
					?>
					<ul class="nav navbar-nav navbar-right hidden-xs">
					<?php
						foreach ( $primary_actions as $key => $tab ) {
							echo $this->makeListItem( $key, $tab );
						}
					?><li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">more <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
					<?php
						foreach ( $secondary_actions as $key => $tab ) {
							echo $this->makeListItem( $key, $tab );
						}
					?>
							</ul>
						</li>
					</ul>
				</div>
			</div></div>
		</nav>
	<?php
	}

	/*************************************************************************************************/
	function toolbox() {
		?>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-cog"></i> <?php $this->msg( 'toolbox' ) ?> <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu">
				<?php
					foreach ( $this->getToolbox() as $key => $tbitem) {
						echo $this->makeListItem( $key, $tbitem );
					}

					wfRunHooks( 'MonoBookTemplateToolboxEnd', array( &$this ) );
					wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this, true ) );
				?>
			</ul>
		</li>
	<?php
	}

	/*************************************************************************************************/
	function personaltools() {
		$personal_tools = $this->getPersonalTools();
		$notifications_message_tool = NULL;
		$notifications_alert_tool = NULL;

		?>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				<span class="fa fa-fw fa-user" aria-label="<?php $this->msg( 'personaltools' ) ?>"></span>
				<?php
					if (isset($personal_tools['userpage'])) {
						echo $personal_tools['userpage']['links'][0]['text'];
					} else {
						$this->msg( 'listfiles_user' );
					} ?>
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<?php
					foreach ( $personal_tools as $key => $item ) {
						if ($key === 'notifications-alert') {
							$notifications_alert_tool = $item;
						} else if ($key === 'notifications-message') {
							$notifications_message_tool = $item;
						} else {
							echo $this->makeListItem( $key, $item );
						}
					}
				?>
			</ul>
		</li>
		<?php

		if (!is_null($notifications_message_tool)) {
			echo $this->makeListItem('notifications-message', $notifications_message_tool);
		}

		if (!is_null($notifications_alert_tool)) {
			echo $this->makeListItem('notifications-alert', $notifications_alert_tool);
		}
	}

	/*************************************************************************************************/
	function languageBox() {
		if ( $this->data['language_urls'] !== false ) {
			?>
			<div id="p-lang" class="portlet" role="navigation">
				<h3<?php $this->html( 'userlangattributes' ) ?>><?php $this->msg( 'otherlanguages' ) ?></h3>

				<div class="pBody">
					<ul>
						<?php foreach ( $this->data['language_urls'] as $key => $langlink ) { ?>
							<?php echo $this->makeListItem( $key, $langlink ); ?>
						<?php
							}
						?>
					</ul>

					<?php $this->renderAfterPortlet( 'lang' ); ?>
				</div>
			</div>
		<?php
		}
	}

	/*************************************************************************************************/
	/**
	 * @param string $bar
	 * @param array|string $cont
	 */
	function customBox( $bar, $cont ) {
		$msgObj = wfMessage( $bar );

		if ( $bar !== 'navigation' ) { ?>
			<li class="dropdown">
				<a href="/wiki/Gentoo_Wiki:Menu-<?php echo htmlspecialchars( $bar ) ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $bar ); ?> <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
		<?php }

		if ( is_array ( $cont ) ) {
			foreach ( $cont as $key => $val ) {
				if ( $val['text'] === '---' ) {
					echo '<li role="presentation" class="divider"></li>';
				} elseif ( substr( $val['text'], 0, 7 ) === 'header:' ) {
					echo '<li role="presentation" class="dropdown-header">' . htmlspecialchars( substr( $val['text'], 7 ) ) . '</li>';
				} else {
					echo $this->makeListItem( $key, $val );
				}
			}
		} else {
			echo "<!-- This would have been a box, but it contains custom HTML which is not supported. -->";
		}

		if ( $bar !== 'navigation' ) { ?>
				</ul>
			</li>
		<?php }
	}
}
