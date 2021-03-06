<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	views/jbmenu.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Displays the JoomBri menu items (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/selectnav.min.js");
 
 $app  	  = JFactory::getApplication();
 $tmpl 	  = $app->input->get('tmpl', '', 'string');
 $preview = $app->input->get('preview', 0, 'int');
 $layout  = $app->input->get('layout', '', 'string');
 
 $config = JblanceHelper::getConfig();
 $limit = $config->feedLimitDashboard;
 
 $model	= $this->getModel(); 
 $user	= JFactory::getUser();
 $hasJBProfile = JblanceHelper::hasJBProfile($user->id);
 $link_messages = JRoute::_('index.php?option=com_jblance&view=message&layout=inbox');
 $link_home = JRoute::_('index.php');
 $link_logout = JRoute::_('index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&return='.base64_encode($link_home));
?>

<!-- Hide the menu for guest, non-joombri users, layout not print -->	
<?php if($hasJBProfile && $tmpl == '') : ?>
	<?php 
		$jbmenu = JblanceHelper::get('helper.menu');
		$activeLink = $jbmenu->getActiveLink();
		$active = $jbmenu->getActiveId($activeLink);
		
		$menus  = $jbmenu->getJBMenuItems();
		$processedMenus = $jbmenu->processJBMenuItems($menus);
		
		$notifys = JblanceHelper::getFeeds($limit, 'notify');	//get the notificataion feeds
		$newMsgs = JblanceHelper::countUnreadMsg();
		
		if($processedMenus){
	?>
	
<script type="text/javascript">
	function showElement(layer){
		var myLayer = document.getElementById(layer);
		if(myLayer.style.display == "none"){
			myLayer.style.display = "block";
			myLayer.backgroundPosition = "top";
		} 
		else { 
			myLayer.style.display = "none";
		}

		//set the status to read
		var myRequest = new Request({
			url: 'index.php?option=com_jblance&task=user.setfeedread',
			method: 'post',
			data: {}, 
			//onRequest: function(){ $('feed_hide_'+msgid).empty().addClass('jbloading'); },
			onComplete: function(response){
				if(response == 'OK'){
					//$('jbl_feed_item_'+msgid).setStyle('display', 'none');
				} 
			}
		});
		myRequest.send();
	}

	window.addEvent('domready', function(){
		selectnav('jbnav');
	});
</script>
<div class="row-fluid">
<div class="span12">
<div id="jbMenu">
	<div class="jbMenuLft">
		<!--<a href="<?php echo JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront');?>" class="<?php echo $active == 0 ? 'active' : 'active'; ?>"><?php echo JText::_('Home');?></a>
		-->
		<div class="jbMenuBar">
			<ul id="jbnav" style="margin:0px; padding:0px;">
			<?php
			foreach($processedMenus as $menu){
			//$class	= empty( $menu->childs ) ? ' class="no-child"' : '';
			?>
				<li<?php echo $active == $menu->item->id ? ' class="active"' : '';?>>
					<?php 
					if($menu->item->type == 'separator')
						$href = 'javascript:void(0);';
					else
						$href = JRoute::_($menu->item->link);
					?>
					<a href="<?php echo $href; ?>"<?php echo $active === $menu->item->id ? ' class="active"' : '';?>><?php echo JText::_( $menu->item->title );?></a>
					<?php
					if(!empty($menu->childs)){
					?>
						<ul style="margin:0px; padding:0px;">
						<?php
						foreach($menu->childs as $child){
						?>
							<li>
								<a href="<?php echo JRoute::_($child->link); ?>"><?php echo JText::_($child->title);?></a>
							</li>
						<?php
						}
						?>
						</ul>
					<?php 
					}
					?>
				</li>
				<?php
				}
				?>
			</ul>
		</div>
		<div class="jbMenuIcon">
			<div id="jbMenuNotify">
				<a href="javascript:void(0);" onclick="javascript:showElement('notify-menu')">
					<img src="components/com_jblance/images/notify.png" alt="img" title="">
				</a>
				<?php 
				$countUnreadFeeds = countUnreadFeeds();
				if($countUnreadFeeds) : ?>
				<span class="notify-count"><?php echo $countUnreadFeeds; ?></span>
				<?php endif; ?>
				<div id="notify-menu" class="notify-menu" style="display: none;">
					<a href="javascript:void(0);" style="float: right; padding: 10px;" onclick="javascript:showElement('notify-menu')">
						<img alt="" src="components/com_jblance/images/remove.gif" title="<?php echo JText::_('COM_JBLANCE_CLOSE'); ?>" alt="img">
					</a>
					<div class="jbl_h3title" style="padding: 5px;"><?php echo JText::_('COM_JBLANCE_NOTIFICATIONS'); ?></div>
					<div style="max-height: 400px;overflow:auto;">
				<?php
				if(count($notifys)){
					for ($i=0, $n=count($notifys); $i < $n; $i++) {
						$notify = $notifys[$i]; ?>
						<div class="media jb-borderbtm-dot">
							<?php echo $notify->logo; ?>
							<div class="media-body">
								<?php echo $notify->title; ?>
								<div>
						        	<i class="icon-calendar"></i> <?php echo $notify->daysago; ?>
						        </div>
							</div>
						</div>
					<?php
					}
				}
				else { ?>
					<div class="font16" style="padding: 5px; border-bottom: none;">
					<?php
						echo JText::_('COM_JBLANCE_NO_NEW_NOTIFICATION');
					?>
					</div>
				<?php } ?>
					</div>
				</div>
			</div>
			<div id="jbMenuInbox">
				<a href="<?php echo $link_messages; ?>" title="New Messages">
					<img src="components/com_jblance/images/notify_mail.png" alt="img" title="">
				</a>
				<?php if($newMsgs) : ?>
				<span class="notify-count"><?php echo $newMsgs; ?></span>
				<?php endif; ?>
			</div>
		</div>
		<div style="top: 6px; position: absolute; right: 0px;">
			<a href="<?php echo $link_logout; ?>" title="<?php echo JText::_('JLOGOUT'); ?>"><i class="icon-off icon-white pull-right"></i></a>
		</div>
	</div>
	
</div>
</div>
</div>
<div class="clearfix"></div>
<?php 
		}
	endif; ?>

<?php 
function countUnreadFeeds(){
	$db = JFactory::getDBO();
	$user	= JFactory::getUser();

	$query = "SELECT COUNT(is_read) isRead FROM #__jblance_feed WHERE target=$user->id AND is_read=0";
	$db->setQuery($query);
	$total 	= $db->loadResult();
	return $total;
}
?>
