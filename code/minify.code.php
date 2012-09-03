<?php
require_once(INCDIR . 'min.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');
require_once(INCDIR . 'lessc.inc.php');

$jsContent = "";
foreach($generalJs as $val)
	$jsContent .= file_get_contents(JSDIR . $val);

if(!LOCAL)
	$jsContent .= file_get_contents(JSDIR . 'googleAnalytics/googleAnalytics.js');

file_put_contents(JSDIR . 'combined/combined.js' , JSMin::minify($jsContent));

foreach($pages as $key=>$val) {
	$jsContent = "";
	if(isset($val['js']) && !empty($val['js'])) {
		foreach($val['js'] as $directory=>$file) {
			if(is_array($file)) {
				foreach($file as $val) {
					$appo = explode('|',$val);
					if(isset($appo[1]))
						$jsContent .= file_get_contents(JSDIR . $directory . '/' . $appo[1] . '.js');
					else
						$jsContent .= file_get_contents(JSDIR . $directory . '/' . $val . '.js');
				}
			} else {
				$appo = explode('|',$file);
				if(isset($appo[1]))
					$jsContent .= file_get_contents(JSDIR . $directory . '/' . $appo[1] . '.js');
				else
					$jsContent .= file_get_contents(JSDIR . $directory . '/' . $file . '.js');
			}
		}
	}
	if(file_exists(JSDIR . 'pages/' . $key . '.js'))
		$jsContent .= file_get_contents(JSDIR . 'pages/' . $key . '.js');
	if(!empty($jsContent))
		file_put_contents(JSDIR . 'combined/' . $key . '.js' , JSMin::minify($jsContent));
}
$cssContent = "";
foreach($generalCss as $key=>$val) {
    $less_fname = LESSDIR . $val . ".less";
	$css_fname = CSSDIR . $val . ".css";
	$cache_fname = CACHEDIR . $val . ".cache";
	if (file_exists($cache_fname))
		$cache = unserialize(file_get_contents($cache_fname));
	else
		$cache = $less_fname;
    $new_cache = lessc::cexecute($cache);
	if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
		file_put_contents($cache_fname, serialize($new_cache));
		file_put_contents($css_fname, $new_cache['compiled']);
	}
	lessc::ccompile($less_fname, $css_fname);
	$cssContent .= file_get_contents($css_fname);
}
file_put_contents(CSSDIR . 'combined.css' , cssmin::minify($cssContent));
file_put_contents(DOCSDIR . 'staticVersion.txt' , VERSION + 1);

$message->success("Js e css compressi con successo");
$contentTpl->assign('message',$message);
?>
