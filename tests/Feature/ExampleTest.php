<?php

test('the home page redirects to the user list', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('admin.users.index'));
});
