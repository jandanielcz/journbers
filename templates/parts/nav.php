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
            <a href="/logout">Logout</a>
        </div>
    </div>
</nav>
<script>
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
        let tridotButton = document.querySelector('.tridots > span');
        tridotButton.addEventListener('click', (e) => {
            if (tridotButton.parentElement.classList.contains('open')) {
                tridotButton.parentElement.classList.remove('open')
            } else {
                tridotButton.parentElement.classList.add('open')
            }
        })
    })
</script>