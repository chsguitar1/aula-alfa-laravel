<?php

it('renders the login page with bootstrap controls and auth links', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('action="'.route('login.store').'" method="POST"', false)
        ->assertSee('name="_token"', false)
        ->assertSee('type="submit"', false)
        ->assertSee('class="form-control form-control-lg', false)
        ->assertSee('name="password"', false)
        ->assertDontSee('app-sidebar')
        ->assertSee(route('password.request'), false)
        ->assertSee(route('register'), false);
});

it('renders registration independently from the administrative layout', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee('action="'.route('users.store').'" method="POST"', false)
        ->assertSee('name="_token"', false)
        ->assertDontSee('app-sidebar');
});

it('renders the password recovery page with a link back to login', function () {
    $this->get(route('password.request'))
        ->assertOk()
        ->assertSee('class="form-control form-control-lg', false)
        ->assertSee('Enviar link de recuperação')
        ->assertSee(route('login'), false);
});
