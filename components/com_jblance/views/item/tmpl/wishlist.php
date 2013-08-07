<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/tmpl/editproject.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Post / Edit project (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.modal', 'a.jb-modal');
 JHTML::_('behavior.tooltip');
 
 JblanceHelper::getMultiSelect('id_category', JText::_('COM_JBLANCE_SEARCH_SKILLS'));

 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 $finance = JblanceHelper::get('helper.finance');		// create an instance of the class FinanceHelper
 $editor = JFactory::getEditor();
 $user = JFactory::getUser();

 $config = JblanceHelper::getConfig();


 $isNew = ($this->row->id == 0) ? 1 : 0;
 $title = $isNew ? JText::_('COM_JBLANCE_POST_NEW_PROJECT') : JText::_('COM_JBLANCE_EDIT_PROJECT');

 //get the project upgrade amounts based on the plan
 
 
 JText::script('COM_JBLANCE_CLOSE');
 
 $ndaFile = JURI::root().'components/com_jblance/images/nda.txt';
?>


<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userFormProject" id="userFormProject" class="form-validate form-horizontal" onsubmit="return validateForm(this);" enctype="multipart/form-data">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_MY_WISHLISTS'); ?></div>
	
	
	
	
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
			<tr>
				<td>
				<div class="control-group">
		<label class="control-label" for="jform_title"><?php echo JText::_('Order Name'); ?>:</label>
		<div class="controls">
			<input type="text" class="inputbox required" name="project_title" id="jform_title" value="<?php echo $this->row->title; ?>" />
		</div>
	</div>
</td></tr>

		</tfoot>
		<tbody>
			<?php
		$k = 0;
		$n=count($this->rows);
		for ($i=0;  $i < $n; $i++) {
			$row = $this->rows[$i];
		
		?>
			<tr>
				<td data-title="<?php echo JText::_('#'); ?>">1</td>
			
			
				<td data-title="<?php echo JText::_('#'); ?>"><a><?php echo $row->item_name;?></a></td>

				<td data-title="<?php echo JText::_('COM_JBLANCE_ACTION'); ?>">

					<?php
						if($row->wishlist == 1)
							
							{ ?>
						
							<div class="control-group">
									<label class="control-label">Convert to Order</label>
								<div class="controls">
									<input type="checkbox" class="radio" name="checkall-toggle[]" value="<?php echo $row->id;?>" title="<?php echo JText::_('Check me'); ?>"  /> 
									
								</div>
							</div>
							
							


					<?php }?>

				</td>

				<?php
			$k = 1 - $k;
		}
		?>

		</tbody>

	</table>	
	
	
	
	
	
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('Add Order'); ?>" class="btn btn-primary"/> 
		<input type="button" value="<?php echo JText::_('COM_JBLANCE_CANCEL'); ?>" onclick="javascript:history.back();" class="btn" />
	</div>
	
	<input type="hidden" name="option" value="com_jblance" /> 
	<input type="hidden" name="task" value="item.convertitem" /> 
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="totalamount" id="totalamount" value="0.00" />
	<?php echo JHTML::_('form.token'); ?>
</form>