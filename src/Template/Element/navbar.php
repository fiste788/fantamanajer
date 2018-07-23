<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="container">
    <div class="navbar-header">
        <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse" type="button">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <?php echo $this->Html->link('FantaManajer', ['_name' => 'home'], ['class' => ['navbar-brand', 'visible-xs']]) ?>
    </div>
    <nav role="navigation" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">

        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php if (!is_null($this->Session->read('Auth.User'))) :
                ?>
                <li id="account" class="dropdown">

                </li>
                <?php
            endif; ?>
            <li class="dropdown" id="login">
                <?php if (is_null($this->Session->read('Auth.User'))) :
                    ?>
                    <a class="dropdown-toggle" data-toggle="dropdown">Login</a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <?php echo $this->element('login'); ?>
                        </li>
                    </ul>
                    <?php
                else :
                    ?>
                    <?php echo $this->Html->link('Logout', ['_name' => 'logout'], ['class' => ['logout', 'entry'], 'title' => 'Logout']); ?>
                    <?php
                endif; ?>
            </li>
        </ul>
    </nav>
</div>
