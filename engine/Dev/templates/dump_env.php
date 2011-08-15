<?php
$env_id = uniqid('error');
?>
<script type="text/javascript">
	var PQP_DETAILS = true;
	var PQP_HEIGHT = "short";

	addEvent(window, 'load', loadCSS);

	function changeTab(tab) {
		var pQp = document.getElementById('pQp');
		hideAllTabs();
		addClassName(pQp, tab, true);
	}

	function hideAllTabs() {
		var pQp = document.getElementById('pQp');
		removeClassName(pQp, 'console');
		removeClassName(pQp, 'speed');
		removeClassName(pQp, 'queries');
		removeClassName(pQp, 'memory');
		removeClassName(pQp, 'files');
	}

	function toggleDetails(){
		var container = document.getElementById('pqp-container');

		if(PQP_DETAILS){
			addClassName(container, 'hideDetails', true);
			PQP_DETAILS = false;
		}
		else{
			removeClassName(container, 'hideDetails');
			PQP_DETAILS = true;
		}
	}
	function toggleHeight(){
		var container = document.getElementById('pqp-container');

		if(PQP_HEIGHT == "short"){
			addClassName(container, 'tallDetails', true);
			PQP_HEIGHT = "tall";
		}
		else{
			removeClassName(container, 'tallDetails');
			PQP_HEIGHT = "short";
		}
	}

	function loadCSS() {
		var sheet = document.createElement("link");
		sheet.setAttribute("rel", "stylesheet");
		sheet.setAttribute("type", "text/css");
		sheet.setAttribute("href", "$cssUrl");
		document.getElementsByTagName("head")[0].appendChild(sheet);
		setTimeout(function(){document.getElementById("pqp-container").style.display = "block"}, 10);
	}


	//http://www.bigbold.com/snippets/posts/show/2630
	function addClassName(objElement, strClass, blnMayAlreadyExist){
	   if ( objElement.className ){
	      var arrList = objElement.className.split(' ');
	      if ( blnMayAlreadyExist ){
	         var strClassUpper = strClass.toUpperCase();
	         for ( var i = 0; i < arrList.length; i++ ){
	            if ( arrList[i].toUpperCase() == strClassUpper ){
	               arrList.splice(i, 1);
	               i--;
	             }
	           }
	      }
	      arrList[arrList.length] = strClass;
	      objElement.className = arrList.join(' ');
	   }
	   else{
	      objElement.className = strClass;
	      }
	}

	//http://www.bigbold.com/snippets/posts/show/2630
	function removeClassName(objElement, strClass){
	   if ( objElement.className ){
	      var arrList = objElement.className.split(' ');
	      var strClassUpper = strClass.toUpperCase();
	      for ( var i = 0; i < arrList.length; i++ ){
	         if ( arrList[i].toUpperCase() == strClassUpper ){
	            arrList.splice(i, 1);
	            i--;
	         }
	      }
	      objElement.className = arrList.join(' ');
	   }
	}

	//http://ejohn.org/projects/flexible-javascript-events/
	function addEvent( obj, type, fn ) {
	  if ( obj.attachEvent ) {
	    obj["e"+type+fn] = fn;
	    obj[type+fn] = function() { obj["e"+type+fn]( window.event ) };
	    obj.attachEvent( "on"+type, obj[type+fn] );
	  }
	  else{
	    obj.addEventListener( type, fn, false );
	  }
	}
</script>
<style type="text/css">
.green{color:#588E13 !important;}
.blue{color:#3769A0 !important;}
.purple{color:#953FA1 !important;}
.orange{color:#D28C00 !important;}
.red{color:#B72F09 !important;}

.half-side {
    width: 50%;
    float:left;
}

#pqp-metrics{
	background:#fefefe;
	width:100%;
}
#pqp-metrics td{
	height:80px;
	width:20%;
	text-align:center;
	cursor:pointer;
	border:1px solid #f4f4f4;
    border-top: 1px solid transparent;
	border-bottom:6px solid #cecece;
	/*-webkit-border-top-left-radius:10px;*/
	/*-moz-border-radius-topleft:10px;*/
	/*-webkit-border-top-right-radius:10px;*/
	/*-moz-border-radius-topright:10px;*/
}
#pqp-metrics td:hover{
	background:#fcfcfc;
	border-bottom:6px solid #000;
}
#pqp-metrics .green{
	border-left:none;
}
#pqp-metrics .red{
	border-right:none;
}

#pqp-metrics h4{
	text-shadow:#ccc 1px 1px 1px;
}


#pqp-footer{
	width:100%;
	background:#fefefe;
	font-size:11px;
	border-top:1px solid #ccc;
}
#pqp-footer td{
	padding:0 !important;
	border:none !important;
}
#pqp-footer strong{
	color:#ccc;
}
#pqp-footer a{
	color:#999;
	padding:5px 10px;
	text-decoration:none;
}
#pqp-footer .credit{
	width:20%;
	text-align:left;
}
#pqp-footer .actions{
	width:80%;
	text-align:right;
}
#pqp-footer .actions a{
	float:right;
	width:auto;
}
#pqp-footer a:hover, #pqp-footer a:hover strong, #pqp-footer a:hover b{
	background:#fff;
	color:blue !important;
	text-decoration:underline;
}
#pqp-footer a:active, #pqp-footer a:active strong, #pqp-footer a:active b{
	background:#ECF488;
	color:green !important;
}
    
</style>

<?php $included = get_included_files() ?>
<?php $extensions = get_loaded_extensions() ?>
<?php $gears = cogear()->gears; ?>
<?php $events = cogear()->events; ?>
<div id="debug-console">
<table id="pqp-metrics" cellspacing="0">
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

    <table id="pqp-footer" cellspacing="0">
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