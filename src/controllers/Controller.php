<?php
interface Controller {
    public function get($variables);
    public function post($variables, $properties, $body);
}
