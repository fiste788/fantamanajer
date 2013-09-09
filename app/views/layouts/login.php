<li class="dropdown" id="login">
    <?php if (!$_SESSION['logged']): ?>
        <a class="dropdown-toggle" data-toggle="dropdown">Login</a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <form action="<?php echo $this->router->generate('login'); ?>" method="post">
                    <fieldset>
                        <div class="form-group">
                            <label class="control-label" for="username">Username:</label>
							<input class="form-control" id="username" maxlength="12" type="text" name="username" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="password">Password:</label>
                            <input class="form-control" id="password" type="password" maxlength="12" name="password" />
                        </div>
                        <div class="checkbox">
                            <label for="remember">
                                <input id="remember" type="checkbox" name="remember" />Ricorda
                            </label>
                        </div>
                        <input class="btn btn-primary pull-right" type="submit" name="login" value="OK" />
                    </fieldset>
                </form>
            </li>
        </ul>
    <?php else: ?>
        <a class="logout entry" href="<?php echo $this->router->generate('logout'); ?>" title="Logout">Logout</a>
    <?php endif; ?>
</li>
