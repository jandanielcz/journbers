<?php include 'parts/head.php' ?>
    <body class="light">
        <div id="Wrapper">
            <?php include 'parts/nav.php' ?>
            <ul class="messages">
                <?php foreach ($vars['f']->getMessages() as $one) {
                    printf("<li class='%s'>%s</li>", $one['type'], $one['text']);
                } ?>
            </ul>
            <section id="Entries">
                <?php \Tracy\Debugger::barDump($vars['trips']); ?>
                <?php foreach ($vars['trips'] as $trip) { ?>
                    <div class="trip">
                        T
                    </div>

                <?php } ?>
            </section>
        </div>
        <div id="Add">
            <a href="/add">&plus;</a>
        </div>
    </body>
</html>

