<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/detailproject.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows details of the project (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);

 $row 	= $this->row;
 $model = $this->getModel();
 $user 	= JFactory::getUser();
 $uri 	= JFactory::getURI();
 
 $config 		  = JblanceHelper::getConfig();
 $currencycode 	  = $config->currencyCode;
 $dformat 		  = $config->dateFormat;
 $enableReporting = $config->enableReporting;
 $guestReporting  = $config->enableGuestReporting;
 $enableAddThis   = $config->enableAddThis;
 $addThisPubid	  = $config->addThisPubid;
 $showUsername	  = $config->showUsername;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';
 
 $projHelper 	= JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 $hasJBProfile  = JblanceHelper::hasJBProfile($user->id);
 
 if($hasJBProfile){
 	$jbuser = JblanceHelper::get('helper.user');
 	$userGroup = $jbuser->getUserGroupInfo($user->id, null);
 }
 
 $isMine = ($row->publisher_userid == $user->id);
 
 $link_report 		= JRoute::_('index.php?option=com_jblance&view=message&layout=report&id='.$row->id.'&report=project&link='.base64_encode($uri)/* .'&tmpl=component' */);
 $link_edit_project = JRoute::_('index.php?option=com_jblance&view=project&layout=editproject&id='.$row->id); 
 $link_pick_user	= JRoute::_( 'index.php?option=com_jblance&view=project&layout=pickuser&id='.$row->id);
 JText::script('COM_JBLANCE_CLOSE');
 
 $now 		 = JFactory::getDate();
 $expiredate = JFactory::getDate($row->start_date);
 $expiredate->modify("+$row->expires days");
 $isExpired = ($now > $expiredate) ? true : false;
?>
<script type="text/javascript">
	window.addEvent('domready',function() {
		new Fx.SmoothScroll({
			duration: 500
			}, window);
	});

	window.addEvent('domready', function(){
		$('commentForm').addEvent('submit', function(e){
		e.stop();
		var req = new Request.HTML({
			url: 'index.php?option=com_jblance&task=project.submitforum',
			data: $('commentForm'),
			onRequest: function(){ $('btnSendMessage').set({'disabled': true, 'value': '<?php echo JText::_('COM_JBLANCE_SENDING'); ?>'}); },
			onSuccess: function(tree, response){
				
				var li = new Element('li');
				var span = new Element('span', {'text': response[1].get('text')}).inject(li);
				var span1 = new Element('span', {'text': '<?php echo JText::_('COM_JBLANCE_RECENTLY'); ?>', 'class':'fr'}).inject(span);
				var p = new Element('p', {'text': response[2].get('text')}).inject(li);
				li.inject($('commentList')).highlight('#EEE');
				$('commentForm').reset();
				$('btnSendMessage').set('value', '<?php echo JText::_('COM_JBLANCE_SENT'); ?>');
				
				//Scrolls the window to the bottom
				var myFx = new Fx.Scroll('commentList').toBottom();
			}
		}).send();
		});
	});

<?php
	if($row->orderState == 0) {	
?>

	window.addEvent('domready', function(){
		$('acceptanceForm').addEvent('submit', function(e){
		e.stop();
		var req = new Request.HTML({
			url: 'index.php?option=com_jblance&task=project.submitacceptance',
			data: $('acceptanceForm'),
			
			onSuccess: function(tree, response){
				
				var p = new Element('p', {'text': 'You have successfully accepted the order'});
				p.inject($('successMessage'));
	
			}
		}).send();
		});
	});	

<?php
	}
?>

	<?php
		foreach($this->items as $item){ 
	?>
		window.addEvent('domready', function(){ 

	        $('editItemForm<?=$item->id;?>').addEvent('submit', function(e){ 
			e.stop();
			var req = new Request.HTML({
				url: 'index.php?option=com_jblance&task=project.edititem',
				data: $('editItemForm<?=$item->id;?>'),
				
				onSuccess: function(tree, response){
					
					var p = new Element('p', {'text': 'You have successfully confirmed the order'});
					p.inject($('successMessage'));
		
				}
			}).send();
			});
				
		});
	<?php
		} 
	?>
	

	<?php
		if(($finalised == 1 && $row->orderState == 1) || $row->orderState == 3){
		?>		

	window.addEvent('domready', function(){
		$('finaliseForm').addEvent('submit', function(e){
		e.stop();
		var req = new Request.HTML({
			url: 'index.php?option=com_jblance&task=project.finaliseitem',
			data: $('finaliseForm'),
			
			onSuccess: function(tree, response){
				document.getElementById('finalised').innerHTML=  '';
				var p = new Element('p', {'text': 'You have successfully finalised the order'});
				p.inject($('finalised'));
	
			}
		}).send();
		});
	});

	<?php
		}
	?>

