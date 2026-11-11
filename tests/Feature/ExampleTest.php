<?php

test('visitante que abre a página inicial é enviado ao login', function () {
    $this->get('/')->assertRedirect('/login');
});
