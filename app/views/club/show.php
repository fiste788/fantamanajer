<div id="headerClub" class="clearfix">
	<img class="logo left" alt="<?php echo $this->clubDett->id; ?>" src="<?php echo CLUBSURL . $this->clubDett->id . '.png'; ?>" title="Logo <?php echo $this->clubDett->nome; ?>" />
	<h2><?php echo $this->clubDett->nome; ?></h2>
</div>
<?php if(!empty($this->giocatori)): ?>
<div class="well">
	<h3>Giocatori</h3>
	<table class="table">
		<thead>
			<tr>
				<th>Nome</th>
				<th class="center">Ruolo</th>
				<th class="center">Club</th>
				<th class="center">PG</th>
				<th class="center">MVoti</th>
				<th class="center">MPunti</th>
				<th class="center">Gol</th>
				<th class="center">Gol subiti</th>
				<th class="center">Assist</th>
				<th class="center">Ammonizioni</th>
				<th class="center">Esplusioni</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->giocatori as $giocatore): ?>
                <tr class="tr <?php if(empty($giocatore->idClub)) echo 'rosso'; ?>">
                    <td title="" class="name">
                        <a href="<?php echo $this->router->generate("giocatore_show",array('id'=>$giocatore->id)); ?>"><?php echo $giocatore->cognome . ' ' . $giocatore->nome; ?></a>
                    </td>
                    <td><?php echo $giocatore->ruolo; ?></td>
                    <td><?php echo (!empty($giocatore->nomeClub)) ? strtoupper(substr($giocatore->nomeClub,0,3)) : "&nbsp;"; ?></td>
                    <td><?php echo $giocatore->presente . " (" . $giocatore->presenzeVoto . ")"; ?></td>
                    <td><?php echo $giocatore->avgVoti ?></td>
                    <td><?php echo $giocatore->avgPunti ?></td>
                    <td><?php echo $giocatore->gol ?></td>
                    <td><?php echo $giocatore->golSubiti ?></td>
                    <td><?php echo $giocatore->assist ?></td>
                    <td><?php echo $giocatore->ammonizioni ?></td>
                    <td><?php echo $giocatore->espulsioni ?></td>
                </tr>
			<?php endforeach; ?>
			<tr>
				<td colspan="4">Totali</td>
				<td><?php echo $this->clubDett->avgVoti; ?></td>
				<td><?php echo $this->clubDett->avgPunti; ?></td>
				<td><?php echo $this->clubDett->totaleGol; ?></td>
				<td><?php echo $this->clubDett->totaleGolSubiti; ?></td>
				<td><?php echo $this->clubDett->totaleAssist; ?></td>
				<td><?php echo $this->clubDett->totaleAmmonizioni; ?></td>
				<td><?php echo $this->clubDett->totaleEspulsioni; ?></td>
			</tr>
		</tbody>
	</table>
</div>
<?php endif;?>
