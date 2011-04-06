<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$cogear->get('site.lang','en');?>">
<head>
    <?event('head')?>
    </head>
    <body>
        <?event('before')?>
        <div class="container_12">
            <div class="grid_12" id="header">
                ><?event('header')?>
            </div>
            <div class="grid_9" id="content">
                ><?event('content')?>
            </div>
            <div class="grid_3" id="sidebar">
                ><?event('sidebar')?>
            </div>
            <div class="grid_12" id="footer"><?event('footer')?></div>
        </div>
        <?event('after')?>
    </body>
</html>