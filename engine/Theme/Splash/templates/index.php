<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cogear->get('site.lang','en');?>">
<head>
    <?event('head')?>
    </head>
    <body>
        <?event('before')?>
        <?event('header')?>
        <?event('content')?>
        <?event('sidebar')?>
        <?event('footer')?>
        <?event('after')?>
    </body>
</html>