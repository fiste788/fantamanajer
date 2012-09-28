<?php
require_once(INCDIR . 'jsmin.inc.php');
require_once(INCDIR . 'cssmin.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');
require_once(INCDIR . 'lessc.inc.php');

$jsContent = "var LOCAL = " . ((LOCAL) ? 'true' : 'false') . ";";
$jsContent .= "var FULLURL = '" . FULLURL . "';";
$jsContent .= "var JSURL = '" . JSURL . "';";
$jsContent .= "var AJAXURL = '" . AJAXURL . "';";
$jsContent .= "var IMGSURL = '" . IMGSURL . "';";
foreach($generalJs as $val)
	$jsContent .= file_get_contents(JSDIR . $val);

if(!LOCAL)
	$jsContent .= file_get_contents(JSDIR . 'googleAnalytics/googleAnalytics.js');

file_put_contents(JSDIR . 'combined/combined.js' , JSMin::minify($jsContent));
foreach($pages->pages as $key=>$page) {
	$jsContent = "";
	if(isset($page->js) && !empty($page->js)) {
		foreach($page->js as $directory=>$file) {
			if(is_array($file)) {
				foreach($file as $val)
					$jsContent .= file_get_contents(JSDIR . $directory . '/' . $val . '.js');
			} else
				$jsContent .= file_get_contents(JSDIR . $directory . '/' . $file . '.js');
		}
	}
	if(file_exists(JSDIR . 'pages/' . $key . '.js'))
		$jsContent .= file_get_contents(JSDIR . 'pages/' . $key . '.js');
	if(!empty($jsContent))
		file_put_contents(JSDIR . 'combined/' . $key . '.js' , JSMin::minify($jsContent));
}
$cssContent = "";
foreach($generalCss as $key=>$val) {
    $file = strpos($val, "/") ? substr($val, strpos($val, "/") + 1) : $val;
    $less_fname = LESSDIR . $val . ".less";
	$css_fname = CSSDIR . $file . ".css";
	$cache_fname = CACHEDIR . $file . ".cache";
    $cache = (file_exists($cache_fname)) ? unserialize(file_get_contents($cache_fname)) : $less_fname;
	$new_cache = lessc::cexecute($cache);
	if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
		file_put_contents($cache_fname, serialize($new_cache));
		file_put_contents($css_fname, $new_cache['compiled']);
	}
	lessc::ccompile($less_fname, $css_fname);
	$cssContent .= file_get_contents($css_fname);
}
file_put_contents(CSSDIR . 'combined.css' , cssmin::minify($cssContent));

$message->success("Js e css compressi con successo");
$contentTpl->assign('message',$message);
?>
