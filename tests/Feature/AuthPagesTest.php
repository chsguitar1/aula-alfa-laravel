<?php

it('renders the login page with bootstrap controls and auth links', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('class="form-control form-control-lg', false)
        ->assertSee('name="password"', false)
        ->assertSee(route('password.request'), false)
        ->assertSee(route('register'), false);
});

it('renders the password recovery page with a link back to login', function () {
    $this->get(route('password.request'))
        ->assertOk()
        ->assertSee('class="form-control form-control-lg', false)
        ->assertSee('Enviar link de recuperação')
        ->assertSee(route('login'), false);
});
