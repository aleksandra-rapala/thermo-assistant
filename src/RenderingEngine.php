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
}
