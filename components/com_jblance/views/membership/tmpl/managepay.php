<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	17 April 2012
 * @file name	:	views/membership/tmpl/managepay.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Manage deposits, withdrawals and Escrow payments (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.modal', 'a.jb-modal');
 
 $model 			  = $this->getModel();
 $config 			  = JblanceHelper::getConfig();
 $dformat 			  = $config->dateFormat;
 $currencysym 		  = $config->currencySymbol;
 $enableEscrowPayment = $config->enableEscrowPayment;
 $enableWithdrawFund  = $config->enableWithdrawFund;
 $showUsername = $config->showUsername;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';
 
 $action	= JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay');
?>
<form action="<?php echo $action; ?>" method="post" name="userFormJob" enctype="multipart/form-data">	
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_MANAGE_PAYMENTS'); ?></div>
	<?php 
	echo JHtml::_('tabs.start', 'panel-tabs', array('useCookie'=>'1'));
	$countEscrowOut = $model->countManagePayPending('escrowout');
	$newTitle = ($countEscrowOut > 0) ? ' <span class="redfont">(<b>'.$countEscrowOut.'</b>)</span>' : '';
	
	//check if escrow is enabled
	if($enableEscrowPayment){
		echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_OUTGOING_ESCROW_PAYMENTS').$newTitle, 'escrowout'); ?>
	<?php 
	if(count($this->escrow_out)) : ?>
	<div id="no-more-tables">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th><?php echo JText::_('#'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_DATE'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_RECEIVER'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_PROJECT'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_AMOUNT').' ('.$currencysym.')'; ?></th>	
				<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for($i=0, $n=count($this->escrow_out); $i < $n; $i++){
			$escout 		= $this->escrow_out[$i];
			$link_release 	= JRoute::_('index.php?option=com_jblance&task=membership.releaseescrow&id='.$escout->id.'&'.JSession::getFormToken().'=1');
			//$link_cancel 	= JRoute::_('index.php?option=com_jblance&task=membership.cancelescrow&id='.$escout->id.'&'.JSession::getFormToken().'=1');
		?>
			<tr>
				<td data-title="<?php echo JText::_('#'); ?>">
					<?php echo $i+1;?>
				</td>
				<td nowrap="nowrap" data-title="<?php echo JText::_('COM_JBLANCE_DATE'); ?>">
					<?php  echo JHTML::_('date', $escout->date_transfer, $dformat); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_RECEIVER'); ?>">
					<?php 
					$receiver = JFactory::getUser($escout->to_id);
					echo $receiver->$nameOrUsername;			
					?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_PROJECT'); ?>">
					<?php  echo ($escout->project_title) ? $escout->project_title : JText::_('COM_JBLANCE_NA'); ?>
				</td>
				<td class="text-center" data-title="<?php echo JText::_('COM_JBLANCE_AMOUNT'); ?>">
					<?php  echo JblanceHelper::formatCurrency($escout->amount, false); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_ACTION'); ?>">
					<?php if($escout->status == '') : ?>
					<a href="<?php echo  $link_release; ?>"><?php echo JText::_('COM_JBLANCE_RELEASE'); ?></a>
					<!-- <a href="<?php echo  $link_cancel; ?>"><?php echo JText::_('COM_JBLANCE_CANCEL'); ?></a> -->
					<?php endif; ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_STATUS'); ?>">
					<?php
					echo ($escout->status == '') ? JText::_('COM_JBLANCE_PENDING'): JText::_($escout->status);
					?>
				</td>
			</tr>
		<?php 
			$k = 1 - $k;
		} ?>
		</tbody>
	</table>
	</div>
	<?php 
		else : 
			echo JText::_('COM_JBLANCE_NO_PENDING_PAYMENTS_FOUND');
		endif;	//end of count escrow
	}		//end of escrow enabled
	?>
	<?php
	$countEscrowIn = $model->countManagePayPending('escrowin');
	$newTitle = ($countEscrowIn > 0) ? ' <span class="redfont">(<b>'.$countEscrowIn.'</b>)</span>' : '';
	//check if escrow is enabled
	if($enableEscrowPayment){
		echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_INCOMING_ESCROW_PAYMENTS').$newTitle, 'escrowin'); ?>
	<?php
	if(count($this->escrow_in)) : ?>
	<div id="no-more-tables">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo JText::_('COM_JBLANCE_DATE'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_SENDER'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_PROJECT'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_AMOUNT').' ('.$currencysym.')'; ?></th>
				<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php
	$k = 0;
	for($i=0, $n=count($this->escrow_in); $i < $n; $i++){
	$escin 		= $this->escrow_in[$i];
	$link_accept 	= JRoute::_('index.php?option=com_jblance&task=membership.acceptescrow&id='.$escin->id.'&'.JSession::getFormToken().'=1');
	?>
			<tr>
				<td data-title="#">
					<?php echo $i+1; ?>
				</td>
				<td nowrap="nowrap" data-title="<?php echo JText::_('COM_JBLANCE_DATE'); ?>">
					<?php  echo JHTML::_('date', $escin->date_transfer, $dformat); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_SENDER'); ?>">
					<?php
					$sender = JFactory::getUser($escin->from_id);
					echo $sender->$nameOrUsername;
					?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_PROJECT'); ?>">
					<?php  echo ($escin->project_title) ? $escin->project_title : JText::_('COM_JBLANCE_NA'); ?>
				</td>
				<td class="text-right" data-title="<?php echo JText::_('COM_JBLANCE_AMOUNT'); ?>">
					<?php  echo JblanceHelper::formatCurrency($escin->amount, false); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_ACTION'); ?>">
					<?php if($escin->status == 'COM_JBLANCE_RELEASED') : ?>
					<a href="<?php echo  $link_accept; ?>"><?php echo JText::_('COM_JBLANCE_ACCEPT'); ?></a>
					<?php endif; ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_STATUS'); ?>">
					<?php
					echo ($escin->status == '') ? JText::_('COM_JBLANCE_PENDING'): JText::_($escin->status);
					?>
				</td>
			</tr>
	<?php
	$k = 1 - $k;
	} ?>
		</tbody>
	</table>
	</div>
	<?php
		else :
			echo JText::_('COM_JBLANCE_NO_PENDING_PAYMENTS_FOUND');
		endif;	//end of escrow count
	}		//end of escrow enabled
	?>
	<?php
	$countWithdraw = $model->countManagePayPending('withdraw');
	$newTitle = ($countWithdraw > 0) ? ' <span class="redfont">(<b>'.$countWithdraw.'</b>)</span>' : '';
	
	//check if withdraw fund is enabled
	if($enableWithdrawFund){
		echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_WITHDRAWALS').$newTitle, 'withdrawals'); ?>
	<?php
	if(count($this->withdraws)) : ?>
	<div id="no-more-tables">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo JText::_('COM_JBLANCE_REQUESTED_AT'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_AMOUNT').' ('.$currencysym.')'; ?></th>
				<th><?php echo JText::_('COM_JBLANCE_WITHDRAWAL_FEE').' ('.$currencysym.')'; ?></th>
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<div class="pagination">
					<?php echo $this->pageNavWithdraw->getListFooter(); ?>
					</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
	<?php
	$k = 0;
	for($i=0, $n=count($this->withdraws); $i < $n; $i++){
		$withdraw 		= $this->withdraws[$i];
		$link_invoice =  JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$withdraw->id.'&tmpl=component&print=1&type=withdraw');
	?>
			<tr>
				<td data-title="#">
					<?php echo $i+1;?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_REQUESTED_AT'); ?>">
					<?php  echo JHTML::_('date', $withdraw->date_withdraw, $dformat); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_AMOUNT'); ?>" class="text-right">
					<?php  echo JblanceHelper::formatCurrency($withdraw->amount, false); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_WITHDRAWAL_FEE'); ?>" class="text-right">
					<?php  echo JblanceHelper::formatCurrency($withdraw->withdrawFee, false); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_STATUS'); ?>">
					<?php echo JblanceHelper::getApproveStatus($withdraw->approved); ?>
				</td>
				<td class="text-center">
					<a class="btn btn-mini jb-modal" title="<?php echo JText::_('COM_JBLANCE_PRINT_INVOICE'); ?>" href="<?php echo $link_invoice; ?>" rel="{handler: 'iframe', size: {x: 650, y: 500}}"><i class="icon-print"></i></a>
				</td>
			</tr>
	<?php
	$k = 1 - $k;
	} ?>
		</tbody>
	</table>
	</div>
	<?php
		else :
			echo JText::_('COM_JBLANCE_NO_PENDING_PAYMENTS_FOUND');
		endif;	//end of withdraw count
	}		//end of escrow withdraw
	?>
	<?php
	$countDeposit = $model->countManagePayPending('deposit');
	$newTitle = ($countDeposit > 0) ? ' <span class="redfont">(<b>'.$countDeposit.'</b>)</span>' : '';
	echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_DEPOSITS').$newTitle, 'deposits'); ?>
	<?php
	if(count($this->deposits)) : ?>
	<div id="no-more-tables">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo JText::_('COM_JBLANCE_DATE'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_AMOUNT').' ('.$currencysym.')'; ?></th>
				<th><?php echo JText::_('COM_JBLANCE_DEPOSIT_FEE').' ('.$currencysym.')'; ?></th>
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<div class="pagination">
					<?php echo $this->pageNavDeposit->getListFooter(); ?>
					</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
	<?php
	$k = 0;
	for($i=0, $n=count($this->deposits); $i < $n; $i++){
		$deposit 	  = $this->deposits[$i];
		$link_invoice =  JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$deposit->id.'&tmpl=component&print=1&type=deposit');
	?>
			<tr>
				<td data-title="#">
					<?php echo $i+1;?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_DATE'); ?>">
					<?php  echo JHTML::_('date', $deposit->date_deposit, $dformat); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_AMOUNT'); ?>" class="text-right">
					<?php  echo JblanceHelper::formatCurrency($deposit->amount, false); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_DEPOSIT_FEE'); ?>" class="text-right">
					<?php  echo JblanceHelper::formatCurrency($deposit->total-$deposit->amount, false); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_STATUS'); ?>">
					<?php echo JblanceHelper::getApproveStatus($deposit->approved); ?>
				</td>
				<td class="text-center">
					<a class="btn btn-mini jb-modal" title="<?php echo JText::_('COM_JBLANCE_PRINT_INVOICE'); ?>" href="<?php echo $link_invoice; ?>" rel="{handler: 'iframe', size: {x: 650, y: 500}}"><i class="icon-print"></i></a>
				</td>
			</tr>
	<?php
	$k = 1 - $k;
	} ?>
		</tbody>
	</table>
	</div>
	<?php
	else :
	echo JText::_('COM_JBLANCE_NO_PENDING_PAYMENTS_FOUND');
	endif;
	?>
	<?php echo JHtml::_('tabs.end'); ?>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>