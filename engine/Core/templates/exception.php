<?php
//@TODO need hard rework, not worked now.
    // Unique error identifier
    $error_id = uniqid('error');

?>
<style type="text/css">
    body {
        margin: 0;
        padding: 0;
    }

    #cogear_error {
        width: 960px;
        margin: 30px auto;
        background: #fefefe;
        font-size: 1em;
        font-family: sans-serif;
        text-align: left;
        color: #333;
    }

    #cogear_error .error-heading,
    #cogear_error h2 {
        margin: 0;
        padding: 0;
        font-size: 1em;
        font-weight: normal;

        background: #eee;
        background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#f5f5f5), to(#eeeeee));

        /* Safari 5.1, Chrome 10+ */
        background: -webkit-linear-gradient(top, #f5f5f5, #eeeeee);

        /* Firefox 3.6+ */
        background: -moz-linear-gradient(top, #f5f5f5, #eeeeee);

        /* IE 10 */
        background: -ms-linear-gradient(top, #f5f5f5, #eeeeee);

        /* Opera 11.10+ */
        background: -o-linear-gradient(top, #f5f5f5, #eeeeee);
        color: #222;
        text-shadow: 0px 1px 2px #fff;
        border-bottom: 1px solid #e5e5e5;
    }

    #cogear_error .error-heading {
        border-left: 6px solid #000;
    }

    #cogear_error .error-heading .message {
        border-left: 2px solid #ccc;
        padding: 1em;
    }

    #cogear_error h1 a,
    #cogear_error h2 a {
        color: #fff;
        text-shadow: 0px 1px 2px #333;
    }

    #cogear_error h2 {
        background: #222;
    }

    #cogear_error h3 {
        margin: 0;
        padding: 0.4em 0 0;
        font-size: 1em;
        font-weight: normal;
    }

    #cogear_error p {
        margin: 0;
        padding: 0.2em 0;
    }

    #cogear_error a {
        color: #1b323b;
    }

    #cogear_error pre {
        color: #111;
        overflow: auto;
        white-space: pre-wrap;
    }

    /*#cogear_error table {*/
        /*width: 100%;*/
        /*display: block;*/
        /*margin: 0 0 0.4em;*/
        /*padding: 0;*/
        /*border-collapse: collapse;*/
        /*background: #fff;*/
    /*}*/

    /*#cogear_error table td {*/
        /*border: solid 1px #ddd;*/
        /*text-align: left;*/
        /*vertical-align: top;*/
        /*padding: 0.4em;*/
    /*}*/

    #cogear_error div.content {
        padding: 0.4em 1em 1em;
        overflow: hidden;
    }

    #cogear_error pre.source {
        margin: 0 0 1em;
        padding: 0.4em;
        background: #fff;
        border: solid 1px #e3e9d1;
        line-height: 1.2em;

        box-shadow: 0 0 1px rgba(0, 0, 0, 0.4);
        -moz-box-shadow: 0 0 1px rgba(0, 0, 0, 0.4);
        -webkit-box-shadow: 0 0 1px rgba(0, 0, 0, 0.4);
    }

    #cogear_error pre.source code {
        overflow: auto;
    }

    #cogear_error pre.source span.line {
        display: block;
    }

    #cogear_error pre.source span.highlight {
        background: #f0eb96;
    }

    #cogear_error pre.source span.line span.number {
        color: #666;
    }

    #cogear_error ol.trace {
        display: block;
        margin: 0 0 0 2em;
        padding: 0;
        list-style: decimal;
    }

    #cogear_error ol.trace li {
        margin: 0;
        padding: 0;
    }

    .js .collapsed {
        display: none;
    }
</style>
<script type="text/javascript">
    document.documentElement.className = document.documentElement.className + ' js';
    function toggle(elem) {
        elem = document.getElementById(elem);

        if (elem.style && elem.style['display'])
        // Only works with the "style" attr
            var disp = elem.style['display'];
        else if (elem.currentStyle)
        // For MSIE, naturally
            var disp = elem.currentStyle['display'];
        else if (window.getComputedStyle)
        // For most other browsers
            var disp = document.defaultView.getComputedStyle(elem, null).getPropertyValue('display');

        // Toggle the state of the "display" style
        elem.style.display = disp == 'block' ? 'none' : 'block';
        return false;
    }
</script>
<div id="cogear_error">

    <div class="error-heading code-<?=strtolower($code)?>">
        <div class="message"><?=$message ?></div>
    </div>
    <?if (defined('DEVELOPMENT') && DEVELOPMENT): ?>
    <div id="<?=$error_id ?>" class="content">
        <p>
            <span class="file"><?=Dev::path($file) ?> [ <?=$line ?> ]</span>
        </p>

        <?=Dev::source($file, $line) ?>

        <ol class="trace">

            <?php foreach (Dev::trace($trace) as $i => $step): ?>
            <li>
                <p>
					<span class="file">
						<?php if ($step['file']): $source_id = $error_id . 'source' . $i; ?>
                        <a href="#<?=$source_id ?>"
                           onclick="return toggle('<?=$source_id ?>')"><?=Dev::path($step['file']) ?>
                            [ <?=$step['line'] ?> ]</a>
                        <?php else: ?>
                        {<?= 'PHP internal call' ?>}
                        <?php endif ?>
					</span>
                    &raquo;
                    <?=$step['function'] ?>(<?php if ($step['args']): $args_id = $error_id . 'args' . $i; ?><a
                        href="#<?=$args_id ?>"
                        onclick="return toggle('<?=$args_id ?>')"><?='arguments' ?></a><?php endif ?>)
                </p>
                <?php if (isset($args_id)): ?>
                <div id="<?=$args_id ?>" class="collapsed">
                    <table cellspacing="0">
                        <?php foreach ($step['args'] as $name => $arg): ?>
                        <tr>
                            <td><code><?=$name ?></code></td>
                            <td><?=Dev::dump($arg) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
                <?php endif ?>
                <?php if (isset($source_id)): ?>
                <pre id="<?=$source_id ?>" class="source collapsed"><code><?=$step['source'] ?></code></pre>
                <?php endif ?>
            </li>

            <?php unset($args_id, $source_id); ?>
            <?php endforeach ?>
        </ol>
    </div>

    <?=Dev::environment();?>
    <? endif;?>

</div>
