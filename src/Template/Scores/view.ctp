<h4>Punteggio: <span><?php echo (isset($score->real_points)) ? $score->real_points : ''; ?></span></h4>
<?php if (!is_null($score->penality)): ?>
    <div class="alert alert-error">
        <img class="" alt="!" src="<?php echo IMGSURL . 'penalita.png'; ?>" />
        <div class="penality">
            <h5>Penalità: <?php echo $score->penality_points; ?><br />Motivazione: <?php echo $score->penality; ?></h5>
        </div>
    </div>
<?php endif; ?>
<div>
    <?= $this->element('lineups',['dispositions'=>$regulars,'caption' => __('Titolari')]) ?>
</div>
<div>
    <?= $this->element('lineups',['dispositions'=>$notRegulars,'caption' => __('Panchinari')]) ?>
</div>
	
