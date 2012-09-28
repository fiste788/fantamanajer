<li class="pull-right dropdown" id="login">
    <?php if (!$_SESSION['logged']): ?>
        <a class="dropdown-toggle">Login</a>
        <ul class="dropdown-menu">
            <li>
                <form action="<?php echo Links::getLink('home'); ?>" method="post">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="username">Username:</label>
                            <div class="controls">
                                <input class="text" id="username" maxlength="12" type="text" name="username" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="password">Password:</label>
                            <div class="controls">
                                <input class="text" id="password" type="password" maxlength="12" name="password" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="checkbox" for="remember">
                                <input id="remember" type="checkbox" name="remember" />Ricorda
                            </label>
                        </div>
                        <input class="btn btn-primary right" type="submit" name="login" value="OK" />
                    </fieldset>
                </form>
            </li>
        </ul>
    <?php else: ?>
        <a class="logout entry" href="<?php echo Links::getLink('home', array('logout'=>'logout')); ?>" title="Logout">Logout</a>
    <?php endif; ?>
</li>
