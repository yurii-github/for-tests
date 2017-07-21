<?php
use framework\Application as app;
use app\widgets\Lang;
?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {

	var lang_sels = document.querySelectorAll('div#registration-form form img.language-selector');

	for(var i=0; i < lang_sels.length; i++) {
		lang_sels[i].addEventListener('click', function(e) {
			var lang =  e.target.getAttribute('data-language');
			//console.log('.language-selector:click', e.target.getAttribute('data-language'));
			document.cookie = 'language='+lang;
			window.location.href = UpdateQueryString('lang', lang, "<?=app::request()->getUrl();?>");
		});
	}

	var submits = document.querySelectorAll('div#registration-form form input[type=submit]');
	var f = document.querySelector('div#registration-form form');
	
	for(var i=0; i < submits.length; i++) {
		submits[i].addEventListener('click', function(e) {
			e.preventDefault();
			switch (e.target.className) {
				case 'login':
					login(e); //verify					
					break;
				case 'register':
					window.location.href = "<?=app::request()->url('register');?>";
					break;
			}
		});
	}

	
	//
	// authenticate user
	//
	function login(ev)
	{
		try {
			ev.target.disabled = 'disabled';
			if(f.elements['username'].value == '') {
				throw new Error("<?=app::t('Username cannot be empty');?>");
			}
			if(f.elements['password'].value == '') {
				throw new Error("<?=app::t('Password cannot be empty');?>");
			}
		} catch(e) {
			ev.target.disabled = '';
			show_error(e.message);
			return;
		}
		
		var xhr = new XMLHttpRequest();
		xhr.open(f.method, f.action);
		xhr.responseType = 'json/xml';
		xhr.onload = function(e) {
			try {
				if (this.readyState == 4) { //finished
					if (this.status == 200) { //success
						var r = JSON.parse(this.responseText);
						if(r.success == false) {//fail
							throw new Error(r.msg);
						}
						if(r.redirect != null && r.success) {
							window.location.href = r.redirect;
						}
					} else {//xhr failed
						throw new Error(this.statusText);
					}
				}
			} catch(e) {
				ev.target.disabled = '';
				show_error(e.message);
			}
		};

		var fdata = new FormData();
		fdata.append('username', f.elements['username'].value);
		fdata.append('password', f.elements['password'].value);
		xhr.send(fdata);
	};

});
</script>
<div id="registration-form">
	<form action="<?=app::request()->url('register/authenticate')?>" method="post">
		<?= Lang::render([ ['uk_UA', 'en_GB'], app::$lang ]); ?>
		<fieldset>
			<legend><?=app::t('authentication', 'titles');?></legend>
			<div class='row'>
				<label for="username"><?=app::t('username', 'titles');?></label>
				<input type="text" placeholder="<?=app::t('your unique name used for login', 'messages');?>" name="username" id="username" />
			</div>
			<div class='row'>
				<label for="password"><?=app::t('password', 'titles');?></label>
				<input type="password" name='password' placeholder="<?=app::t('your password for login', 'messages');?>" />
			</div>
		</fieldset>
		<div class="parsley-error-list"></div>
		<input type="submit" class="login" value="<?=app::t('login', 'titles');?>">
		<input type="submit" class="register" value="<?=app::t('register', 'titles');?>">
	</form>
</div>