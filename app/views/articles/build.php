<div class="mdl-container">
    <section class="mdl-grid mdl-grid--no-spacing mdl-shadow--4dp">
        <form class="form-horizontal" method="post" action="<?php echo $this->router->generate('articles_create'); ?>">
            <fieldset>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" maxlength="30" name="article[title]" id="title" value="<?php echo $this->article->title; ?>" />
                    <label class="mdl-textfield__label" for="name">Titolo *</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea rows="3" class="mdl-textfield__input" type="text" maxlength="75" name="article[subtitle]" id="subtitle"><?php echo $this->article->subtitle; ?></textarea>
                    <label class="mdl-textfield__label" for="name">Sottotitolo</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea rows="10" class="mdl-textfield__input" type="text" maxlength="1000" name="article[body]" id="body"><?php echo $this->article->body; ?></textarea>
                    <label class="mdl-textfield__label" for="name">Testo *</label>
                </div>
                <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                    Ok
                </button>
                <span class="help-block">(*) I campi contrassegnati con l'asterisco sono obbligatori</span>
            </fieldset>
        </form>
    </section>
</div>