<?php 
$flag = FALSE;
foreach($this->pages as $key=>$val)
{
	if($val['roles'] <= $_SESSION['roles'])
	{
		if(isset($val['navbar']['main']))
			$appo[$val['navbar']['key']] = $val['navbar'];
		if($key == 'dettaglioSquadra' && $this->p == 'dettaglioSquadra' && $_GET['squadra'] != $_SESSION['idSquadra'])
		{
			$appo['squadre']['pages'][] = $this->p;
			$flag = TRUE;
		}
		$appo[$val['navbar']['key']]['pages'][] = $key;
	}
} 
if ($flag)
	unset ($appo['dettaglioSquadra']['pages'][0]);

if($_SESSION['logged'] != TRUE)
	unset ($appo['dettaglioSquadra']);
$sort_arr = array();
foreach($appo as $uniqid => $row)
	foreach($row as $key => $value)
		$sort_arr[$key][$uniqid] = $value;
array_multisort($sort_arr['order'] , SORT_ASC , $appo);
?>
<ul>
	<?php foreach($appo as $key=>$val):
		$selected = FALSE;
		//echo "<pre>" . print_r($val,1) . "</pre>";
		if(in_array($this->p,$val['pages'])) $selected = TRUE;
			if($selected): ?>
				<li class="selected">
			<?php else: ?>
				<li>
			<?php endif; ?>
			<div>
				<?php if($key == 'dettaglioSquadra'): ?>
				<a href="<?php echo Links::getLink($key,array('squadra'=>$_SESSION['idSquadra'])); ?>"><?php echo $val['title']; ?></a>
				<?php else: ?>
				<a href="<?php echo Links::getLink($key); ?>"><?php echo $val['title']; ?></a>
				<?php endif; ?>
				<?php if($selected && !isset($this->pages[$this->p]['navbar']['main'])): ?>
					<a class="son"> > </a>
					<a><?php echo $this->pages[$this->p]['navbar']['title']; ?></a>
				<?php endif; ?>
			</div>
			</li>
	<?php endforeach; ?>
</ul>
