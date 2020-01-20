<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $message
 * @var array $params
 */
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<div class="<?= h($class) ?>"><?= h($message) ?></div>
