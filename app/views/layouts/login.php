<?php if (!$_SESSION['logged']): ?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet md-cell--4-col-phone">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">Login</h2>
                </div>

                <div class="mdl-card__supporting-text">
                    <form action="<?php echo $this->router->generate('login'); ?>" method="post">
                        <fieldset>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="email" maxlength="30" name="email" />
                                <label class="mdl-textfield__label" for="email">Email</label>
                            </div>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="password" maxlength="30" name="password" />
                                <label class="mdl-textfield__label" for="password">Password</label>
                            </div>

                            <div class="checkbox">
                                <label for="remember">
                                    <input id="remember" type="checkbox" name="remember" />Ricorda
                                </label>
                            </div>
                            <input name="login" value="Ok" type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <a class="mdl-navigation__link logout entry" href="<?php echo $this->router->generate('logout'); ?>" title="Logout">Logout</a>
<?php endif; ?>