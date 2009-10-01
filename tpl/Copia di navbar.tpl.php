<?php foreach($this->pages as $key=>$val)
{
	if(isset($val['navbar']['main']))
		$appo[$val['navbar']['key']] = $val['navbar'];
	$appo[$val['navbar']['key']]['pages'][] = $key;
} 
echo "<pre>".print_r($appo,1)."</pre>";
$sort_arr = array();
foreach($appo as $uniqid => $row)
	foreach($row as $key => $value)
		$sort_arr[$key][$uniqid] = $value;
		asort($sort_arr['order']);
		
		foreach($sort_arr['order'] as $key=>$val)
			$appo2[$key] = $appo[$key];
echo "<pre>".print_r($appo2,1)."</pre>"
?>
<ul>
	<?php foreach($this->pages as $key=>$val):
		$a = FALSE;
		if(in_array($this->p,$appo2[$val['navbar']['key']]['pages'])) $a = TRUE;
		if(isset($val['navbar']['main'])): 
			if($a): ?>
				<li class="selected">
			<?php else: ?>
				<li>
			<?php endif; ?>
			<div>
				<a href="<?php echo $this->linksObj->getLink($key); ?>"><?php echo $this->pages[$key]['navbar']['title']; ?></a>
				<?php if($a && !isset($this->pages[$this->p]['navbar']['main'])): ?>
					<a class="son"> > </a>
					<a><?php echo $this->pages[$this->p]['navbar']['title']; ?></a>
				<?php endif; ?>
			</div>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
	
</ul>