</script>
<!-- <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm"> -->
	<div class="pull-right">
		<!-- show the bid button only if the status is OPEN & not expired -->
		<?php if($row->status == 'COM_JBLANCE_OPEN' && !$isExpired && !$isMine) : ?>
			<?php $link_place_bid = JRoute::_( 'index.php?option=com_jblance&view=project&layout=placebid&id='.$row->id); ?>
			<a href="<?php echo $link_place_bid; ?>" class="btn btn-info btn-large"><?php echo JText::_('COM_JBLANCE_BID_ON_THIS_PROJECT'); ?></a>
		<?php endif; ?>
	</div>
	<div class="clearfix"></div>
	<div class="jbl_h3title">
		<h2><?php echo $row->project_title; ?> <small><?php echo JText::_('COM_JBLANCE_PROJECT_DETAILS'); ?></small></h2>
	</div>
	<div class="page-actions">
		<?php if($enableAddThis) : ?>
		<div id="social-bookmark" class="page-action fl">
			<!-- AddThis Button BEGIN -->
			<div class="addthis_toolbox addthis_default_style ">
				<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
				<a class="addthis_button_tweet"></a>
				<a class="addthis_button_google_plusone" g:plusone:size="medium"></a> 
				<a class="addthis_counter addthis_pill_style"></a>
			</div>
			<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
			<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=<?php echo $addThisPubid; ?>"></script>
			<!-- AddThis Button END -->
		</div>
		<?php endif; ?>
		<!-- show Edit Project and Pick User only to publisher -->
		<?php if($isMine) : ?>
			<div id="edit-project" class="page-action">
			    <a href="<?php echo $link_edit_project; ?>"><i class="icon-edit"></i> <?php echo JText::_('COM_JBLANCE_EDIT_PROJECT'); ?></a>
			</div>
			<!-- show Pick User if bids>0 and status=open -->
			<?php if($row->status != 'COM_JBLANCE_CLOSED' && count($this->bids) > 0) :?>
				<div id="pick-user" class="page-action">
				    <a href="<?php echo $link_pick_user; ?>"><i class="icon-map-marker"></i> <?php echo JText::_('COM_JBLANCE_PICK_USER').' ('.count($this->bids).')'; ?></a>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<?php if($enableReporting && ($user->id !=0 || $guestReporting )) : ?>
			<div id="report-this" class="page-action">
			    <a href="<?php echo $link_report; ?>"><i class="icon-warning-sign"></i> <?php echo JText::_('COM_JBLANCE_REPORT_PROJECT'); ?></a>
			</div>
			<?php endif; ?>
		<?php endif; ?>
		<!-- <div id="send-message" class="page-action">
		    <a href="<?php //echo $link_sendpm; ?>"><i class="icon-comment"></i> <?php echo JText::_('COM_JBLANCE_SEND_MESSAGE'); ?></a>
		</div> -->
	</div>
	<div class="clearfix"></div><br>
				
	<!--div class="well well-small">
		<ul class="promotions big" style="float: left; margin: -16px 0 0 -16px;">
			<?php if($row->is_featured) : ?>
			<li data-promotion="featured"><?php echo JText::_('COM_JBLANCE_FEATURED'); ?></li>
			<?php endif; ?>
			<?php if($row->is_private) : ?>
  			<li data-promotion="private"><?php echo JText::_('COM_JBLANCE_PRIVATE'); ?></li>
  			<?php endif; ?>
			<?php if($row->is_urgent) : ?>
  			<li data-promotion="urgent"><?php echo JText::_('COM_JBLANCE_URGENT'); ?></li>
  			<?php endif; ?>
  			<?php if($row->is_sealed) : ?>
			<li data-promotion="sealed"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></li>
			<?php endif; ?>
			<?php if($row->is_nda) : ?>
			<li data-promotion="nda"><?php echo JText::_('COM_JBLANCE_NDA'); ?></li>
			<?php endif; ?>
		</ul>
		<div class="clearfix"></div>
		<div class="row-fluid">
			<div class="span5">
				<div class="well well-small white span jb-aligncenter" style="margin:5px;">
		            <div style="display:inline-block; padding: 0 10px 0 5px;" class="jb-aligncenter border-r">
						<div class="margin-b5"><?php echo JText::_('COM_JBLANCE_BIDS'); ?></div>
						<div id="num-bids" class="boldfont skybluefont font16">
		                 	<?php if($row->is_sealed) : ?>
		                 	<span class="label label-important"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></span>
				  			<?php else : ?>
				  			<?php echo count($this->bids); ?>
				  			<?php endif; ?>
						</div>
					</div>
					<div style="display:inline-block; padding: 0 10px 0 10px;" class="jb-aligncenter border-r">
						<div class="margin-b5"><?php echo JText::_('COM_JBLANCE_AVG_BID').' ('.$currencycode.')'; ?></div>
						<div class="boldfont skybluefont font16">
		                    <?php
							$projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
							$avg = $projHelper->averageBidAmt($row->id);
							$avg = round($avg, 0);
							 ?>
							<?php if($row->is_sealed) : ?>
				  				<?php echo JText::_('COM_JBLANCE_NA'); ?>
				  			<?php else : ?>
				  				<?php echo JblanceHelper::formatCurrency($avg, true, 0, 0); ?>
				  			<?php endif; ?>
		                </div>
		            </div>
		            <div style="display:inline-block; padding: 0 5px 0 10px;" class="jb-aligncenter">
		                <div class="margin-b5"><?php echo JText::_('COM_JBLANCE_BUDGET').' ('.$currencycode.')'; ?></div>
		                <div class="boldfont skybluefont font16">
		                	<?php echo JblanceHelper::formatCurrency($row->budgetmin, true, false, 0); ?> - <?php echo JblanceHelper::formatCurrency($row->budgetmax, true, false, 0); ?>
		                </div>
		            </div>
	        	</div>
			</div>
			<div class="span3 offset4">
				<div class="well well-small white jb-aligncenter" style="margin:5px;">
					<div style="display:inline-block">
						<?php if(!$isExpired) : ?>
                    	<div class="greenfont font12">
                    		<?php 
								echo JblanceHelper::showRemainingDHM($expiredate);
							?>
                    	</div>
                    	<div class="boldfont greenfont font16"><?php echo strtoupper(JText::_($row->status)); ?></div>
                    	<?php else : ?>
                    	<div class="boldfont redfont font16"><?php echo strtoupper(JText::_('COM_JBLANCE_EXPIRED')); ?></div>
                    	<?php endif; ?>
					</div>
        		</div>
			</div>
		</div>
	</div-->
	
	<div class="row-fluid">
		<div class="span8">
			<h4><?php echo JText::_('COM_JBLANCE_PROJECT_DESCRIPTION'); ?>:</h4>
			<div style="text-align: justify;">
				<?php
				$finalised = 0;


				foreach($this->items as $item){ 
					$disabled = "";
					$received = "";
					$purchased = "";

					if($item->isConfirmed == 1 || $item->isRejected == 1){
						$disabled = "disabled";
					}

					if($item->isPurchased == 1){
						$purchased = "disabled";
					}

					if($item->isReceived == 1){
						$received = "disabled";
					}					
				?>
				
					<div class="span8">
						<form id="editItemForm<?=$item->id;?>" method="post" action="<?php echo JRoute::_('index.php'); ?>" class="form-inline">					

							<label for="itemName">Item Name</label><input type="text" name="itemName" id="itemName" value="<?=$item->item_name;?>" <?=$disabled;?> />

							<label for="itemURL">Item URL</label><input type="text" name="itemURL" id="itemURL" value="<?=$item->item_url;?>" <?=$disabled;?> />

							<label for="id_category">Category</label><input type="text" name="id_category" id="id_category" value="<?=$item->id_category;?>" <?=$disabled;?> />

							<label for="cost">Cost</label><input type="text" name="cost" id="cost" value="<?=$item->cost;?>" <?=$disabled;?> />

							<label for="isConfirmed">Confirmed</label><input type="text" name="isConfirmed" id="isConfirmed" value="<?=$item->isConfirmed;?>" <?=$disabled;?> />

							<label for="isRejected">Rejected</label><input type="text" name="isRejected" id="isRejected" value="<?=$item->isRejected;?>" <?=$disabled;?> />
							<input type="hidden" name="itemID" id="itemID" value="<?=$item->id;?>" />	
							<input type="hidden" name="orderState" id="orderState" value="<?=$row->orderState;?>" />						
							<?php
							if($row->orderState == 3){
							?>
							<label for="isPurchased">Purchased</label><input type="text" name="isPurchased" id="isPurchased" value="<?=$item->isPurchased;?>" <?=$purchased;?>  />

							<label for="isReceived">Received</label><input type="text" name="isReceived" id="isReisReceivedjected" value="<?=$item->isReceived;?>" <?=$received;?> />


							<?php
							}
							?>							
							<?php
							if(($item->isConfirmed == 0 && $item->isRejected == 0) || ($row->orderState == 3 && ($item->isPurchased != 1 || $item->isReceived != 1))){
							?>
								<input type="submit" value="Confirmed" id="btnConfirmed" class="btn btn-primary" />

							<?php
							}
							?>
							<?php
							if($item->isConfirmed == 1 || $item->isRejected == 1){
								$finalised = 1;
							}else{
								$finalised = 0;
							}
							?>							

							
						</form>	

					</div>

		        
				
				<?php
				} ?>
					
				<?php
					if(($finalised == 1 && $row->orderState == 1) || $row->orderState == 3){
					?>
					<div id="finalised">
						<form id="finaliseForm" method="post" action="<?php echo JRoute::_('index.php'); ?>" class="form-inline">

							<input type="submit" value="Finalise Order" id="btnFinalise" class="btn btn-primary" />
							<input type="hidden" name="project_id" value="<?php echo $row->id; ?>" />
							<input type="hidden" name="orderState" id="orderState" value="<?=$row->orderState;?>" />
						</form>	
					</div>
					<?php
					}
					?>	
			</div>
			
			<!-- <h4><?php echo JText::_('COM_JBLANCE_SKILLS_REQUIRED'); ?>:</h4>
			<div><?php echo JblanceHelper::getCategoryNames($row->id_category); ?></div> -->
			
			<?php
			if(count($this->projfiles) > 0) : ?>
			<h4><?php echo JText::_('COM_JBLANCE_ADDITIONAL_FILES'); ?>:</h4>
			<div><i class="icon-file"></i>
				<?php
				foreach($this->projfiles as $projfile){ 
					if($user->guest)
						echo $projfile->show_name.', ';
					else
						echo LinkHelper::getDownloadLink('project', $projfile->id, 'project.download').', ';
				} ?>
			</div>
			<?php endif; ?>
			
			<?php if($this->fields) : ?>
			<?php 
			$fields = JblanceHelper::get('helper.fields');		// create an instance of the class FieldsHelper
			$parents = $children = array();
			//isolate parent and childr
			foreach($this->fields as $ct){
				if($ct->parent == 0)
					$parents[] = $ct;
				else
					$children[] = $ct;
			}
			if(count($parents)){
				foreach($parents as $pt){ ?>
				<h4><?php echo JText::_($pt->field_title); ?>:</h4>
				<div class="form-horizontal">
						<?php
						foreach($children as $ct){
							if($ct->parent == $pt->id){ ?>
					<div class="control-group">
						<label class="control-label nopadding"><?php echo JText::_($ct->field_title); ?>: </label>
						<div class="controls">
							<?php $fields->getFieldHTMLValues($ct, $row->id, 'project'); ?>
						</div>
					</div>
						<?php
							}
						} ?>
				</div>
			<?php
				}
			}
			?>
			<?php endif; ?>
		</div>
		<div class="span4">
			<div class="media">
				<?php 
				$attrib = 'width=56 height=56 class="img-polaroid"';
				$avatar = JblanceHelper::getThumbnail($row->publisher_userid, $attrib);
				echo !empty($avatar) ? LinkHelper::GetProfileLink($row->publisher_userid, $avatar, '', '', ' pull-left') : '&nbsp;'; ?>
  				<div class="media-body">
    				<div class="media-heading">
    					<strong><?php echo JText::_('COM_JBLANCE_POSTED_BY'); ?> :</strong>
    				</div>
    				<div>
	    				<?php 
						$publisher = JFactory::getUser($row->publisher_userid); 
						echo LinkHelper::GetProfileLink($row->publisher_userid, $this->escape($publisher->$nameOrUsername)); ?>
					</div>
					<div style="margin-top: 5px;">
						<?php JblanceHelper::getAvarageRate($row->publisher_userid); ?>
					</div>
  				</div>
			</div>
		</div>
	</div>
	<div class="lineseparator"></div>
	<div class="row-fluid">
		<div class="span12">
			<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PUBLIC_CLARIFICATION_BOARD'); ?></div>
			<span style="font-style:italic;"><?php echo JText::sprintf('COM_JBLANCE_X_MESSAGES', count($this->forums)); ?></span>
			<div class="fr"><a href="#addmessage_bm" class="btn"><?php echo JText::_('COM_JBLANCE_ADD_MESSAGE'); ?></a></div>
			<div id="comments">
				<ul id="commentList" style="max-height: 400px; overflow: auto;">
				<?php 
				for($i=0, $x=count($this->forums); $i < $x; $i++){
					$forum = $this->forums[$i];
					$poster = JFactory::getUser($forum->user_id)->$nameOrUsername;
					$postDate = JFactory::getDate($forum->date_post); ?>
					<li>
		        		<span><?php echo LinkHelper::GetProfileLink($forum->user_id, $poster); ?>
			        		<span class="fr">
			        		<?php echo JblanceHelper::showTimePastDHM($postDate, 'SHORT'); ?>
							</span>
						</span>
		        		<p><?php echo $forum->message; ?></p>
		      		</li>
		      	<?php 
				}
		      	?>
		    	</ul>
		    	<form id="commentForm" method="post" action="<?php echo JRoute::_('index.php'); ?>" class="form-inline">
		    		<a id="addmessage_bm"></a>
		    		<!-- show the forum add message only for bidder and publisher -->
					<?php 
					$hasBid = $projHelper->hasBid($row->id, $user->id);
					if(($user->id == $row->publisher_userid) || $hasBid) :
					?>
			    	<div class="well">
			    		<textarea id="message" name="message" rows="3" class="input-xxlarge"></textarea>
						<input type="submit" value="<?php echo JText::_('COM_JBLANCE_POST_MESSAGE'); ?>" id="btnSendMessage" class="btn btn-primary" />
				        <div style="margin-top: 5px;"><?php echo JText::_('COM_JBLANCE_SHARING_CONTACT_PROHIBITED'); ?></div>
				        <input type="hidden" name="project_id" value="<?php echo $row->id; ?>" />
				        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>" />
					</div>
					<?php 
					else : ?>
					<div class="jbbox-info"><?php echo JText::_('COM_JBLANCE_MUST_BID_TO_POST_MESSAGES'); ?></div>
					<?php	
					endif;
					?>
				</form>
			</div>
		</div>
	</div>
	<?php
		// Hides the acceptance form if the state is no longer 0
		if($row->orderState == 0) {

	?>
	<div class="row-fluid">
		<div class="span12">

		    <form id="acceptanceForm" method="post" action="<?php echo JRoute::_('index.php'); ?>" class="form-inline">
				<input type="submit" value="<?php echo JText::_('COM_JBLANCE_POST_ACCEPT'); ?>" id="btnAcceptAssignment" class="btn btn-primary" />
		        <input type="hidden" name="project_id" value="<?php echo $row->id; ?>" />
		        <input type="hidden" name="action" value="accept" />		        
			</form>
		    <form id="acceptanceForm" method="post" action="<?php echo JRoute::_('index.php'); ?>" class="form-inline">
				<input type="submit" value="<?php echo JText::_('COM_JBLANCE_POST_REJECT'); ?>" id="btnRejectAssignment" class="btn btn-primary" />
		        <input type="hidden" name="project_id" value="<?php echo $row->id; ?>" />
		        <input type="hidden" name="action" value="reject" />
			</form>	
			<div id="successMessage">
			</div>		
		</div>
	</div>
	<?php
		}
	?>
<!-- </form> -->