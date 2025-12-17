<?php

namespace Tests\Feature\Tasks;

use Database\Factories\UserFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreTaskTest extends TestCase
{
    #[Test]
    public function itCanStoreATask(): void
    {
        // Given
        $user = UserFactory::new()->create();

        // When
        $this->actingAs($user)
            ->from('/dashboard')
            ->postJson('/tasks', [
                'name' => 'Test Task',
            ])
            ->assertRedirect('/dashboard')
            ->assertSessionDoesntHaveErrors();

        // Then
        $this->assertDatabaseHas('tasks', [
            'name' => 'Test Task',
        ]);
    }

    #[Test]
    public function itRequiresAuthToStoreATask(): void
    {
        $this->postJson('/tasks', [
            'name' => 'Test Task',
        ])->assertUnauthorized();
    }
}
