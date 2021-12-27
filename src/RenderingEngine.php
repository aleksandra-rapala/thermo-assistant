<?php
class RenderingEngine {
    public function renderView($name, $properties=[]) {
        $templatePath = "public/views/".$name.".html";

        if (file_exists($templatePath)) {
            extract($properties);

            ob_start();
            require_once($templatePath);
            $rendered = ob_get_clean();

            echo($rendered);
        } else {
            throw new UnexpectedValueException("Could not render specified page");
        }
    }

    private function renderTextField($label, $property, $value) {
        echo <<<HTML
            <label for="$property-field">$label</label>
            <input name="$property" id="$property-field" value="$value" />
        HTML;
    }

    private function renderCheckbox($label, $property, $value, $isChecked) {
        $checkedAttribute = $isChecked ? "checked" : "";

        echo <<<HTML
            <input type="checkbox" name="$property" value="$value" id="$property-$value-field" $checkedAttribute>
            <label for="$property-$value-field">$label</label>
        HTML;
    }

    private function renderCombobox($label, $property, $options, $selected) {
        $optionsTags = "";

        foreach ($options as $optionValue => $optionLabel) {
            $selectedAttribute = $optionValue == $selected? "selected" : "";
            $optionsTags .= "<option value=\"$optionValue\" $selectedAttribute>$optionLabel</option>";
        }

        echo <<<HTML
            <label for="$property-field">$label</label>
            
            <select name="$property" id="$property-field">
                $optionsTags
            </select>
        HTML;
    }
}
