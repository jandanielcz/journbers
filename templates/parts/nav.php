<nav>
    <img src="/static/logo.svg" alt="<?= $vars['common.appName'] ?> logo">
    <h1><a href="/"><?= $vars['common.appName'] ?></a></h1>
    <div class="carSelector">
        <ol>
            <li>Golf</li>
        </ol>
    </div>
    <div>
        <?php //menu? ?>
    </div>
    <div class="tridots">
        <span>&vellip;</span>
        <div class="submenu">
            <?php if ($vars['common.user']->hasRole('warden')) { ?>
            <a href="/c/lock">Lock trips</a>
            <?php } ?>
            <a href="/logout">Logout</a>
        </div>
    </div>
</nav>
<script src="/static/nav.js"></script>