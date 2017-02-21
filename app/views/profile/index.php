<?php
use framework\Application as app;
/* @var $user \app\models\User  */
?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
	var submit = document.querySelector('div#registration-form form input[type=submit]');

	submit.addEventListener('click', function(e) {
		e.preventDefault();
		//console.log('logout click');
		var f = document.querySelector('div#registration-form form');
		window.location.href = f.action;
	});
	
});
</script>

<div id="registration-form">
	<img  style="border: 4px solid gray; width: 120px; height:120px; position: absolute;; top: -10px; right: -10px" 
	src="<?=app::app()->params['avatar_web_path'];?><?= (is_readable(app::app()->params['avatar_path'].$user->user_id.'.jpg') ? $user->user_id .'.jpg' : 
	( $user->sex == 'female' ? 'female.png' : 'male.png' ))?>">
	<div id="logo"><?=app::t('profile', 'titles');?></div>
	<form action="<?=app::app()->request()->url('register/logout');?>" method="get">
		<fieldset>
			<legend><?=app::t('account', 'titles');?></legend>
			<div class='row'>
				<label><?=app::t('username', 'titles');?></label>
				<div class="item"><?=$user->username; ?></div>
			</div>
			<div class='row'>
				<label><?=app::t('e-mail', 'titles');?></label>
				<div class="item"><?=$user->email; ?></div>
			</div>
		</fieldset>
		<fieldset>
			<legend><?=app::t('profile', 'titles');?></legend>
			<div class='row'>
				<label><?=app::t('name', 'titles');?></label>
				<div class="item"><?=$user->name; ?></div>
			</div>
			<div class='row'>
				<label><?=app::t('surname', 'titles');?></label>
				<div class="item"><?=$user->surname; ?></div>
			</div>
			<div class='row'>
				<label><?=app::t('sex', 'titles');?></label>
				<div class="item"><?=app::t($user->sex,'titles');?></div>
			</div>
			<div class='row'>
				<label><?=app::t('logged info', 'titles');?></label>
				<div class="item">
					<?=app::t('created', 'titles');?>: <?=$user->created_date;?>
					<br/>
					<?=app::t('updated', 'titles');?>: <?=$user->updated_date;?>
					<br/>
					<?=app::t('accessed', 'titles');?>: <?=$user->lastaccess_date;?>
					<br/>
					<?=app::t('logged', 'titles');?>: <?=$user->lastlogin_date;?>
					
					<table style="border: 1px dashed; width: 100%; margin-top: 20px">
					<?php foreach ($user->getLoginAttempts() as $el):?>
					<tr><td><?=$el->created_date;?></td><td><?=$el->status;?></td><td><?=$el->user_ip;?></td></tr>
					<?php endforeach;?>
					</table>
				</div>
			</div>
		</fieldset>
		<div class="parsley-error-list"></div>
		<input type="submit" value="<?=app::t('logout', 'titles');?>">
	</form>
</div>