<?php 
//
//TEMPLATE BY http://codepen.io/roine/pen/ydkge
//
use framework\Application as app;
?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {

	var submit = document.querySelector('div#registration-form form input[type=submit]');
	var f = document.querySelector('div#registration-form form');
	var is_registered = false;

	//
	// submit click event, registers user first, authenticates him, then tries to upload its avatar
	//
	submit.addEventListener('click', function(e) {
		e.preventDefault();
		//console.log('submit click');
		e.target.disabled = 'disabled';//for dummies, no extra clicks

		try {
			if(is_registered) { // try to upload image
				upload_cover(e);
			} else {   // try to register user
				register(e);
			}	
		} catch(err) {
			e.target.disabled = '';//asd
			show_error(err.message);
		}
	});


	
	//
	// register new user
	// ev event called
	// @throws Error
	//
	function register(ev)
	{
		var fdata = new FormData();
		fdata.append('username', f.elements['username'].value);
		fdata.append('password', f.elements['password'].value);
		fdata.append('email', f.elements['email'].value);
		fdata.append('fullname', f.elements['fullname'].value);
		fdata.append('sex', f.elements['sex'].value);
	
		//username
		if(f.elements['username'].value == '') {
			throw new Error("<?=app::t('Username cannot be empty');?>");
		}
		var pat = /^[\wа-яА-ЯіІїЇєЄ]+$/g;
		if (! pat.test(f.elements['username'].value)) {
			throw new Error("<?=app::t('Username can contain only letters, numbers, underscore');?>");
		}
		if (f.elements['username'].value.length < <?=app::app()->params['username_len_min'];?> 
		   || f.elements['username'].value.length > <?=app::app()->params['username_len_max'];?>) {
			throw new Error("<?=app::t('Username length must be {min}-{max} characters long', 'messages', 
				['{min}'=>app::app()->params['username_len_min'],'{max}'=>app::app()->params['username_len_max']]);?>");
		}
		// password
		if(f.elements['password'].value == '') {
			throw new Error("<?=app::t('Password cannot be empty');?>");
		}
		if (f.elements['username'].value == f.elements['password'].value) {
			throw new Error("<?=app::t('Password cannot be same as username');?>");
		}
		if (f.elements['password'].value.length < <?=app::app()->params['password_len_min'];?> 
		   || f.elements['password'].value.length > <?=app::app()->params['password_len_max'];?>) {
			throw new Error("<?=app::t('Password length must be {min}-{max} characters long', 'messages', 
				['{min}'=>app::app()->params['password_len_min'],'{max}'=>app::app()->params['password_len_max']]);?>");
		}
		//email
		if (f.elements['email'].value == '') {
			throw new Error("<?=app::t('Email cannot be empty');?>");
		}
		if (!validateEmail(f.elements['email'].value)) {
			throw new Error("<?=app::t('Please provide valid email');?>");
		}
		//fullname
		if (f.elements['fullname'].value == '') {
			throw new Error("<?=app::t('Fullname cannot be empty');?>");
		}
		var pat = /^[a-zA-Zа-яА-ЯіІїЇєЄ ]+$/g;
		if (! pat.test(f.elements['fullname'].value)) {
			throw new Error("<?=app::t('Fullname can contain only letters and spaces');?>");
		}
		if (f.elements['fullname'].value.length > <?=app::app()->params['fullname_len_max'];?>) {
			throw new Error("<?=app::t('Fullname cannot be longer than {num} characters', 'messages',
			 ['{num}'=>app::app()->params['fullname_len_max']]);?>");
		}

		var xhr = new XMLHttpRequest();
		xhr.onload = function(e) {
			try {
				if (this.readyState == 4) { //finished
					if (this.status == 200) { //success
						var r = JSON.parse(this.responseText);
						if(!r.success) {//fail
							throw new Error(r.msg);
						}
						show_error('');//hide errors
						is_registered = true;
						submit.disabled = '';
						submit.click(); // self click for cover upload trigger
					} else {
						throw new Error(this.statusText);
					}
				}
			} catch(e) {
				submit.disabled = '';
				show_error(e.message);
			}
		};
		xhr.responseType = 'json/xml';
		xhr.open(f.method, f.action);
		xhr.send(fdata);
	}


	//
	// uploads cover/avatar of newly created user
	//
	// @param ev event called
	// @throws Error
	//
	function upload_cover(ev)
	{
		var av = f.querySelector('input#avatar');
		
		if(av.files[0] === undefined || av.files[0].name == '') {
			//console.log('no avatar');
			throw new Error("<?=app::t('Avatar cannot be empty');?>");
		}
		if ((typeof FileReader !== undefined) && av.files[0].size > <?=app::app()->params['avatar_max_size']*1000;?> ) {
			throw new Error("<?=app::t('Max filesize is {kb}kb, types [{types}]', 'messages',
				['{kb}' => app::app()->params['avatar_max_size'],
				'{types}' => implode(',',array_keys(app::app()->params['avatar_allowed_mime']))]);?>");
		}

		var bar =  document.querySelector('div#registration-form form progress');
		var fdata = new FormData();
		fdata.append('file', av.files[0]);	
			
		var xhr = new XMLHttpRequest();
		xhr.upload.onprogress = function(e) {
			var percent = (e.loaded / e.total) * 100;
			var progress = Math.round(percent);
			bar.value = progress;
			//console.log(progress);
		};
		xhr.onload = function(e) {
			try {
				if (this.readyState == 4) { //finished
					if (this.status == 200) { //success
						var r = JSON.parse(this.responseText);
						if(!r.success) {//fail
							throw new Error(r.msg);
						}
						//all is done, go away!
						window.location.href = "<?=app::request()->url('profile');?>";
					}
				} else {
					throw new Error(this.statusText);
				}
			} catch(e) {
				submit.disabled = '';
				show_error(e.message);
			}
		};
		xhr.responseType = "json/xml";
		xhr.open("POST", "<?=app::request()->url('register/uploadcover');?>");
		xhr.send(fdata);
	}
	

});
</script>
<div id="registration-form">
	<div id="logo"><?=app::t('registration', 'titles');?></div>
	<form action="<?=app::request()->url('register/register');?>" method="post">
		<fieldset>
			<legend><?=app::t('account', 'titles');?></legend>
			<div class='row'>
				<label for="username"><?=app::t('username', 'titles');?></label>
				<input type="text" name="username" id="username" placeholder="<?=app::t('your unique name used for login');?>" />
			</div>
			<div class='row'>
				<label for="password"><?=app::t('password', 'titles');?></label>
				<input type="password" name='password' id="password" placeholder="<?=app::t('your password for login');?>" />
			</div>
			<div class='row'>
				<label for="email"><?=app::t('e-mail', 'titles');?></label>
				<input type="text" name="email" id="email" placeholder="your@email.com"/>
			</div>
		</fieldset>
		<fieldset>
			<legend><?=app::t('profile', 'titles');?></legend>
			<div class='row'>
				<label for='fullname'><?=app::t('fullname', 'titles');?></label>
				<input type="text" name="fullname" id="fullname" placeholder="<?=app::t('name surname');?>"  />
			</div>
			<div class="row">
				<label for="email"><?=app::t('sex', 'titles');?></label>
				<select name="sex" id="sex">
					<option value="male"><?=app::t('male', 'titles');?></option>
					<option value="female"><?=app::t('female', 'titles');?></option>
				</select>
			</div>
			<div class='row'>
				<label for="avatar"><?=app::t('avatar', 'titles');?></label>
				<input type="file" name="avatar" id="avatar" />
			</div>
			<progress value="0" max="100" style="width: 370px"></progress>
		</fieldset>
		<div class="parsley-error-list"></div>
		<input type="submit" value="<?=app::t('register', 'titles');?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?=app::app()->params['avatar_max_size'];?>" /> 
	</form>
</div>