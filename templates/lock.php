<?php include 'parts/head.php' ?>
    <body class="light">
        <div id="Wrapper">
            <?php include 'parts/nav.php' ?>
            <ul class="messages">
                <?php foreach ($vars['f']->messages() as $one) {
                    printf("<li class='%s'>%s</li>", $one['type'], $one['text']);
                } ?>
            </ul>
            <section id="LockForm">
                <p>
                    Currently all trips with odometer at start lower than
                    <strong><?= $vars['lockValue'] ?></strong> <span class="unit">km</span> or
                    exactly <?= $vars['lockValue'] ?> <span class="unit">km</span>
                    cannot be edited or removed
                    and all new changes or new trips should start with odometer value bigger than
                    <?= $vars['lockValue'] ?> <span class="unit">km</span>.
                </p>
                <form action="/c/lock" method="post">
                    <h3 class="addTripStart"><span>New lock odometer value:</span></h3>
                    <div class="row">
                        <label for="LockValue">Odometer</label>
                        <input type="number" name="LockValue" id="LockValue" value="<?= $vars['lockValue'] ?>">
                    </div>
                    <div class="row button">
                        <input type="submit" value="Save config">
                    </div>
                </form>
            </section>
        </div>
    </body>
</html>

