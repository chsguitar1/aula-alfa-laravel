<?php

test('a guest is redirected to login from the home page', function () {
    $this->get('/')
        ->assertRedirectToRoute('login');
});
