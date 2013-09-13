<div id="card-player" class="clearfix">
	<h3><?php echo $this->giocatore; ?></h3>
	<div id="dati-gioc" class="clearfix">
        <figure>
            <img class="foto pull-left" alt="<?php echo $this->giocatore; ?>" src="<?php echo $this->pathFoto; ?>" />
        </figure>
		<div class="pull-left">
			<p><?php echo $this->ruoli[$this->giocatore->ruolo]; ?></p>
			<?php if($_SESSION['logged']): ?><p>Squadra: <?php echo $this->label; ?></p><?php endif; ?>
			<p>Presenze: <?php echo $this->giocatore->presente . " (" . $this->giocatore->presenzeVoto . ")"; ?></p>
			<p>Gol: <?php if($this->giocatore->ruolo != 'P') echo $this->giocatore->gol; elseif($this->giocatore->golSubiti == 0) echo $this->giocatore->golSubiti; else "-" . $this->giocatore->golSubiti; ?></p>
			<p>Assist: <?php echo $this->giocatore->assist; ?></p>
			<p>Media voti: <?php echo (!empty($this->giocatore->avgVoti)) ? $this->giocatore->avgVoti : ''; ?></p>
			<p>Media punti: <?php echo (!empty($this->giocatore->avgPunti)) ? $this->giocatore->avgPunti : ''; ?></p>
		</div>
		<?php if($this->giocatore->nomeClub != NULL): ?>
			<a class="pull-right hidden-xxs" href="<?php echo $this->router->generate('club_show',array('id'=>$this->giocatore->idClub)); ?>">
				<img height="50%" width="60" title="<?php echo $this->giocatore->nomeClub; ?>" class="shield" alt="<?php echo $this->giocatore->nomeClub; ?>" src="<?php echo $this->pathClub; ?>"/>
			</a>
		<?php endif; ?>
	</div>
</div>
<?php if(isset($this->giocatore->voti) && !empty($this->giocatore->voti)): ?>
    <div class="clearfix well">
        <table id="giornate" class="table" data-giocatore="<?php echo $this->giocatore; ?>">
            <thead>
                <tr>
                    <th><abbr title="Giornata">Giorn</abbr></th>
                    <th>Punti</th>
                    <th>Voti</th>
                    <th<?php if($this->giocatore->ruolo == "P") echo ' class="hidden-xxs"' ?>>Gol</th>
                    <th<?php if($this->giocatore->ruolo != "P") echo ' class="hidden-xxs"' ?>><abbr title="Gol subiti">Gol S</abbr></th>
                    <th>Assist</th>
                    <th class="hidden-xxs"><abbr title="Rigori">Rig</abbr></th>
                    <th class="hidden-xxs"><abbr title="Rigori subiti">Rig S</abbr></th>
                    <th><abbr title="Ammonito"><i class="ammonizione"></i></abbr></th>
                    <th><abbr title="Espulso"><i class="espulsione"></i></abbr></th>
                    <th><abbr title="Titolare">Tit</abbr></th>
                    <th class="hidden-xxs"><abbr title="Quotazione">Quot</abbr></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($this->giocatore->voti as $key => $val): ?>
                    <tr data-voto="[<?php echo $val->getIdGiornata() . "," . $val->getVoto(); ?>]" data-punti="[<?php echo $val->getIdGiornata() . "," . $val->getPunti(); ?>]">
                        <td><?php echo $val->getIdGiornata(); ?></td>
                        <td class="punti"><?php echo $val->getPunti(); ?></td>
                        <td class="voto"><?php echo ($val->getVoto() != '0') ? $val->getVoto() : "&nbsp;"; ?></td>
                        <td<?php if($this->giocatore->ruolo == "P") echo ' class="hidden-xxs"' ?>><?php echo $val->getGol(); ?></td>
                        <td<?php if($this->giocatore->ruolo != "P") echo ' class="hidden-xxs"' ?>><?php echo $val->getGolSubiti(); ?></td>
                        <td><?php echo $val->getAssist(); ?></td>
                        <td class="hidden-xxs"><?php echo $val->getRigoriSegnati(); ?></td>
                        <td class="hidden-xxs"><?php echo $val->getRigoriSubiti(); ?></td>
                        <td><?php if($val->isAmmonito()): ?><i class="glyphicon glyphicon-ok"></i><?php endif; ?></td>
                        <td><?php if($val->isEspulso()): ?><i class="glyphicon glyphicon-ok"></i><?php endif; ?></td>
                        <td><?php if($val->isTitolare()): ?><i class="glyphicon glyphicon-ok"></i><?php endif; ?></td>
                        <td class="hidden-xxs"><?php echo $val->getQuotazione(); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="grafico" class="hidden-xs">
        <div id="placeholder" style="height:300px"></div>
        <div id="overview" style="width:200px;height:100px"></div>
        <p>Seleziona sulla miniatura una parte di grafico per ingrandirla.</p>
        <p id="selection">&nbsp;</p>
        <a id="clear-selection" class="btn btn-danger">Cancella selezione</a>
    </div>
<?php endif; ?>
