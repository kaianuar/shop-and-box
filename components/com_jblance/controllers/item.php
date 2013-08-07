<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	controllers/project.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.controller');

class JblanceControllerItem extends JControllerLegacy {

	function __construct(){
		parent :: __construct();
	}

	function saveItem(){
		$app  	= JFactory::getApplication();
		$user 	= JFactory::getUser();
		$row	= JTable::getInstance('item', 'Table');
		$post   = JRequest::get('POST');

		
		$post['user_id'] = $user->id;
		$post['wishlist'] = 1;

		//print_r($post); exit;
		$id		= $app->input->get('id', 0, 'int');
		$db 	= JFactory::getDBO();
		$jbmail = JblanceHelper::get('helper.email');	

		if(!$row->save($post)){
		 	JError::raiseError(500, $row->getError());
		 }
		//echo "Item save here"; exit;
		$msg	= JText::_('Item successfully added in wishlist').' : '.$row->project_title;
		$return	= JRoute::_('index.php?option=com_jblance&view=item&layout=wishlist', false);
		$this->setRedirect($return, $msg);
	}

	function convertItem(){
		$post   = JRequest::get('POST');
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$id 	= $app->input->get('id', 0, 'int');
		$order_name = $post['project_title'];


		//echo $order_name; exit;

		foreach ($post['checkall-toggle'] as $item_id) {
			$query = "UPDATE #__jblance_item SET wishlist=0 WHERE id =".$item_id;
			$db->setQuery($query);
			$db->execute();

			
		}
		$item_list = implode(', ', $post['checkall-toggle']);


		$sql = "INSERT INTO #__jblance_project (project_title,publisher_userid,items) VALUES ('".$order_name."',".$user->id.",'".$item_list."')";
		$db->setQuery($sql);
		$db->query();
		$msg	= JText::_('Item successfully added in wishlist').' : '.$row->project_title;
		$return	= JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject', false);
		$this->setRedirect($return, $msg);
		
		
	}

	
}

?>