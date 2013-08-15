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
class Piwik_WhoisLive_Controller extends Piwik_Controller
{	

	public function __construct()
	{
		parent::__construct();
		$this->idSite = Piwik_Common::getRequestVar('idSite');
		$this->minIdVisit = Piwik_Common::getRequestVar('minIdVisit', 0, 'int');
		$this->unknownOnly = Piwik_Common::getRequestVar('unknownOnly', 0, 'int') == 1;
	}

	public function index()
	{
		$this->widget(true);
	}

	public function widget( $fetch = false )
	{
		$view = Piwik_View::factory('index');
		$this->setGeneralVariablesView($view);
		$view->visits = $this->getLastVisits( $this->idSite, 30, null, $this->unknownOnly );
		$view->unknownOnly = $this->unknownOnly;
		if( $fetch ) return $view->render();
		else echo $view->render();
	}

	public function getLastVisits( $idSite = null, $limit = 30, $minIdVisit = null, $unknownOnly = true )
	{
		require_once PIWIK_INCLUDE_PATH . '/plugins/UserSettings/functions.php';
		require_once PIWIK_INCLUDE_PATH . '/plugins/Referers/functions.php';

		if( is_null($idSite) ) Piwik::checkUserIsSuperUser();
		else Piwik::checkUserHasViewAccess( $idSite );
		$visitors = $this->loadLastVisitorDetailsFromDatabase( null, $idSite, $limit, $minIdVisit, $unknownOnly );
		foreach( $visitors as $key => $row ) {
			$row['ip'] = long2ip( $row['location_ip'] );

//			$row['location_provider'] = Piwik_( $row['location_provider'] );
//			$row['location_geoip_continent'] = Piwik_( $row['location_geoip_continent'] );
//			$row['location_geoip_country'] = Piwik_( $row['location_geoip_country'] );

			$row['config_os_logo'] = Piwik_getOSLogo( $row['config_os'] );
			$row['config_os'] = Piwik_getOSLabel( $row['config_os'] );

			$browser = $row['config_browser_name'] . ";" . $row['config_browser_version'];
			$row['config_browser_logo'] = Piwik_getBrowsersLogo( $browser );
			$row['config_browser_name'] = Piwik_getBrowserLabel( $browser );

			if( $row['referer_type']==2 ) {
				$se_url = Piwik_getSearchEngineUrlFromName( $row['referer_name'] );
				$row['referer_logo'] = Piwik_getSearchEngineLogoFromUrl( $se_url );
			}
			$visitors[$key] = $row;
		}
		return $visitors;
	}

	private function loadLastVisitorDetailsFromDatabase( $visitorId = null, $idSite = null, $limit = null, $minIdVisit = null, $unknownOnly = true )
	{
		$where = $whereBind = array();
		if(!is_null($idSite))
		{
			$where[] = "idsite = ? ";
			$whereBind[] = $idSite;
		}
		if(!is_null($visitorId))
		{
			$where[] = "visitor_idcookie = ? ";
			$whereBind[] = $visitorId;
		}
		if(!is_null($minIdVisit))
		{
			$where[] = "idvisit > ? ";
			$whereBind[] = $minIdVisit;
		}
		if($unknownOnly)
		{
			$where[] = "location_provider='Ip'";
		}
		$sqlWhere = "";
		if(count($where) > 0)
		{
			$sqlWhere = " WHERE " . join(' AND ', $where);
		}
		$sql = "SELECT * FROM " . Piwik_Common::prefixTable('log_visit') . " $sqlWhere ORDER BY idvisit DESC LIMIT $limit";
		return Piwik_FetchAll($sql, $whereBind);
	}


	public function getWhoisFromIp( $fetch = false )
	{
		$out = array();
		$ip = Piwik_Common::getRequestVar('ip');
		exec( 'whois ' . escapeshellarg($ip), $out );
		$out = htmlspecialchars( implode( "\n", $out ) );
		if( $fetch ) return $out;
		else echo $out;
	}

}
