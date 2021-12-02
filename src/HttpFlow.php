<?php
class HttpFlow {
    public function badRequest() {
        http_response_code(400);
        die();
    }

    public function notFound() {
        http_response_code(404);
        die();
    }

    public function methodNotAllowed() {
        http_response_code(405);
        die();
    }

    public function redirectTo($resource) {
        header("Location: $resource");
    }
}
