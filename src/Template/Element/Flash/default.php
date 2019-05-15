<?php
/**
 * @var \App\View\AppView $this
 */
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<div class="<?= h($class) ?>"><?= h($message) ?></div>
