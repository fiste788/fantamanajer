<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $matchday
 */
?>
<?= json_encode(['success' => true, 'data' => isset($matchday) ? $matchday : []]);
