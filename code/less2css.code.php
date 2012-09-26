<?php
require_once(INCDIR . 'lessc.inc.php');

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
	$generalCss[$key] = $file . '.css';
}
?>
