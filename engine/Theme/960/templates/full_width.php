<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cogear->get('site.lang','en');?>">
<head>
        <?event('head')?>
    </head>
    <body>
        <?event('body.before')?>
        <?event('body.before')?>
        <div class="container_12">
            <div class="grid_12" id="header">
               <?event('header')?>
            </div>
            <div class="grid_12" id="content">
                <?event('content')?>
            </div>
            <div class="grid_12" id="footer"><?event('footer')?></div>
        </div>
        <?event('body.after')?>
    </body>
</html>