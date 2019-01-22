<?php include 'parts/head.php' ?>
    <body class="light">
        <div id="Wrapper">
            <?php include 'parts/nav.php' ?>
            <ul class="messages">
                <?php foreach ($vars['f']->getMessages() as $one) {
                    printf("<li class='%s'>%s</li>", $one['type'], $one['text']);
                } ?>
            </ul>
            <section id="AddForm">
                <?php
                    $editing = false;
                    if (isset($vars['prefill']['Id'])) {
                        printf('<input type="hidden" name="Id" value="%s">', $vars['prefill']['Id']);
                        $editing = true;
                    }
                ?>
                <form action="<?= ($editing) ? '/edit' : '/add' ?>" method="post">
                    <input type="hidden" name="Car" value="<?php echo $vars['car'] ?>">
                    <input type="hidden" name="Driver" value="<?php echo $vars['driver'] ?>">
                    <?php
                    if ($editing) {
                        printf('<input type="hidden" name="Id" value="%s">', $vars['prefill']['Id']);
                    } ?>
                    <h3 class="addTripStart"><span>Trip start</span></h3>
                    <div class="row">
                        <label for="OdometerStart">Odometer</label>
                        <input type="number" name="OdometerStart" id="OdometerStart"
                               value="<?php echo (isset($vars['prefill']) && isset($vars['prefill']['OdometerStart'])) ? $vars['prefill']['OdometerStart'] : '' ?>">
                        <label for="TimeStart">Time</label>
                        <input type="datetime-local" name="TimeStart" id="TimeStart"
                                value="<?php echo (isset($vars['prefill']) && isset($vars['prefill']['TimeStart'])) ? $vars['prefill']['TimeStart'] : '' ?>">
                        <label for="PlaceStart">Place</label>
                        <input type="text" name="PlaceStart" id="PlaceStart"
                               value="<?php echo (isset($vars['prefill']) && isset($vars['prefill']['PlaceStart'])) ? $vars['prefill']['PlaceStart'] : 'Brno' ?>">
                    </div>
                    <h3 class="addTarget"><span>Target</span></h3>
                    <div class="row selection">
                        <div class="btnGroup">
                            <?php
                                if ((isset($vars['prefill']) && isset($vars['prefill']['Personal']) ) && $vars['prefill']['Personal'] === '1') {
                                    $workClass = '';
                                    $personalClass = 'class="on"';
                                } else {
                                    $workClass = 'class="on"';
                                    $personalClass = '';
                                }
                            ?>
                            <button <?php echo $workClass ?> data-form-element="Personal" value="0">Work</button>
                            <button <?php echo $personalClass ?> data-form-element="Personal" value="1">Personal</button>
                            <input type="hidden" name="Personal"
                                   value="<?php echo (isset($vars['prefill']) && isset($vars['prefill']['Personal'])) ? $vars['prefill']['Personal'] : '0' ?>">
                        </div>
                    </div>
                    <div class="row onlyForWork">
                        <label for="Client">Client</label>
                        <input type="text" name="Client" id="Client"
                               value="<?php echo (isset($vars['prefill']) && isset($vars['prefill']['Client'])) ? $vars['prefill']['Client'] : '' ?>">
                        <label for="PlaceTarget">Place</label>
                        <input type="text" name="PlaceTarget" id="PlaceTarget"
                               value="<?php echo (isset($vars['prefill']) && isset($vars['prefill']['PlaceTarget'])) ? $vars['prefill']['PlaceTarget'] : '' ?>">
                    </div>
                    <h3 class="addTripEnd"><span>Trip end</span></h3>
                    <div class="row selection">
                        <div class="btnGroup">
                            <?php
                                if ((isset($vars['prefill']) && isset($vars['prefill']['AndBack'])) && $vars['prefill']['AndBack'] === '0') {
                                    $andBackClass = '';
                                    $elsewhereClass = 'class="on"';
                                } else {
                                    $andBackClass = 'class="on"';
                                    $elsewhereClass = '';
                                }
                            ?>
                            <button <?php echo $andBackClass ?> data-form-element="AndBack" value="1">And back</button>
                            <button <?php echo $elsewhereClass ?> data-form-element="AndBack" value="0">Elsewhere</button>
                            <input type="hidden" name="AndBack"
                                   value="<?php echo (isset($vars['prefill']) && isset($vars['prefill']['AndBack'])) ? $vars['prefill']['AndBack'] : '1' ?>">
                        </div>
                    </div>
                    <div class="row lastBeforeCheck">
                        <label for="OdometerEnd">Odometer</label>
                        <input type="number" name="OdometerEnd" id="OdometerEnd"
                               value="<?php echo (isset($vars['prefill']) && isset($vars['prefill']['OdometerEnd'])) ? $vars['prefill']['OdometerEnd'] : '' ?>">
                        <label for="TimeEnd">Time</label>
                        <input type="datetime-local" name="TimeEnd" id="TimeEnd"
                               value="<?php echo (isset($vars['prefill']) && isset($vars['prefill']['TimeEnd'])) ? $vars['prefill']['TimeEnd'] : '' ?>">
                        <label for="PlaceEnd">Place</label>
                        <input type="text" name="PlaceEnd" id="PlaceEnd"
                               value="<?php echo (isset($vars['prefill']) && isset($vars['prefill']['PlaceEnd'])) ? $vars['prefill']['PlaceEnd'] : '' ?>">
                    </div>
                    <div class="check">
                        <table>
                            <tr>
                                <td data-field="km">xx</td>
                                <th>km</th>
                            </tr>
                            <tr>
                                <td data-field="hours">yy</td>
                                <th>hours</th>
                            </tr>
                        </table>
                    </div>
                    <div class="note">
                        <label for="Note">Note</label>
                        <textarea name="Note" id="Note" cols="30" rows="4"><?php echo (isset($vars['prefill']) && isset($vars['prefill']['Note'])) ? $vars['prefill']['Note'] : '' ?></textarea>
                    </div>
                    <div class="row button">
                        <input type="submit" value="<?= ($editing) ? 'Modify' : 'Add new' ?>">
                    </div>
                </form>
            </section>
        </div>
        <script src="/static/addform.js"></script>
    </body>
</html>

