<?php include 'parts/head.php' ?>
    <body class="light">
        <div id="Wrapper">
            <nav>
                <img src="/static/logo.svg" alt="Journbers logo">
                <h1>Journbers</h1>
                <div>
                    <a href="/">Home</a>
                </div>
                <div class="tridots">
                    <span>&vellip;</span>
                    <div class="submenu">
                        <a href="/logout">Logout</a>
                    </div>
                </div>
            </nav>
            <ul class="messages">
                <?php foreach ($vars['f']->getMessages() as $one) {
                    printf("<li class='%s'>%s</li>", $one['type'], $one['text']);
                } ?>
            </ul>
            <section id="Entries">
                Entries
            </section>
        </div>
        <script>
            'use strict'

            document.addEventListener('DOMContentLoaded', () => {
                let tridotButton = document.querySelector('.tridots > span')
                console.log(tridotButton)
                tridotButton.addEventListener('click', (e) => {
                    if (tridotButton.parentElement.classList.contains('open')) {
                        tridotButton.parentElement.classList.remove('open')
                    } else {
                        tridotButton.parentElement.classList.add('open')
                    }
                })
            })
        </script>
    </body>
</html>

