<?php $i = 1; ?>
<div id="classifica-container" data-squadra="<?php echo ($_SESSION['logged'] == TRUE && $_SESSION['legaView'] == $_SESSION['idLega']) ? $this->squadre[$_SESSION['idUtente']]->id : 'false'; ?>">
    <table class="table">
        <thead>
            <tr>
                <th>P.</th>
                <th class="nowrap">Nome</th>
                <th class="nowrap"><abbr title="Punti totali">PTot</abbr></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->classificaDett as $key => $val): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td class="squadra no-wrap" id="squadra-<?php echo $key; ?>"><a href="<?php echo $this->router->generate('squadra_show', array('id' => $key)) ?>"><?php echo $this->squadre[$key]->nomeSquadra; ?></a></td>
                    <td><?php echo array_sum($val); ?></td>
                </tr>
            <?php $flag = $key; endforeach; ?>
        </tbody>
    </table>

    <?php if (key($this->classificaDett[$flag]) != 0): ?>
        <div id="tab_classifica">
            <table class="table">
                <thead>
                    <tr>
                        <?php foreach ($this->classificaDett[$flag] as $key => $val): ?>
                            <th class="<?php if((count($this->classificaDett[$flag]) - $key) > 1) echo ' hidden-xs';if((count($this->classificaDett[$flag]) - $key) > 4) echo ' hidden-phone';if((count($this->classificaDett[$flag]) - $key) > 9) echo ' hidden-tablet'; ?>"><?php echo $key; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->classificaDett as $key => $val): ?>
                        <tr data-media="<?php echo array_sum($val) / count($val) ?>" data-key="<?php echo $key; ?>" data-squadra="<?php echo trim($this->squadre[$key]->nomeSquadra); ?>">
                            <?php foreach ($val as $secondKey => $secondVal): ?>
                                <td class="<?php if((count($this->classificaDett[$flag]) - $secondKey) > 1) echo ' hidden-xs';if((count($this->classificaDett[$flag]) - $secondKey) > 4) echo ' hidden-phone';if((count($this->classificaDett[$flag]) - $secondKey) > 9) echo ' hidden-tablet'; echo (isset($this->penalità[$key][$secondKey])) ? "rosso" : ''; ?>" title="<?php echo 'Posizione nella giornata: ' . $this->posizioni[$secondKey][$key]; echo (isset($this->penalità[$key][$secondKey])) ? ' Penalità: ' . $this->penalità[$key][$secondKey] . ' punti' : ''; ?>">
                                    <a href="<?php echo $this->router->generate('punteggio_show', array('idGiornata' => $secondKey, 'idUtente' => $key)); ?>"><?php echo $val[$secondKey]; ?></a>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php if (!empty($this->giornate)): ?>
    <div id="grafico" class="hidden-phone">
        <div id="placeholder" style="height:300px"></div>
        <div id="overview" style="width:200px;height:100px"></div>
        <p>Seleziona sulla miniatura una parte di grafico per ingrandirla.</p>
        <p id="selection">&nbsp;</p>
        <a id="clear-selection" class="btn btn-danger">Cancella selezione</a>
    </div>
<?php endif; ?>
