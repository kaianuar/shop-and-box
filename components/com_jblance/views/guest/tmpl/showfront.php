<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	views/guest/tmpl/showfront.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	User Groups (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 
 $doc = JFactory::getDocument();
 //$doc->addStyleSheet("components/com_jblance/css/pricing.css");

 $app  	= JFactory::getApplication();
 $user	= JFactory::getUser();
 $model = $this->getModel();

 $config = JblanceHelper::getConfig();
 $link_dashboard = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard');
 
 jbimport('fbconnect');
 $fb = new FbconnectHelper();
 $user_info = $fb->initFbLogin();
 
 //check if app key/secret is empty. If empty, do not show the FB connect button
 $showFbConnect = true;
 $app_id = $config->fbApikey;
 $app_sec = $config->fbAppsecret;
 if(empty($app_id) || empty($app_sec))
 	$showFbConnect = false;
?>
	
<script type="text/javascript">
<!--
	window.addEvent('domready',function() {
		new Fx.SmoothScroll({
			duration: 500
			}, window);
	});

	function valButton(btn) {
		var cnt = -1;
		for (var i=btn.length-1; i > -1; i--) {
		   if (btn[i].checked) {cnt = i; i = -1;}
		   }
		if (cnt > -1) 
			return btn[cnt].value;
		else 
			return null;
	}
	function validateForm(f){
		var btn = document.getElementById('ugid').value;
		
		if(btn == null){
			alert('<?php echo JText::_('COM_JBLANCE_PLEASE_CHOOSE_YOUR_ROLE', true); ?>');
			return false;
		}
		else {
			return true;				
		}
	}
	function selectRole(ugId){
		$$('label.active').removeClass('active btn-success');
		$('lbl_ug_id'+ugId).addClass('active btn-success');
		$('userGroup').submit();
	}
//-->
</script>

<div class="row-fluid jbbox-shadow jbbox-gradient">
	<!-- <div class="span8">
		<div class="introduction">
			<h2><?php //echo JText::_($config->welcomeTitle); ?></h2>
			<ul id="featurelist">
				<li><?php //echo JText::_('COM_JBLANCE_HIRE_ONLINE_FRACTION_COST'); ?></li>
				<li><?php //echo JText::_('COM_JBLANCE_OUTSOURCE_ANYTHING_YOU_CAN_THINK'); ?></li>
	            <li><?php //echo JText::_('COM_JBLANCE_PROGRAMMERS_DESIGNERS_CONTENT_WRITERS_READY'); ?></li>
	            <li><?php //echo JText::_('COM_JBLANCE_PAY_FREELANCERS_ONCE_HAPPY_WITH_WORK'); ?></li>
			</ul>
			<a href="#ugselect" id="signup" class="btn btn-large btn-primary"><?php //echo JText::_('COM_JBLANCE_SIGN_UP_NOW'); ?></a>
		</div>
	</div> -->
	<div class="span4">
	<!-- if user is guest -->
        <?php if($user->guest) : ?>
	    <div class="jb-loginform">
	    	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="login" id="form-login">
	       		<h3><?php echo JText::_('COM_JBLANCE_MEMBERS_LOGIN'); ?></h3>
	        	<div class="control-group">
					<label class="control-label" for="username"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?>:</label>
						<div class="controls">
							<div class="input-prepend input-append">
      							<span class="add-on"><i class="icon-user"></i></span>
								<input type="text" class="span6" name="username" id="username" />
								<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>" class="btn" title="<?php echo JText::_('COM_JBLANCE_FORGOT_YOUR_USERNAME').'?'; ?>">
									<i class="icon-question-sign"></i>
								</a>
							</div>
						</div>
					</div>
		        	<div class="control-group">
						<label class="control-label" for="password"><?php echo JText::_('COM_JBLANCE_PASSWORD'); ?>:</label>
						<div class="controls">
							<div class="input-prepend input-append">
      							<span class="add-on"><i class="icon-lock"></i></span>
								<input type="password" class="span6" name="password" id="password" />
								<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>" class="btn" title="<?php echo JText::_('COM_JBLANCE_FORGOT_YOUR_PASSWORD').'?'; ?>">
									<i class="icon-question-sign"></i>
								</a>
							</div>
						</div>
					</div>
					<div class="control-group">
					    <div class="controls">
					      <label class="checkbox">
					        <input type="checkbox" alt="Remember me" value="yes" id="remember" name="remember" /><?php echo JText::_('COM_JBLANCE_REMEMBER_ME'); ?>
						</label>
						<input type="submit" value="<?php echo JText::_('COM_JBLANCE_LOGIN');?>" name="submit" id="submit" class="btn btn-small" />
						<?php 
						if($user_info['loginUrl'] != '' && $showFbConnect){ ?>
						<a class="btn btn-primary btn-small" href="<?php echo $user_info['loginUrl']; ?>">
							<span><?php echo JText::_('COM_JBLANCE_SIGN_IN_WITH_FACEBOOK'); ?></span>
						</a> 
						<?php 
						} ?>
				    </div>
				</div>
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="user.login" />
				<input type="hidden" name="return" value="<?php echo base64_encode($link_dashboard); ?>" />
				<?php echo JHTML::_('form.token'); ?>
	        </form>
		</div>
	<?php else : ?>
		<div class="jb-loginform">
			<h4><?php echo JText::sprintf('COM_JBLANCE_WELCOME_USER', $user->name); ?></h4>
		</div>
	<?php endif; ?>
	</div>
	<div class="span7">
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="regNewUser" class="form-horizontal form-validate" onsubmit="return validateForm(this);" enctype="multipart/form-data">
<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_ACCOUNT_INFO'); ?></div>
<?php echo JText::_('COM_JBLANCE_FIELDS_COMPULSORY'); ?>
	
		<fieldset>
	<legend><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></legend>
		<div class="control-group">
			<label class="control-label" for="name"><?php echo JText::_('COM_JBLANCE_NAME'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input class="inputbox required" type="text" name="name" id="name" size="40" value="" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="username"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input type="text" name="username" id="username" class="inputbox hasTip required" onchange="checkAvailable(this);" title="<?php echo JText::_('COM_JBLANCE_TT_USERNAME'); ?>"> 
				<div id="status_username" class="dis-inl-blk"></div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="email"><?php echo JText::_('COM_JBLANCE_EMAIL'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input type="text" name="email" id="email" class="inputbox hasTip required validate-email" onchange="checkAvailable(this);" title="<?php echo JText::_('COM_JBLANCE_TT_EMAIL'); ?>">
				<div id="status_email" class="dis-inl-blk"></div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password"><?php echo JText::_('COM_JBLANCE_PASSWORD'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input type="password" name="password" id="password" class="inputbox hasTip required validate-password" title="<?php echo JText::_('COM_JBLANCE_TT_PASSWORD'); ?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password2"><?php echo JText::_('COM_JBLANCE_CONFIRM_PASSWORD'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
					<input type="password" size="40" maxlength="100" name="password2" id="password2" class="inputbox hasTip required validate-passverify" title="<?php echo JText::_('COM_JBLANCE_TT_REPASSWORD'); ?>">
			</div>
		</div>
	</fieldset>
	
	<?php
	$termid = $config->termArticleId;
	$link = JRoute::_("index.php?option=com_content&view=article&id=".$termid.'&tmpl=component');
	?>
	<p><?php echo JText::sprintf('COM_JBLANCE_BY_CLICKING_YOU_AGREE', $link); ?></p>
	
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_( 'COM_JBLANCE_I_ACCEPT_CREATE_MY_ACCOUNT' ); ?>" class="btn btn-primary" />
	</div>
		
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="guest.grabuseraccountinfo" />
	<input type="hidden" name="ugid" id="ug_id2" value="2" />
	<?php echo JHTML::_('form.token'); ?>
</form>
	</div>
</div>

	<div style="clear:both;"></div>
<!--<div class="sp20">&nbsp;</div>

<a id="ugselect"></a>
 <form action="<?php// echo JRoute::_('index.php'); ?>" method="post" name="userGroup" id="userGroup" class="form-validate" onsubmit="return validateForm(this);">
	
	<?php /*
	$totGroups = count($this->userGroups);
	for($i = 0; $i < $totGroups; $i++){ 
		$userGroup = $this->userGroups[$i]; 
		if($i % 2 == 0){
		*/ ?>
	<div class="row-fluid usergroup-table">	
		<?php//
		}?>
		<div class="span6 well well-small white userrole">
			<div class="userrole-name text-center">
			<?php// if($userGroup->approval == 1) : ?>
			<div class="pull-right"><span class="label label-important"><?php// echo JText::_('COM_JBLANCE_REQUIRE_APPROVAL'); ?></span></div>
			<?php //endif; ?>
			<h2><?php //echo $userGroup->name; ?></h2>
			</div>
			<?php// echo stripslashes($userGroup->description); ?>
			<hr>
			<div class="text-center">
				<label for="ug_id<?php// echo $userGroup->id; ?>" id="lbl_ug_id<?php //echo $userGroup->id; ?>" class="btn btn-primary btn-large">
					<input type="radio" name="ugid" id="ug_id<?php //echo $userGroup->id; ?>" value="<?php// echo $userGroup->id; ?>" class="required validate-radio" style="display: none;" onclick="javascript:selectRole(2);" />
					<?php echo JText::_('COM_JBLANCE_CHOOSE_AND_CONTINUE'); ?>
				</label>
			</div>
        </div>
        <?php// if($i % 2 == 1 || $i==($totGroups-1)){ ?>
	</div>
        <?php// }?>
	<?php
	//}?>
	
	<p class="jbbox-info">
	<?php // echo JText::_('COM_JBLANCE_REQUIRE_APPROVAL_NOTE'); ?>
	</p>
	
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="guest.grabusergroupinfo" />
	<input type="hidden" name="check" value="post" />
</form> -->