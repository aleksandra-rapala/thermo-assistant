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

    private function renderTextFieldWithSuggestions($label, $property, $value, $suggestions) {
        echo <<<HTML
            <label for="$property-field">$label</label>
            <input name="$property" id="$property-field" value="$value" list="$property-suggestions" />
            
            <datalist id="$property-suggestions">
        HTML;

        foreach ($suggestions as $suggestion) {
            echo <<<HTML
                <option>$suggestion</option>
            HTML;
        }

        echo <<<HTML
            </datalist>
        HTML;
    }

    private function renderCheckbox($label, $property, $value, $isChecked) {
        $checkedAttribute = $isChecked ? "checked" : "";

        echo <<<HTML
            <input type="checkbox" name="$property" value="$value" id="$property-$value-field" $checkedAttribute>
            <label for="$property-$value-field">$label</label>
        HTML;
    }

    private function renderRadioGroup($label, $property, $options, $selected) {
        echo <<<HTML
            <span>$label</span>
        HTML;

        foreach ($options as $optionValue => $optionLabel) {
            $checkedAttribute = $selected === $optionValue? "checked" : "";

            echo <<<HTML
                <div>
                    <input type="radio" name="$property" value="$optionValue" id="$optionValue-$property-field" $checkedAttribute />
                    <label for="$optionValue-$property-field">$optionLabel</label>
                </div>
            HTML;
        }
    }

    private function renderCombobox($label, $property, $options, $selected) {
        echo <<<HTML
            <label for="$property-field">$label</label>
        HTML;

        echo <<<HTML
            <select name="$property" id="$property-field">
        HTML;

        foreach ($options as $optionValue => $optionLabel) {
            $selectedAttribute = $optionValue == $selected? "selected" : "";

            echo <<<HTML
                <option value="$optionValue" $selectedAttribute>$optionLabel</option>
            HTML;
        }

        echo <<<HTML
            </select>
        HTML;
    }

    private function renderHiddenField($property, $value) {
        echo <<<HTML
            <input type="hidden" name="$property" value="$value" />
        HTML;
    }
}
