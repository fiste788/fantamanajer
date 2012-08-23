<?php 
require_once(INCDIR . 'lessc.inc.php');

foreach($generalCss as $key=>$val) {
	$less_fname = LESSDIR . $val . ".less";
	$css_fname = CSSDIR . $val . ".css";
	$cache_fname = CACHEDIR . $val . ".cache";
	if (file_exists($cache_fname)) {
		$cache = unserialize(file_get_contents($cache_fname));
	} else {
		$cache = $less_fname;
	}

	$new_cache = lessc::cexecute($cache);
	if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
		file_put_contents($cache_fname, serialize($new_cache));
		file_put_contents($css_fname, $new_cache['compiled']);
	}
	lessc::ccompile(CSSDIR . 'less/' . $val . '.less', CSSDIR . $val . '.css');
	$generalCss[$key] = $val . '.css';
}
?>
