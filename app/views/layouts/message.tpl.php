<?php if(isset($_SESSION['__flash'])): ?>
	<?php switch($_SESSION['__flash']->level):
        case 0: ?>
			<div title="Clicca per nascondere" id="messaggio" class="alert alert-info"><?php echo $_SESSION['__flash']->text; ?></div>
		<?php break; case 1: ?>
			<div title="Clicca per nascondere" id="messaggio" class="alert alert-success"><?php echo $_SESSION['__flash']->text; ?></div>
		<?php break; case 2: ?>
			<div title="Clicca per nascondere" id="messaggio" class="alert alert-notice"><?php echo $_SESSION['__flash']->text; ?></div>
		<?php break; case 3: ?>
			<div title="Clicca per nascondere" id="messaggio" class="alert alert-error"><?php echo $_SESSION['__flash']->text; ?></div>
	<?php endswitch; ?>
<?php endif; ?>
