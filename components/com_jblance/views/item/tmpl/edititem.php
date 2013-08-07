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
	<div class="jbl_h3title">Post New Item</div>
	<?php 
	$lastSubscr = $finance->getLastSubscription($user->id);
	if($lastSubscr->projects_allowed > 0) :
	?>
	<div class="bid_project_left" style="float:right">
	    <div><span class="font26"><?php echo $lastSubscr->projects_left; ?></span>/<span><?php echo $lastSubscr->projects_allowed; ?></span></div>
	    <div><?php echo JText::_('COM_JBLANCE_PROJECTS_LEFT'); ?></div>
	</div>
	<?php endif; ?>
	<fieldset>
		<legend><?php echo JText::_('Item details'); ?></legend>
		<div class="control-group">
			<label class="control-label" for="project_title"><?php echo JText::_('Item Name'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input type="text" class="input-xlarge required" name="item_name" id="item_name" value="<?php echo $this->row->project_title;?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="id_category"><?php echo JText::_('Categories'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<?php 
				$attribs = 'class="input-xlarge required" size="20" multiple ';
				$categtree = $select->getSelectCategoryTree('id_category[]', explode(',', $this->userInfo->id_category), 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
				echo $categtree; ?>
			</div>
		</div> 		
		<!-- <div class="control-group">
			<label class="control-label" for="id_category"><?php echo JText::_('Item Categories'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<?php 
				$attribs = 'class="input-xlarge required"';
				$default = $this->row->id_category;
				echo $select->getSelectCategoryTree('id_categorys[]', $default, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '',true);
				?>
			</div>

		</div>  -->
		
	
		<!-- <div class="control-group">
			<label class="control-label" for="budgetrange"><?php echo JText::_('COM_JBLANCE_BUDGET'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<?php 
				$attribs = 'class="input-xlarge required"';
				$default = $this->row->budgetmin.'-'.$this->row->budgetmax;
				echo $select->getSelectBudgetRange('budgetrange', $default, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '');
				?>
			</div>
		</div>  -->


		<!-- <div class="control-group">
			<label class="control-label" for="description"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<?php echo $editor->display('description', $this->row->description, '100%', '400', '50', '10', false); ?>
			</div>
		</div>  -->
		
		<!-- <div class="control-group">
			<label class="control-label"><?php echo JText::_('COM_JBLANCE_ATTACHMENT'); ?> :</label>
			<div class="controls">
				<?php
				for($i=0; $i < $fileLimitConf; $i++){
				?>
				<input name="uploadFile<?php echo $i;?>" type="file" id="uploadFile<?php echo $i;?>" /><br>
				<?php 
				} ?>
				<input name="uploadLimit" type="hidden" value="<?php echo $fileLimitConf;?>" />
				<?php 
				$tipmsg = JText::_('COM_JBLANCE_ATTACH_FILE').'::'.JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : '.$config->projectFileText.'<br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : '.$config->projectMaxsize.' kB';
				?>
				<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
				<div class="lineseparator"></div>
				<?php 
				foreach($this->projfiles as $projfile){ ?>
				<label class="checkbox">
					<input type="checkbox" name=file-id[] value="<?php echo $projfile->id; ?>" />
  					<?php echo LinkHelper::getDownloadLink('project', $projfile->id, 'project.download'); ?>
				</label>
				<?php	
				}
				?>
			</div>
		</div> -->
	</fieldset>
	
	<?php 
	$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
	
	$parents = array();$children = array();
	//isolate parent and childr
	foreach($this->fields as $ct){
		if($ct->parent == 0)
			$parents[] = $ct;
		else
			$children[] = $ct;
	}
		
	if(count($parents)){
		foreach($parents as $pt){ ?>
	<fieldset>
		<legend><?php echo JText::_($pt->field_title); ?></legend>
			<?php
			foreach($children as $ct){
				if($ct->parent == $pt->id){ ?>
			<div class="control-group">
					<?php
					$labelsuffix = '';
					if($ct->field_type == 'Checkbox') $labelsuffix = '[]'; //added to validate checkbox
					?>
				<label class="control-label" for="custom_field_<?php echo $ct->id.$labelsuffix; ?>"><?php echo JText::_($ct->field_title); ?><span class="redfont"><?php echo ($ct->required)? '*' : ''; ?></span>:</label>
				<div class="controls">
					<?php $fields->getFieldHTML($ct, $this->row->id, 'project'); ?>
				</div>
			</div>
			<?php
				}
			} ?>
	</fieldset>
			<?php
		}
	}
	?>
	
	
	
	<div class="font14 boldfont">
	<?php 
	if($reviewProjects && !$this->row->approved){ ?>
		<div class="jbbox-info"><?php echo JText::_('COM_JBLANCE_PROJECT_WILL_BE_REVIEWED_BY_ADMIN_BEFORE_LIVE'); ?></div>
	<?php 
	}
	?>
	</div>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE_PROJECT'); ?>" class="btn btn-primary"/> 
		<input type="button" value="<?php echo JText::_('COM_JBLANCE_CANCEL'); ?>" onclick="javascript:history.back();" class="btn" />
	</div>
	
	<input type="hidden" name="option" value="com_jblance" /> 
	<input type="hidden" name="task" value="item.saveitem" /> 
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="totalamount" id="totalamount" value="0.00" />
	<?php echo JHTML::_('form.token'); ?>
</form>