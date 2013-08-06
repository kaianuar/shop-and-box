<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/tmpl/showmyproject.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	List of projects posted by the user (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.modal', 'a.jb-modal');
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/mooboomodal.js");
 $doc->addScript("components/com_jblance/js/jbmodal.js");

 $model				  = $this->getModel();
 $config 			  = JblanceHelper::getConfig();

 $showUsername 		  = $config->showUsername;
 $nameOrUsername 	  = ($showUsername) ? 'username' : 'name';
 
 JText::script('COM_JBLANCE_CLOSE');
 JText::script('COM_JBLANCE_YES');
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_MY_WISHLISTS'); ?></div>
	<div id="no-more-tables">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th><?php echo JText::_('#'); ?></th>
				<th>Wishlist Items</th>
				
				<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
				
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6">
					<div class="pagination">
						
					</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
		$k = 0;
		$n=count($this->rows);
		for ($i=0;  $i < $n; $i++) {
			
			$row = $this->rows[$i];
		$link_makeorder = JRoute::_('index.php?option=com_jblance&task=project.convertwishlist&id='.$row->id.'&'.JSession::getFormToken().'=1');
		$link_del = JRoute::_('index.php?option=com_jblance&task=project.removeproject&id='.$row->id.'&'.JSession::getFormToken().'=1');

		} 
		?>
			<tr>
				<td data-title="<?php echo JText::_('#'); ?>">1</td>
			
			
				<td data-title="<?php echo JText::_('#'); ?>"><a><?php echo $row->item_name;?></a></td>

				<td data-title="<?php echo JText::_('COM_JBLANCE_ACTION'); ?>">
					<?php
						if($row->wishlist == 1){ ?>
							<a href="javascript:void(0);" onclick="javascript:modalConfirm('<?php echo JText::_('Confirm order?', true); ?>', '<?php echo JText::_('Are you sure make this item as an order?', true); ?>', '<?php echo $link_makeorder; ?>');" ><?php echo JText::_('Convert to order'); ?></a> /
							<a href="javascript:void(0);" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_DELETE', true); ?>', '<?php echo JText::_('COM_JBLANCE_CONFIRM_DELETE_PROJECT', true); ?>', '<?php echo $link_del; ?>');" ><?php echo JText::_('COM_JBLANCE_DELETE'); ?></a>
					<?php } ?>

				</td>
				
			</tr>
		
			
		</tbody>
		
	</table>
	</div>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="" />	
</form>