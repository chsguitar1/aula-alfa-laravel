<?php

test('visitante é redirecionado da página inicial para o login', function () {
    $this->get('/')->assertRedirect(route('login'));
});
