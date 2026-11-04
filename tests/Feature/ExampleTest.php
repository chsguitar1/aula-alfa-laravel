<?php

test('guests are redirected from the home page to the login', function () {
    $this->get('/')->assertRedirect(route('login'));
});
