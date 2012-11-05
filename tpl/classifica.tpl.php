<?php $i = 1; ?>
<div id="classifica-container" class="column last">
    <table class="column table" style="width:300px;overflow:hidden;">
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
                    <td><?php echo $i; ?></td>
                    <td class="squadra no-wrap" id="squadra-<?php echo $key; ?>"><a href="<?php echo Links::getLink('dettaglioSquadra', array('squadra' => $key)) ?>"><?php echo $this->squadre[$key]->nomeSquadra; ?></a></td>
                    <td><?php echo array_sum($val); ?></td>
                </tr>
                <?php $i++;
                $flag = $key;
            endforeach; ?>
        </tbody>
    </table>
    <div id="tab_classifica" class="column last hidden-phone" style="height:<?php echo (27 * (count($this->classificaDett) + 1)) + 18; ?>px">
<?php $appo = array_keys($this->classificaDett);
$i = $appo[0]; ?>
                    <?php if (key($this->classificaDett[$flag]) != 0): ?>
            <table class="column table" style="width:<?php echo count($this->classificaDett[$i]) * 50; ?>px;margin:0;">
                <thead>
                    <tr>
    <?php foreach ($this->classificaDett[$flag] as $key => $val): ?>
                            <th style="width:35px"><?php echo $key; ?></th>
                    <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($this->classificaDett as $key => $val): ?>
                        <tr>
                            <?php foreach ($val as $secondKey => $secondVal): ?>
                                <td title="<?php echo 'Posizione nella giornata: ' . $this->posizioni[$secondKey][$key];
                                echo (isset($this->penalità[$key][$secondKey])) ? ' Penalità: ' . $this->penalità[$key][$secondKey] . ' punti' : ''; ?>"<?php echo (isset($this->penalità[$key][$secondKey])) ? ' class="rosso"' : ''; ?>>
                                    <a href="<?php echo Links::getLink('dettaglioGiornata', array('giornata' => $secondKey, 'squadra' => $key)); ?>"><?php echo $val[$secondKey]; ?></a>
                                </td>
                <?php endforeach; ?>
                        </tr>
        <?php $i++;
    endforeach; ?>
                </tbody>
            </table>
<?php endif; ?>
    </div>
</div>
<?php if (!empty($this->giornate)): ?>
    <div id="grafico" class="hidden-phone">
        <div id="placeholder" style="height:300px;"></div>
        <div id="overview" style="width:200px;height:100px;">&nbsp;</div>
        <p>Seleziona sulla miniatura una parte di grafico per ingrandirla. Per questa funzionalità si consiglia di usare browser come Safari, Firefox o Opera invece di altri meno performanti come Internet Explorer</p><p class="column" id="selection">&nbsp;</p>
        <div id="hidden" class="hidden"></div>
        <a id="clearSelection" class="hidden">(Cancella selezione)</a>
    </div>
    <script id="source" type="text/javascript">
        // <![CDATA[
    	var datasets = {
        <?php $i = 0;
        foreach ($this->classificaDett as $key => $val): $i++; ?>"<?php echo $key; ?>": {
                        label: "<?php echo $this->squadre[$key]->nomeSquadra; ?>",
                        data: [<?php foreach ($val as $secondKey => $secondVal): ?><?php echo '[' . $secondKey . ',' . $val[$secondKey] . ']';
            echo (count($secondVal) - $secondKey != $secondKey - 1) ? ',' : '';
        endforeach; ?>]
                    }<?php echo (count($this->classificaDett) != $i) ? ",\n" : ""; ?>
        <?php endforeach; ?>
            };
            var medie = {
    <?php $i = 0;
    foreach ($this->classificaDett as $key => $val): $i++; ?>
        <?php $media = array_sum($this->classificaDett[$key]) / count($this->classificaDett[$key]); ?>
                    "<?php echo $key; ?>" : {label: "Media <?php echo $this->squadre[$key]->nomeSquadra; ?> (<?php echo substr($media, 0, 5); ?>)",data: [[1,<?php echo $media; ?>],[<?php echo count($this->classificaDett[$key]); ?>,<?php echo $media; ?>]]}<?php echo (count($this->classificaDett) != $i) ? ",\n" : ""; ?>
    <?php endforeach; ?>
            };
            var squadra = {val:<?php echo ($_SESSION['logged'] == TRUE && $_SESSION['legaView'] == $_SESSION['idLega']) ? $this->squadre[$_SESSION['idUtente']]->id : 'false'; ?>};
            // ]]>
    </script>
<?php endif; ?>
