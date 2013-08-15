<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: $
 * 
 * @category Piwik_Plugins
 * @package Piwik_WhoisLive
 */

/**
 *
 * @package Piwik_WhoisLive
 */
class Piwik_WhoisLive extends Piwik_Plugin
{
	public function getInformation()
	{
		return array(
			'name' => 'WhoisLive',
			'description' => Piwik_Translate('WhoisLive_PluginDescription'),
			'author' => 'Robert Frunzke',
			'author_homepage' => 'http://manjadigital.de/',
			'version' => '0.2',
			'translationAvailable' => true,
		);
	}

	function postLoad()
	{
		Piwik_AddWidget('WhoisLive_widgets', 'WhoisLive_widget', 'WhoisLive', 'widget');
	}

}

