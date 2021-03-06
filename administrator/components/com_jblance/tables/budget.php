<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	28 July 2012
 * @file name	:	tables/budget.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
	
class TableBudget extends JTable {
	
	var $id = null;
	var $title = null;
	var $budgetmin = null;
	var $budgetmax = null;
	var $ordering = null;
	var $published = null;
			
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct('#__jblance_budget', 'id', $db);
	}
}
?>