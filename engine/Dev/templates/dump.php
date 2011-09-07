<?if(!$assets):?>
<style type="text/css">
    .cogear-root ul,
    .cogear-root li {
        list-style: none;
    }
    .dump-nest { display: none;}

    .dump-type {
        font-size: 11px;
        font-weight: normal;
        color: #999;
    }
    .dump-element-nested {
        cursor: pointer;
        border-right: 3px solid #333;
    }
    .cogear-root {
        background: #EEE;
        border-color: #CECECE;
        border-style: solid;
        border-width: 1px;
        padding: 1px;
        -khtml-border-radius: 4px;
        -o-border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
    .cogear-root .dump-item .dump-element {
        height: 22px;
        overflow: hidden;
    }
    .cogear-root .dump-inline {
        display: inline;
    }
</style>
<script type="text/javascript">

var debug = function() {};
// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

/**
* Add a CSS class to an HTML element
*
* @param HtmlElement el
* @param string className
* @return void
*/
debug.reclass = function(el, className) {
	if (el.className.indexOf(className) < 0) {
		el.className += (' ' + className);
		}
	}

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

/**
* Remove a CSS class to an HTML element
*
* @param HtmlElement el
* @param string className
* @return void
*/
debug.unclass = function(el, className) {
	if (el.className.indexOf(className) > -1) {
		el.className = el.className.replace(className, '');
		}
	}

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

/**
* Toggle the nodes connected to an HTML element
*
* @param HtmlElement el
* @return void
*/
debug.toggle = function(el) {
	var ul = el.parentNode.getElementsByTagName('ul');
	for (var i=0; i<ul.length; i++) {
		if (ul[i].parentNode.parentNode == el.parentNode) {
			ul[i].parentNode.style.display = (ul[i].parentNode.style.display == 'none')
				? 'block'
				: 'none';
			}
		}

	// toggle class
	//
	if (ul[0].parentNode.style.display == 'block') {
		debug.reclass(el, 'debug-opened');
		} else {
		debug.unclass(el, 'debug-opened');
		}
	}

/**
* Hover over an HTML element
*
* @param HtmlElement el
* @return void
*/
debug.over = function(el) {
	krumo.reclass(el, 'debug-hover');
	}

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

/**
* Hover out an HTML element
*
* @param HtmlElement el
* @return void
*/

debug.out = function(el) {
	krumo.unclass(el, 'debug-hover');
	}
</script>
<?endif;?>
<div class="cogear-root">
    <?=$dump?>
</div>