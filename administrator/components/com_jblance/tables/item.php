<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	tables/project.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
defined('_JEXEC') or die( 'Restricted access' );

class TableItem extends JTable {
	var $id = null;
	var $item_name = null;
	var $user_id = null;
	var $wishlist = null;
	
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct( '#__jblance_item', 'id', $db);
	}
}
?>