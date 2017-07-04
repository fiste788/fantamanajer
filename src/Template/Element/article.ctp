<article class="mdl-card mdl-shadow--4dp">
    <header class="mdl-card__title">
        <h2 class="mdl-card__title-text"><?php echo $article->title; ?></h2>
        <div class="mdl-card__subtitle-text">
            <em>
                <time><?php echo $article->created_at->format("Y-m-d H:i:s"); ?></time>
                <span class="pull-right">
                    <?php echo $article->team->name; ?>
                </span>
            </em>
            <?php echo $article->subtitle; ?>
        </div>
    </header>
    <div class="mdl-card__supporting-text">
        <?php echo nl2br($article->body); ?>
    </div>
    <div class="mdl-card__menu">
        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="menu-<?= $article->id ?>">
            <i class="material-icons">more_vert</i>
        </button>
        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-<?= $article->id ?>">
            <?php if (!is_null($this->request->session()->read('Auth.User')) && $article->isOwnedBy($this->request->session()->read('Auth.User.id'))): ?>
                <li class="mdl-menu__item">
                    <a class="mdl-navigation__link" href="<?php echo $this->Url->build(['action' => 'edit', $article->id]); ?>" title="Modifica"><i class="material-icons">edit</i>Modifica</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</article>