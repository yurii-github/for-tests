<?php 
/* @var $this framework\View */
$this->addCss('assets/register.css');
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

// Show error message in form
// if no msg, hide
function show_error(msg)
{
	var errList = document.querySelector('div.parsley-error-list');
	errList.innerHTML = ''; // clear previous data
	
	if(msg == '') {
		errList.style['display'] = 'none';
	} else {
		var err = document.createElement('div');
		err.classList.add('parsley-error');
		err.innerHTML = msg;
		errList.appendChild(err);
		errList.style['display'] = 'inherit'; //show
	}
}


//http://stackoverflow.com/a/11654436
function UpdateQueryString(key, value, url) {
    if (!url) url = window.location.href;
    var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
        hash;

    if (re.test(url)) {
        if (typeof value !== 'undefined' && value !== null)
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        else {
            hash = url.split('#');
            url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
            if (typeof hash[1] !== 'undefined' && hash[1] !== null) 
                url += '#' + hash[1];
            return url;
        }
    }
    else {
        if (typeof value !== 'undefined' && value !== null) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?';
            hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (typeof hash[1] !== 'undefined' && hash[1] !== null) 
                url += '#' + hash[1];
            return url;
        }
        else
            return url;
    }
}
</script>

<?= $this->head(); ?>
</head>
<body>
<?= $content; ?>
</body>
</html>