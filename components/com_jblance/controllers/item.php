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
		//print_r($post['id_category']); exit;
	//	print_r($post); exit;
		if(empty($post['id_category'][0])){

			echo "Please select at least 1 category"; exit;
		} 
		$post['user_id'] = $user->id;
		$post['wishlist'] = 1;
		$item_cat = implode(', ', $post['id_category']);


		$post['id_category'] = $item_cat;
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
		$proj_cat = implode(', ', $post['id_category']);
		


		foreach ($post['checkall-toggle'] as $item_id) {
			$query = "SELECT id_category FROM #__jblance_item WHERE id =".$item_id;
			//echo $query;
			$db->setQuery($query);
 			$id_cat = $db->loadObject();
 			$item_cat[] = $id_cat->id_category;


 			$collection = array();

			foreach($item_cat as $numbers) {
			  $nums = explode(',', $numbers);
			  foreach($nums as $num) {
			    $collection[] = trim($num);
			  }
			}
			$collection = array_unique($collection, SORT_NUMERIC);
			$collection = array_reverse($collection);

 			

		}
		
		$item_cat = implode(',', $collection);

		foreach ($post['checkall-toggle'] as $item_id) {
			$q = "SELECT id_category,MAX(cost) FROM #__jblance_item WHERE id =".$item_id." ";
			//echo $q."<br/>";
			$db->setQuery($q);
			$res = $db->loadObject();

			//print_r($res);

			
		}


		//print_r("."); exit;



		//print_r($item_cat); 
	//	echo count($item_cat);
		//print_r("."); exit;

		foreach ($post['checkall-toggle'] as $item_id) {
			$query = "UPDATE #__jblance_item SET wishlist=0 WHERE id =".$item_id;
			$db->setQuery($query);
			$db->execute();

			
		}
		$item_list = implode(', ', $post['checkall-toggle']);


		$sql = "INSERT INTO #__jblance_project (project_title,id_category,publisher_userid,items) VALUES ('".$order_name."','".$item_cat."',".$user->id.",'".$item_list."')";
		$db->setQuery($sql);
		$db->query();
		$msg	= JText::_('Item successfully added in wishlist').' : '.$row->project_title;
		$return	= JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject', false);
		$this->setRedirect($return, $msg);
		


		// Basic Assignment Engine

		// RULE 1
		// 1) Find category of most expansive item on the list
		// 2) match the category to Boxer who have similar category interest
		// 3) Check if boxer is available for assignment, if yes then get Boxer id
		// and assign to the project

		
	}

	
}

?>