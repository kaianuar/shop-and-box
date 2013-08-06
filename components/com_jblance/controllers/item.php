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
		$return	= JRoute::_('index.php?option=com_jblance&view=project&layout=showmywishlist', false);
		$this->setRedirect($return, $msg);
	}

}

?>