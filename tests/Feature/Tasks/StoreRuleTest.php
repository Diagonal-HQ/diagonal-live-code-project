<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use Database\Factories\UserFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreRuleTest extends TestCase
{
    #[Test]
    public function itCanStoreARule(): void
    {
        // Given
        $user = UserFactory::new()->create();

        // When
        $this->actingAs($user)
            ->from('/dashboard')
            ->postJson('/rules', [
                'name' => 'Test Rule',
                'model_type' => Task::class,
                'event' => 'created',
                'action' => [
                    'type' => 'model.set',
                    'input' => [
                        'field' => 'due_date',
                        'value' => [
                            'type' => 'date',
                            'value' => [
                                'initial' => '2025-01-01',
                            ]
                        ]
                    ]
                ]
            ])
            ->assertRedirect('/dashboard')
            ->assertSessionDoesntHaveErrors();

        // Then
        $this->assertDatabaseHas('rules', [
            'name' => 'Test Rule',
            'model_type' => Task::class,
            'event' => 'created',
        ]);
    }

    
}
