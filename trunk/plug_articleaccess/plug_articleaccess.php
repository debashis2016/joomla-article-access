<?php
/**
* @copyright Copyright (C) 2009 Fiji Web Design. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author gabe@fijiwebdesign.com
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Example Content Plugin
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 		1.5
 */
class plgContentPlug_articleaccess extends JPlugin
{

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param object $params  The object that holds the plugin parameters
	 * @since 1.5
	 */
	function plgContentPlug_articleaccess( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}

	/**
	 * Example prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 */
	function onPrepareContent( &$article, &$params, $limitstart )
	{
		global $mainframe;
		
		if (strpos($article->text, '{/access}') != -1) {
			$article->text = preg_replace_callback('/\{access view=([^\}]+)\}(.*?)\{\/access}/is', array($this, 'replace'), $article->text);
		}
		
		return true;

	}
	
	/**
	 * Make replacements to content
	 * @private
	 * @return String
	 * @param $matches Array
	 */
	function replace($matches) 
	{
		static $usertype;
		static $usertypes;
		static $usertype_id;
		
		if (!$usertypes) {
			$Db =& JFactory::getDBO();
			$query = "SELECT `name` FROM #__core_acl_aro_groups";
			$Db->setQuery($query);
			$_usertypes = $Db->loadRowList();
			$usertypes = array();
			foreach($_usertypes as $_usertype) {
				$usertypes[] = strtolower($_usertype[0]);
			}
		}
		
		if (!$usertype) {
			$user =& JFactory::getUser();
			$usertype = $user->usertype ? strtolower($user->usertype) : 'guest';
			$usertype_id = (int) array_search($usertype, $usertypes);
		}
		
		$_usertypes = array_map('trim', explode(',', $matches[1]));
		$content = $matches[2];
		
		//var_dump($usertype_id, $usertype, $_usertypes, $usertypes);
		
		$view = false;
		foreach($_usertypes as $_usertype) {
			if ($usertype == $_usertype) {
				//var_dump('match 1');
				$view = true;
				break;
			}
			if (strpos($_usertype, '!') === 0) {
				$_usertype = substr($_usertype, 1);
				if ($usertype != $_usertype) {
					//var_dump('match 2');
					$view = true;
					break;
				}
			}
			if (strpos($_usertype, '-') !== false) {
				list($start, $end) = explode('-', $_usertype);
				$start = $start ? array_search(trim($start), $usertypes) : 0;
				$end = $end ? array_search(trim($end), $usertypes) : count($usertypes);
				if ($start <= $usertype_id && $end >= $usertype_id) {
					//var_dump('match 3');
					$view = true;
					break;
				}
			}
		}
		
		if ($view) {
			return $content;
		} else {
			return '';
		}
		
	}
	
}



?>