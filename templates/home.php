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

                    $nextTripStart = null;

                    foreach ($vars['trips'] as $trip) {
                    $iconBg = ColorTool::stringToColor($trip['driver_name']);

                    $classes = ['trip'];
                    if ($trip['end_odometer']) {
                        $classes[] = 'done';
                    }

                    if ($trip['driver'] === $vars['currentUser']) {
                        $classes[] = 'mine';
                    }

                ?>

                    <?php
                    if ($nextTripStart !== null && $nextTripStart !== $trip['end_odometer']) {
                        ?>
                            <div class="space">
                                <p><?= ($nextTripStart - $trip['end_odometer']) ?> <span class="unit">km</span></p>
                                <div class="actions">
                                    <button class="inline">New trip</button>
                                    <button class="inline">Add &darr;</button>
                                    <button class="inline">Add &uarr;</button>
                                </div>
                            </div>
                        <?php
                    }

                    ?>

                    <div class="<?= join(' ', $classes) ?>">
                        <div class="icon"
                             style="border-color: <?php echo ColorTool::stringToColor($trip['driver_name']) ?>"
                             title="<?= $trip['driver_name'] ?>">
                             <?php echo StringTool::nameInicials($trip['driver_name']) ?>
                        </div>
                        <div class="times">
                            <div class="start">
                                <?php echo $trip['start_date']->format('Y-m-d') ?>
                                <span>
                                    <?php echo $trip['start_date']->format('H:i') ?>
                                </span>
                            </div>
                            <div class="toSign">&mdash;</div>
                            <div class="end">
                                <?php echo $trip['end_date']->format('Y-m-d') ?>
                                <span>
                                    <?php echo $trip['end_date']->format('H:i') ?>
                                </span>
                            </div>
                        </div>
                        <div class="places">
                            <div class="start">
                                <?= $trip['start_place'] ?>
                            </div>
                            <div class="toSign">&rarr;</div>
                            <div class="target">
                                <?= $trip['target_place'] ?>
                            </div>
                            <?php
                                if ($trip['and_back']) {
                                    echo '<div class="andBack">&larrhk;</div>';
                                } else {
                                    echo '<div class="toSign">&rarr;</div>';
                                    printf('<div class="end">%s</div>', $trip['end_place']);
                                }
                            ?>
                        </div>
                        <div class="client">
                            <?= $trip['target_client'] ?>
                        </div>
                        <div class="note">
                            <?= $trip['note'] ?>
                        </div>
                        <div class="summary">
                            <div class="km">
                                <?= $trip['trip_length'] ?><span>KM</span>
                            </div>
                            <div class="hours">
                                <?= StringTool::durationToHtml($trip['trip_duration']) ?>
                            </div>
                        </div>
                        <div class="actions">
                            <button class="inline">Edit</button>
                        </div>
                    </div>



                <?php
                        $nextTripStart = $trip['start_odometer'];
                    }
                ?>
            </section>
        </div>
        <div id="Add">
            <a href="/add">Add trip</a>
        </div>
    </body>
</html>

