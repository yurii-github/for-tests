<?php
use framework\Application;

if (Application::$lang == 'uk_UA'): ?>
<h1>ПОМИЛКА-ЧУЧАЛО</h1>
Внутрішня помилка сервера. Вибачте за незручності. Адміна було повідомлено. Ми полагодимо збій як найшвидше
<?php else: ?>
<h1>DUMMY ERROR</h1>
Internal Server Error. Sorry for inconvinience. Admin is notified. We will repair the issue as soon as possible
<?php endif; ?>

<?php 
// this view is used for unhandled and fatal errors. probably you won't see it during reviewing this test application
//var_dump($error); -- extra stuff to do. unhandled errors
?>