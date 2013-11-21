<div id="headerClub" class="clearfix">
	<img class="logo pull-left" alt="<?php echo $this->clubDett->id; ?>" src="<?php echo CLUBSURL . $this->clubDett->id . '.png'; ?>" title="Logo <?php echo $this->clubDett->nome; ?>" />
	<h2><?php echo $this->clubDett->nome; ?></h2>
</div>
<?php if(!empty($this->giocatori)): ?>
<div class="well">
	<h3>Giocatori</h3>
	<table class="table tablesorter">
		<thead>
			<tr>
				<th>Nome</th>
				<th>Ruolo</th>
                <th><abbr title="Partite giocate">PG</abbr></th>
                <th><abbr title="Media voto">MV</abbr></th>
                <th><abbr title="Media punti">MP</abbr></th>
				<th class="hidden-xs">Gol</th>
				<th class="hidden-xs">Gol subiti</th>
				<th class="hidden-xs">Assist</th>
				<th class="hidden-xs"><abbr title="Ammonito"><i class="ammonizione"></i></abbr></th>
                <th class="hidden-xs"><abbr title="Espulso"><i class="espulsione"></i></abbr></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->giocatori as $giocatore): ?>
                <tr>
                    <td>
                        <a href="<?php echo $this->router->generate("giocatore_show",array('id'=>$giocatore->id)); ?>"><?php echo $giocatore->cognome . ' ' . $giocatore->nome; ?></a>
                    </td>
                    <td><?php echo $giocatore->ruolo; ?></td>
                    <td><?php echo $giocatore->presente . " (" . $giocatore->presenzeVoto . ")"; ?></td>
                    <td><?php echo $giocatore->avgVoti ?></td>
                    <td><?php echo $giocatore->avgPunti ?></td>
                    <td class="hidden-xs"><?php echo $giocatore->gol ?></td>
                    <td class="hidden-xs"><?php echo $giocatore->golSubiti ?></td>
                    <td class="hidden-xs"><?php echo $giocatore->assist ?></td>
                    <td class="hidden-xs"><?php echo $giocatore->ammonizioni ?></td>
                    <td class="hidden-xs"><?php echo $giocatore->espulsioni ?></td>
                </tr>
			<?php endforeach; ?>
        </tbody>
        <tfoot>
			<tr>
				<td colspan="3">Totali</td>
				<td><?php echo $this->clubDett->avgVoti; ?></td>
				<td><?php echo $this->clubDett->avgPunti; ?></td>
				<td class="hidden-xs"><?php echo $this->clubDett->totaleGol; ?></td>
				<td class="hidden-xs"><?php echo $this->clubDett->totaleGolSubiti; ?></td>
				<td class="hidden-xs"><?php echo $this->clubDett->totaleAssist; ?></td>
				<td class="hidden-xs"><?php echo $this->clubDett->totaleAmmonizioni; ?></td>
				<td class="hidden-xs"><?php echo $this->clubDett->totaleEspulsioni; ?></td>
			</tr>
		</tfoot>
	</table>
</div>
<?php endif;?>
