<form class="form-horizontal" action="<?php echo $this->router->generate('impostazioni_update') ?>" method="post">
	<fieldset>
		<div class="form-group">
			<label class="control-label">Nome lega</label>
			<input class="form-control" type="text" class="text" name="lega[nome]" maxlength="15" value="<?php echo $this->lega->nome; ?>"/>
		</div>
		<div class="form-group">
			<label class="control-label">Max trasferimenti</label>
            <input class="form-control" type="text" class="text" name="lega[numTrasferimenti]" placeholder="<?php echo isset($this->default['numTrasferimenti']) ? $this->default['numTrasferimenti'] : ''  ?>" value="<?php echo $this->lega->numTrasferimenti; ?>" />
		</div>
		<div class="form-group">
			<label class="control-label">Max selezione giocatori</label>
            <input class="form-control" type="text" class="text" name="lega[numSelezioni]" placeholder="<?php echo isset($this->default['numSelezioni']) ? $this->default['numSelezioni'] : ''; ?>" value="<?php echo $this->lega->numSelezioni; ?>" />
		</div>
		<div class="form-group">
			<label class="control-label">Minuti consegna anticipata formazione</label>
            <input class="form-control" type="text" class="text" name="lega[minFormazione]" placeholder="<?php echo isset($this->default['minFormazione']) ? $this->default['minFormazione'] : ''; ?>" value="<?php echo $this->lega->minFormazione; ?>" />
		</div>
		<div class="form-group">
			<label class="control-label">Percentuale sul punteggio se si dimentica la formazione</label>
            <input class="form-control" type="text" class="text" name="lega[punteggioFormazioneDimenticata]" placeholder="<?php echo isset($this->default['punteggioFormazioneDimenticata']) ? $this->default['punteggioFormazioneDimenticata'] : ''; ?>" value="<?php echo $this->lega->punteggioFormazioneDimenticata; ?>" />
		</div>
        <div class="form-group">
			<div class="checkbox">
				<input type="hidden" name="lega[capitano]" value="0"/>
				<label>
					<input type="checkbox" name="lega[capitano]" value="1"<?php echo ($this->lega->capitano) ? ' checked="checked"' : ''; ?> />Capitano doppio
				</label>
			</div>
            <?php if(isset($this->default['capitano'])): ?><span class="help-block">Default: <?php echo ($this->default['capitano'] == 1) ? "Si" : "No"; ?></span><?php endif; ?>
		</div>
		<div class="form-group">
			<div class="checkbox">
				<input type="hidden" name="lega[capitanoFormazioneDimenticata]" value="0"/>
				<label>
					<input type="checkbox" name="lega[capitanoFormazioneDimenticata]" value="1"<?php echo ($this->lega->capitanoFormazioneDimenticata) ? ' checked="checked"' : ''; ?> />Capitano doppio se si dimentica la formazione
				</label>
			</div>
            <?php if(isset($this->default['capitanoFormazioneDimenticata'])): ?><span class="help-block">Default: <?php echo ($this->default['capitanoFormazioneDimenticata'] == 1) ? "Si" : "No"; ?></span><?php endif; ?>
		</div>
		<div class="form-group">
			<div class="checkbox">
				<input type="hidden" name="lega[jolly]" value="0"/>
				<label>
					<input type="checkbox" name="lega[jolly]" value="1"<?php echo ($this->lega->jolly) ? ' checked="checked"' : ''; ?> />Uso del jolly per raddoppiare il punteggio una volta a girone
				</label>
			</div>
            <?php if(isset($this->default['jolly'])): ?><span class="help-block">Default: <?php echo ($this->default['jolly'] == 1) ? "Si" : "No"; ?></span><?php endif; ?>
		</div>
		<input type="submit" class="btn btn-primary" name="submit" value="OK" />
	</fieldset>
</form>
