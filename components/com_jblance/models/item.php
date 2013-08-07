<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	models/project.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.component.model');
 
 class JblanceModelItem extends JModelLegacy {


 	function getshowMyWishlist(){
 		$app  = JFactory::getApplication();
 		$db	  = JFactory::getDBO();
 		$user = JFactory::getUser();
 		
 		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->input->get('limitstart', 0, 'int');
 	    $query = 'SELECT * FROM #__jblance_item p WHERE p.user_id='.$user->id.' AND p.wishlist=1 ORDER BY p.id DESC';

 	    //echo $query;
 		

 	    
 		$db->setQuery($query);
 		$db->execute();
 		$total = $db->getNumRows();
 		


 		jimport('joomla.html.pagination');
 		$pageNav = new JPagination($total, $limitstart, $limit);
 		
 		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
 		$rows = $db->loadObjectList();

 		
 		$return[0] = $rows;
 		$return[1] = $pageNav;
 		return $return;
 	}


 }

 ?>