<?php

include 'parts/head.php';

use Journbers\Tool\ColorTool;
use Journbers\Tool\StringTool;

?>
    <body class="light">
        <div id="Wrapper">
            <?php include 'parts/nav.php' ?>
            <ul class="messages">
                <?php foreach ($vars['f']->messages() as $one) {
                    printf("<li class='%s'>%s</li>", $one['type'], $one['text']);
                } ?>
            </ul>
            <section id="Entries">
                <?php \Tracy\Debugger::barDump($vars['trips']); ?>
                <?php

                $p = new Parsedown();

                $nextTripStart = null;
                $nextTripStartTime = null;
                $nextTripId = null;

                foreach ($vars['trips'] as $trip) {
                    $iconBg = ColorTool::stringToColor($trip['driver_name']);

                    $classes = ['trip'];
                    if ($trip['end_odometer'] && $trip['end_date'] && ($trip['end_place'] || $trip['and_back'])) {
                        $classes[] = 'done';
                    }

                    if ($trip['driver'] === $vars['currentUser']) {
                        $classes[] = 'mine';
                    }

                    if ($trip['is_personal']) {
                        $classes[] = 'personal';
                    }

                    if ($trip['driver'] !== $trip['added_by']) {
                        $isEdited = true;
                    } else {
                        $isEdited = false;
                    }
                    ?>

                    <?php
                    if ($nextTripStart !== null && $nextTripStart !== $trip['end_odometer']) {
                        ?>
                            <div class="space">
                                <p><?= ($nextTripStart - $trip['end_odometer']) ?> <span class="unit">km</span></p>
                                <div class="actions">
                                    <form action="/fill-space" method="post">
                                        <input type="hidden" name="SpaceStart" value="<?= $trip['end_odometer'] ?>">
                                        <input type="hidden" name="SpaceEnd" value="<?= $nextTripStart ?>">
                                        <input type="hidden" name="SpaceMaxTime" value="<?= $nextTripStartTime->format('Y-m-d H:i') ?>">
                                        <input type="hidden" name="SpaceMinTime" value="<?= $trip['end_date']->format('Y-m-d H:i') ?>">
                                        <button class="inline">New trip</button>
                                    </form>
                                    <form action="/space-to-end" method="post">
                                        <input type="hidden" name="SpaceEnd" value="<?= $nextTripStart ?>">
                                        <input type="hidden" name="TripId" value="<?= $trip['id'] ?>">
                                        <button class="inline">Add &darr;</button>
                                    </form>
                                    <form action="/space-to-start" method="post">
                                        <input type="hidden" name="SpaceStart" value="<?= $trip['end_odometer'] ?>">
                                        <input type="hidden" name="TripId" value="<?= $nextTripId ?>">
                                        <button class="inline">Add &uarr;</button>
                                    </form>
                                </div>
                            </div>
                        <?php
                    }
                    ?>

                    <div class="<?= join(' ', $classes) ?>" data-id="<?= $trip['id'] ?>">
                        <div class="icon"
                             style="border-color: <?php echo ColorTool::stringToColor($trip['driver_name']) ?>"
                             title="<?= $trip['driver_name'] ?>">
                             <?php echo StringTool::nameInicials($trip['driver_name']) ?>
                        </div>
                        <?php if ($isEdited) {
                            ?>
                            <div class="editorIcon"
                                 title="<?= $trip['added_by_name'] ?> (<?= $trip['added_on']->format('Y-m-d H:i') ?>)"
                                style="border-color: <?php echo ColorTool::stringToColor($trip['added_by_name']) ?>">
                                <?= StringTool::nameInicials($trip['added_by_name']) ?>
                            </div>
                            <?php
                        } ?>
                        <div class="times">
                            <div class="start">
                                <?php echo $trip['start_date']->format('Y-m-d') ?>
                                <span>
                                    <?php echo $trip['start_date']->format('H:i') ?>
                                </span>
                            </div>
                            <div class="toSign">&mdash;</div>
                            <div class="end">
                                <?php if ($trip['end_date'] === null) {
                                    echo '';
                                } else {
                                    echo $trip['end_date']->format('Y-m-d') ?>
                                        <span>
                                            <?php echo $trip['end_date']->format('H:i') ?>
                                        </span>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="places">
                            <div class="start">
                                <?= $trip['start_place'] ?>
                            </div>
                            <?php
                            if ($trip['target_place']) {
                                ?>
                                    <div class="toSign">&rarr;</div>
                                    <div class="target">
                                        <?= $trip['target_place'] ?>
                                    </div>
                                <?php
                            }
                            ?>

                            <?php
                            if ($trip['and_back']) {
                                echo '<div class="andBack">&larrhk;</div>';
                            } else {
                                echo '<div class="toSign">&rarr;</div>';
                                printf('<div class="end">%s</div>', $trip['end_place']);
                            }
                            ?>
                        </div>
                        <?php
                        if ($trip['is_personal']) {
                            ?>
                                <div class="client personal">
                                </div>
                            <?php
                        } else {
                            ?>
                                <div class="client">
                                    <?= $trip['target_client'] ?>
                                </div>
                            <?php
                        }
                        ?>

                        <div class="note">
                            <?= $p->text($trip['note']) ?>
                        </div>
                        <div class="summary">
                            <div class="km" title="Odometer: <?= $trip['start_odometer'] ?> - <?= $trip['end_odometer'] ?>">
                                <?= $trip['trip_length'] ?><span>KM</span>
                            </div>
                            <div class="hours">
                                <?= StringTool::durationToHtml($trip['trip_duration']) ?>
                            </div>
                        </div>
                        <div class="actions">
                            <?php if (!$trip['is_locked']) { ?>
                                <button class="inline danger" onclick="removalConfirm(<?= $trip['id'] ?>)">Remove</button>
                                <button class="inline" onclick="window.location.href = '/edit/<?= $trip['id'] ?>'">Edit</button>
                            <?php } ?>
                        </div>
                    </div>



                    <?php
                    $nextTripStart = $trip['start_odometer'];
                    $nextTripStartTime = $trip['start_date'];
                    $nextTripId = $trip['id'];
                }
                ?>
            </section>
        </div>
        <div id="Add">
            <a href="/<?= $vars['car'] ?>/add">Add trip</a>
        </div>
        <script src="/static/home.js"></script>
    </body>
</html>

