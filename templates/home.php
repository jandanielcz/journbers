<?php

include 'parts/head.php';

use Journbers\Tool\ColorTool;
use Journbers\Tool\StringTool;
?>
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
                <?php
                    foreach ($vars['trips'] as $trip) {
                    $iconBg = ColorTool::stringToColor($trip['driver_name']);
                    $tripStart = new \DateTimeImmutable($trip['start_date']);
                ?>
                    <div class="trip">
                        <div class="icon <?= (ColorTool::isDark($iconBg)) ? 'dark' : 'light' ?>"
                             style="background-color: <?php echo ColorTool::stringToColor($trip['driver_name']) ?>"
                             title="<?= $trip['driver_name'] ?>">
                             <?php echo StringTool::nameInicials($trip['driver_name']) ?>
                        </div>
                        <div class="times">
                            <div class="start">
                                <?php //TODO: Too hardcoded :( ?>
                                <?php echo $tripStart->format('j. n. Y') ?>
                                <span>
                                    <?php echo $tripStart->format('H:i') ?>
                                </span>
                            </div>
                            <div class="toSign">&mdash;</div>
                            <div class="end"></div>
                        </div>
                    </div>

                <?php } ?>
            </section>
        </div>
        <div id="Add">
            <a href="/add">&plus;</a>
        </div>
    </body>
</html>

