<p>Chcę otrzymywać oferty z punktów sprzedaży paliw działających na terenie mojej gminy</p>

<div class="indented-checkbox">
    <?php
    $this->renderUncheckedCheckbox("Wiadomość e-mail", "subscriptions", "fuels-email");
    ?>
</div>

<div class="indented-checkbox">
    <?php
    $this->renderUncheckedCheckbox("Powiadomienie w przeglądarce", "subscriptions", "fuels-notification");
    ?>
</div>