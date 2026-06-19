<?php

use App\Models\User;
use App\Models\Workspace;

it('genera invite_code al crear', function () {
    $user = User::factory()->create();

    $workspace = Workspace::create([
        'name' => 'Prueba Test',
        'description' => 'Descripción de prueba',
        'user_id' => $user->id,
    ]);

    expect($workspace->invite_code)->toMatch('/^TC\-[A-Z0-9]{4}$/');
});
