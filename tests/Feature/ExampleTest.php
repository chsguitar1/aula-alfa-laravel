<?php

test('visitor is redirected from the home page to login', function () {
    $this->get('/')->assertRedirect(route('login'));
});
