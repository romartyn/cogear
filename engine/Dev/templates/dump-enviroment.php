<?php
$env_id = uniqid('error');
?>

<style type="text/css">
    #environment-tab {
        display: block;
    }
    
    #files-tab,
    #gears-tab,
    #events-tab {
        display: none;
    }

    #debug-console {
        background: #f1f1f1;
        -khtml-border-radius: 8px;
        -o-border-radius: 8px;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        padding: 0 10px 10px;
    }

    #debug-content .debug-tab{
        padding: 10px;
    }

    #debug-content table {
        width: 100%;
    }

    #debug-panel {
        width: 960px;
        height: 25px;
        /*bottom: 0;*/
        /*position: fixed;*/
    }

    #debug-panel .active,
    .debug-tab {
        background: #fefefe;
    }
    
    #debug-panel ul {
        display: block;
        clear: both;
        margin: 0;
        padding: 0;
        font-size: 11px;
        line-height: 25px;
    }
    #debug-panel li {
        list-style: none;
        display: inline;
        float: left;
        height: 25px;
        padding: 2px 8px;
        margin-left: 10px;
    }
</style>

<div id="debug-console">
    <div id="debug-panel">
        <ul>
            <li><a href="http://cogear.org" target="_blank">Cogear <strong><?=COGEAR?></strong></a></li>
            <li class="active"><a href="#">Environment</a></li>
            <li><a href="#"><?=count($events)?> Events</a></li>
            <li><a href="#"><?=count($gears)?> Gears</a></li>
            <li><a href="#"><?=count($included) ?> Files</a></li>
            <li><a href="#">12 Queries</a></li>
        </ul>
    </div>
    
    <div id="debug-content">
        <div id="environment-tab" class="debug-tab">
        <?php foreach (array('_SESSION', '_GET', '_POST', '_FILES', '_COOKIE', '_SERVER') as $var): ?>

        <?php if (empty($GLOBALS[$var]) OR !is_array($GLOBALS[$var])) continue ?>
        <? $globid = $env_id . 'environment' . strtolower($var) ?>

            <div id="<?=$globid ?>">
                <h4>$<?=$var ?></h4>
                <table cellspacing="0">
                    <?php foreach ($GLOBALS[$var] as $key => $value): ?>
                    <tr>
                        <td><code><?=htmlspecialchars($key)?></code></td>
                        <td><?=Dev::dump($value) ?></td>
                    </tr>
                    <?php endforeach ?>
                </table>
            </div>

        <?php endforeach ?>
        </div>
        <div id="events-tab" class="debug-tab">
            <div id="<?=$env_id?>-events">
                <table cellspacing="0">
                    <?php foreach ($events as $eventname => $event): ?>
                    <tr>
                        <td><code><?=htmlspecialchars($eventname) ?></code></td>
                        <td><?=Dev::dump($event) ?></td>
                    </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
        <div id="gears-tab" class="debug-tab">
            <div id="<?=$env_id?>-gears">
                <table cellspacing="0">
                    <?php foreach ($gears as $gearname => $gear): ?>
                    <tr>
                        <td><code><?=htmlspecialchars($gearname) ?></code></td>
                        <td><?=Dev::dump($gear) ?></td>
                    </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
        <div id="files-tab" class="debug-tab">
            <div id="<?=$env_id ?>-files" class="half-side">
                <h4>Files</h4>
                <table cellspacing="0">
                    <?php foreach ($included as $file): ?>
                    <tr>
                        <td><?=Dev::path($file) ?></td>
                    </tr>
                    <?php endforeach ?>
                </table>
            </div>
            <div id="<?=$env_id?>-extensions" class="half-side" class="debug-tab">
                <h4>Extensions</h4>
                <table cellspacing="0">
                    <?php foreach ($extensions as $file): ?>
                    <tr>
                        <td><?=Dev::path($file) ?></td>
                    </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
    </div>
</div>