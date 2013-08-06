<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/view.html.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 jimport('joomla.application.component.view');

 if(version_compare(JVERSION, '3.0', '>')){
	JHtml::_('bootstrap.loadCss');
 }
 else {
	jbimport('moobootstrap');
	JHtml::_('moobootstrap.loadCss');
 }

 $document = JFactory::getDocument();
 $document->addStyleSheet("components/com_jblance/css/style.css");
?>
<?php include_once(JPATH_COMPONENT.'/views/jbmenu.php'); ?>
<?php
/**
 * HTML View class for the Jblance component
 */
class JblanceViewItem extends JViewLegacy {

	function display($tpl = null){
		
		$app  	= JFactory::getApplication();
		$layout = $app->input->get('layout', 'edititem', 'string');
		$model	= $this->getModel();
		$user	= JFactory::getUser();
		
		JblanceHelper::isAuthenticated($user->id, $layout);
		
		if($layout == 'edititem'){
			
		}
		
		
		
        parent::display($tpl);

	}
}