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
                <form action="/add" method="post">
                    <input type="hidden" name="Car" value="<?php echo $vars['car'] ?>">
                    <h3 class="addTripStart"><span>Trip start</span></h3>
                    <div class="row">
                        <label for="OdometerStart">Odometer</label>
                        <input type="number" name="OdometerStart" id="OdometerStart"
                               value="<?php echo (isset($vars['prefill']) ? $vars['prefill']['OdometerStart'] : '1000') ?>">
                        <label for="TimeStart">Time</label>
                        <input type="datetime-local" name="TimeStart" id="TimeStart"
                                value="<?php echo (isset($vars['prefill']) ? $vars['prefill']['TimeStart'] : '') ?>">
                        <label for="PlaceStart">Place</label>
                        <input type="text" name="PlaceStart" id="PlaceStart"
                               value="<?php echo (isset($vars['prefill']) ? $vars['prefill']['PlaceStart'] : 'Brno') ?>">
                    </div>
                    <h3 class="addTarget"><span>Target</span></h3>
                    <div class="row selection">
                        <div class="btnGroup">
                            <?php
                                if (isset($vars['prefill']) && $vars['prefill']['Personal'] === '1') {
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
                                   value="<?php echo (isset($vars['prefill']) ? $vars['prefill']['Personal'] : '0') ?>">
                        </div>
                    </div>
                    <div class="row onlyForWork">
                        <label for="Client">Client</label>
                        <input type="text" name="Client" id="Client"
                               value="<?php echo (isset($vars['prefill']) ? $vars['prefill']['Client'] : '') ?>">
                        <label for="PlaceTarget">Place</label>
                        <input type="text" name="PlaceTarget" id="PlaceTarget"
                               value="<?php echo (isset($vars['prefill']) ? $vars['prefill']['PlaceTarget'] : '') ?>">
                    </div>
                    <h3 class="addTripEnd"><span>Trip end</span></h3>
                    <div class="row selection">
                        <div class="btnGroup">
                            <?php
                                if (isset($vars['prefill']) && $vars['prefill']['AndBack'] === '0') {
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
                                   value="<?php echo (isset($vars['prefill']) ? $vars['prefill']['AndBack'] : '1') ?>">
                        </div>
                    </div>
                    <div class="row lastBeforeCheck">
                        <label for="OdometerEnd">Odometer</label>
                        <input type="number" name="OdometerEnd" id="OdometerEnd"
                               value="<?php echo (isset($vars['prefill']) ? $vars['prefill']['OdometerEnd'] : '') ?>">
                        <label for="TimeEnd">Time</label>
                        <input type="datetime-local" name="TimeEnd" id="TimeEnd"
                               value="<?php echo (isset($vars['prefill']) ? $vars['prefill']['TimeEnd'] : '') ?>">
                        <label for="PlaceEnd">Place</label>
                        <input type="text" name="PlaceEnd" id="PlaceEnd"
                               value="<?php echo (isset($vars['prefill']) ? $vars['prefill']['PlaceEnd'] : '') ?>">
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
                        <textarea name="Note" id="Note" cols="30" rows="4"><?php echo (isset($vars['prefill']) ? $vars['prefill']['Note'] : '') ?></textarea>
                    </div>
                    <div class="row button">
                        <input type="submit" value="Save">
                    </div>
                </form>
            </section>
        </div>
        <script src="/static/addform.js"></script>
    </body>
</html>

