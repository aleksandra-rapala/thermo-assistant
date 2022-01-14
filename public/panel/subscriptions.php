<p>Chcę otrzymywać oferty dotyczące planowanych prac termomodernizacyjnych</p>

<div class="indented-checkbox">
    <?php
    $this->renderUncheckedCheckbox("Wiadomość e-mail", "subscriptions", "modernization-email");
    ?>
</div>

<div class="indented-checkbox">
    <?php
    $this->renderUncheckedCheckbox("Powiadomienie w przeglądarce", "subscriptions", "modernization-notification");
    ?>
</div>

<p>Chcę otrzymywać oferty dotyczące planowanych wymian kotłów lub instalacji kolektorów słonecznych</p>

<div class="indented-checkbox">
    <?php
    $this->renderUncheckedCheckbox("Wiadomość e-mail", "subscriptions", "heater-email");
    ?>
</div>

<div class="indented-checkbox">
    <?php
    $this->renderUncheckedCheckbox("Powiadomienie w przeglądarce", "subscriptions", "heater-notification");
    ?>
</div>