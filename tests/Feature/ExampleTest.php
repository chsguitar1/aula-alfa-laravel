<?php

test('visitante na home é redirecionado ao login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
