<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?php if (!$this->request->session()->read('logged')): ?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet md-cell--4-col-phone">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">Login</h2>
                </div>

                <div class="mdl-card__supporting-text">
                    <?= $this->Form->create(); ?>
                        <fieldset>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <?= $this->Form->text('email',['class' => 'mdl-textfield__input']) ?>
                                <label class="mdl-textfield__label" for="email">Email</label>
                            </div>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <?= $this->Form->password('password',['class' => 'mdl-textfield__input']) ?>
                                <label class="mdl-textfield__label" for="password">Password</label>
                            </div>
                            <div class="checkbox">
                                <label for="remember">
                                    <input id="remember" type="checkbox" name="remember" />Ricorda
                                </label>
                            </div>
                        </fieldset>
                        <?= $this->Form->button(__('Login'),['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']); ?>
                    <?= $this->Form->end() ?>
                    
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php echo $this->Html->link(
    'Logout',
    '/logout',
    ['class' => 'mdl-navigation__link logout entry']); ?>
<?php endif; ?>