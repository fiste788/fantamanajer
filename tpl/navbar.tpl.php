<?php 
$flag = FALSE;
foreach($this->pages as $key=>$val)
{
	if($val['roles'] <= $_SESSION['roles'])
	{
		if(isset($val['navbar']['main']))
			$appo[$val['navbar']['key']] = $val['navbar'];
		if($key == 'dettaglioSquadra' && $this->p == 'dettaglioSquadra' && $_GET['squadra'] != $_SESSION['idUtente'])
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
				<a href="<?php echo Links::getLink($key,array('squadra'=>$_SESSION['idUtente'])); ?>"><?php echo $val['title']; ?></a>
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
<div id="rightNavbar">
	<?php if(count($this->leghe) > 1): ?>
		<?php $appo = $_GET; unset($appo['p']); ?>
		<form class="entry" action="<?php echo Links::getLink($this->p,$appo); ?>" method="post">
			<fieldset>
				<label class="lega" for="legaView">Lega:</label>
				<select id="legaView" onchange="this.form.submit();" name="legaView">
					<?php foreach($this->leghe as $key=>$value): ?>
						<option <?php echo ($_SESSION['legaView'] == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $value->getNome(); ?></option>
					<?php endforeach; ?>
				</select>
			</fieldset>
		</form>
	<?php endif; ?>
	<?php if($_SESSION['logged']): ?>
		<div id="account" class="entry">

		</div>
	<?php endif; ?>
	<?php require_once(TPLDIR . "login.tpl.php") ?>
</div>