<form action="<?php echo $this->router->generate('users_update') ?>" method="post">
    <fieldset>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="name" maxlength="15" name="user[name]" value="<?php echo $this->user->name ?>" />
            <label class="mdl-textfield__label" for="name">Name</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="surname" maxlength="15" name="user[surname]" value="<?php echo $this->user->surname ?>" />
            <label class="mdl-textfield__label" for="surname">Surname</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="email" id="email" name="user[email]" value="<?php echo $this->user->email ?>" />
            <label class="mdl-textfield__label" for="email">Email</label>
        </div>
        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="email_active">
            <input type="checkbox" id="email_active" class="mdl-switch__input" name="user[active_email]"<?php echo ($this->user->isActiveEmail()) ? ' checked="checked"' : '' ?> />
            <span class="mdl-switch__label">Ricevi email</span>
        </label>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="password" id="password" name="user[password]" />
            <label class="mdl-textfield__label" for="password">Password</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="password" id="password_repeat" name="user[password_repeat]" />
            <label class="mdl-textfield__label" for="password_repeat">Ripeti password</label>
        </div>
        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
            Ok
        </button>
    </fieldset>
</form>