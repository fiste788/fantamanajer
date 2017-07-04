<?php if(!$this->request->session()->read('logged')): ?>
    <a class="mdl-navigation__link" href="<?php echo $this->Url->build(['_name' =>'login']) ?>">Login</a>
<?php else: ?>
    <a class="mdl-navigation__link mdl-navigation__link--current" href="<?php echo $this->Url->build(['_name'=>'teams_view','id' =>$this->request->session()->read('Team')->id]) ?>"><?= $this->request->session()->read('Team')->name ?></a>
<?php endif; ?>
    <a class="mdl-navigation__link mdl-collapse__button" href="<?php echo $this->Url->build(['_name'=>'championships_view','id' => $currentChampionship->id]) ?>">
        <?= $currentChampionship->league->name; ?>
    </a>
<!-- div class="mdl-collapse"> 
    
    <div class="mdl-collapse__content-wrapper">
        <div class="mdl-collapse__content mdl-animation--default">
            <a class="mdl-navigation__link" href="<?= $this->Url->build(['_name' =>'teams_index']) ?>">Le squadre</a>
            <a class="mdl-navigation__link" href="<?= $this->Url->build(['controller'=>'Articles','action' => 'view']) ?>">Conferenze stampa</a>
            <a class="mdl-navigation__link" href="<?= $this->Url->build('ranking') ?>">Classifica</a>
            <a class="mdl-navigation__link" href="<?= $this->Url->build('free_players') ?>">Giocatori liberi</a>
        </div>
    </div>
</div-->
<div class="mdl-collapse"> 
    <a class="mdl-navigation__link mdl-collapse__button">
        <i class="material-icons mdl-collapse__icon mdl-animation--default">expand_more</i>
        Clubs di A
    </a>
    <div class="mdl-collapse__content-wrapper">
        <div class="mdl-collapse__content mdl-animation--default">
            <a class="mdl-navigation__link" href="<?= $this->Url->build(['_name' =>'clubs_index']) ?>">Clubs di A</a>
            <a class="mdl-navigation__link" href="/fantamanajer-new/probabili_formazioni">Probabili formazioni</a>
        </div>
    </div>
</div>
<?php if($this->request->session()->read('logged')): ?>
    <div class="mdl-collapse"> 
        <a class="mdl-navigation__link mdl-collapse__button">
            <i class="material-icons mdl-collapse__icon mdl-animation--default">expand_more</i>
            Area admin
        </a>
        <div class="mdl-collapse__content-wrapper">
            <div class="mdl-collapse__content mdl-animation--default">
                <a class="mdl-navigation__link" href="/fantamanajer-new/crea_squadra">Crea una nuova squadra</a>
                <a class="mdl-navigation__link" href="/fantamanajer-new/gestione_database">Gestione database</a>
                <a class="mdl-navigation__link" href="/fantamanajer-new/lancia_script">Lancia script</a>
                <a class="mdl-navigation__link" href="/fantamanajer-new/trasferimento/new">Nuovo trasferimento</a>
                <a class="mdl-navigation__link" href="/fantamanajer-new/giornata">Giornata</a>
                <a class="mdl-navigation__link" href="/fantamanajer-new/lineups/insert_old">Inserisci formazione mancante</a>
                <a class="mdl-navigation__link" href="/fantamanajer-new/newsletter">Newsletter</a>
                <a class="mdl-navigation__link" href="/fantamanajer-new/penalità">Penalità</a>
                <a class="mdl-navigation__link" href="/fantamanajer-new/impostazioni">Impostazioni lega</a>
            </div>
        </div>
    </div>
    <div class="mdl-collapse"> 
        <a class="mdl-navigation__link mdl-collapse__button">
            <i class="material-icons mdl-collapse__icon mdl-animation--default">expand_more</i>
            Altro...
        </a>
        <div class="mdl-collapse__content-wrapper">
            <div class="mdl-collapse__content mdl-animation--default">
                <a class="mdl-navigation__link" href="<?= $this->Url->build(['_name' =>'events']) ?>">Vedi gli eventi</a>
                <a class="mdl-navigation__link" href="/fantamanajer-new/about">About</a>
                <a class="mdl-navigation__link" href="/fantamanajer-new/download">Downloads</a>
            </div>
        </div>
    </div>

    <a class="mdl-navigation__link" id="notifiche" data-toggle="dropdown">
        <span class="mdl-badge" data-badge="1">Notifiche</span>
    </a>
    <div class="mdl-menu__container">
    </div>
    <a class="mdl-navigation__link logout entry" href="<?= $this->Url->build(['_name' =>'logout']) ?>" title="Logout">Logout</a>
<?php endif; ?>