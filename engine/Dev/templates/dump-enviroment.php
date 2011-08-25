<?php
$env_id = uniqid('error');
?>

<div id="debug-console">
<table id="cogear-metrics" cellspacing="0">
<tr>
	<td onclick="changeTab('console');">
		<var>$logCount</var>
		<h4>Console</h4>
	</td>
	<td onclick="changeTab('speed');">
		<var>Engine</var>
		<h4>Environment</h4>
	</td>
	<td onclick="changeTab('queries');">
		<var><?=count($events)?> Events</var>
		<h4>Defined</h4>
	</td>
	<td onclick="changeTab('memory');">
		<var><?=count($gears)?> Gears</var>
		<h4>Loaded</h4>
	</td>
	<td class="red" onclick="changeTab('files');">
		<var><?=count($included) ?> Files</var>
		<h4>Included</h4>
	</td>
</tr>
</table>

<div id="environment-tab">
<?php foreach (array('_SESSION', '_GET', '_POST', '_FILES', '_COOKIE', '_SERVER') as $var): ?>

<?php if (empty($GLOBALS[$var]) OR !is_array($GLOBALS[$var])) continue ?>
<? $globid = $env_id . 'environment' . strtolower($var) ?>

    <div id="<?=$globid ?>" class="collapsed">
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
<div id="events-tab">
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
<div id="gears-tab">
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
<div id="files-tab">
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
    <div id="<?=$env_id?>-extensions" class="half-side">
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

    <table id="cogear-footer" cellspacing="0">
        <tr>
            <td class="credit">
                <a href="http://cogear.ru" target="_blank">
                Cogear <strong><?=COGEAR?></strong></a></td>
            <td class="actions">
                <a href="#" onclick="toggleDetails();return false">Details</a>
                <a class="heightToggle" href="#" onclick="toggleHeight();return false">Height</a>
            </td>
        </tr>
    </table>
</div>