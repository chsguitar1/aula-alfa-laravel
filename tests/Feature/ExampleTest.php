<?php

test('guests are redirected from home to login', function () {
    $response = $this->get('/');

    $response->assertRedirectToRoute('login');
});
