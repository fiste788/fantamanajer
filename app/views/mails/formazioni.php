<html>
	<?php $i = 0; ?>
	<body style="font-family:'Trebuchet MS',Arial,sans-serif;overflow:hidden">
        <h2>
            <a style="color:#00a2ff;text-decoration:none;" title="Home" href="<?php echo FULLBASEURL ?>">FantaManajer</a>
        </h2>
		<div>
			<?php foreach ($this->squadre as $key => $squadra): ?>
				<?php if($i % 2 == 0): ?>
					<div style="margin: 0;clear:both;width:100%;float:left;">
				<?php endif; ?>
				<div style="float:left;width:50%;">
					<h3>
                        <a style="color:#00a2ff;text-decoration:none;" href="<?php echo FULLBASEURL . \Lib\Router::generate('formazione_show',array('giornata'=>$this->giornata,'squadra'=>$key)); ?>"><?php echo $squadra->nomeSquadra; ?></a>
					</h3>
				<?php if (isset($this->formazione[$key])): ?>
					<h4 style="color:#00a2ff;text-decoration:none;">Titolari</h4>
					<?php for($i = 0; $i < 11 ; $i++): ?>
                        <div>
                            <?php echo $this->giocatori[$key][$this->formazione[$key]->giocatori[$i]->idGiocatore]; ?>
                            <?php if($this->formazione[$key]->giocatori[$i]->idGiocatore == $this->formazione[$key]->idCapitano): ?>
                                <span>(C)</span>
                            <?php endif; ?>
                        </div>
					<?php endfor; ?>
					<?php if(isset($this->formazione[$key]->giocatori[11])): ?>
						<h4 style="color:#00a2ff;text-decoration:none;">Panchinari</h4>
						<?php for($i = 11; $i < 18 ; $i++): ?>
							<?php if(isset($this->formazione[$key]->giocatori[$i])): ?>
								<?php echo $this->giocatori[$key][$this->formazione[$key]->giocatori[$i]->idGiocatore]; ?>
							<?php endif; ?>
						<?php endfor; ?>
					<?php endif; ?>
				<?php else: ?>
					<p>Non ha settato la formazione</p>
				<?php endif; ?>
				</div>
				<?php $i++; ?>
				<?php if($i%2 == 0): ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<p style="font-size:12px;clear:both;float:left;width:100%;">
				Si prega di non rispondere a questa mail in quanto non verrà presa in considerazione.<br />
				Per domande o chiarimenti contatta gli amministratori all'indirizzo <a style="color:#00a2ff;text-decoration:none;" href="mailto:admin@fantamanajer.it">admin@fantamanajer.it</a>
			</p>
		</div>
	</body>
</html>


