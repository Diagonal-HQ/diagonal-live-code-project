<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use Database\Factories\TaskFactory;
use Database\Factories\UserFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreTaskRuleTest extends TestCase
{
    #[Test]
    public function itCanStoreARuleForATask(): void
    {
        // Given
        $user = UserFactory::new()->create();
        $task = TaskFactory::new()->create();

        // When
        $this->actingAs($user)
            ->from('/dashboard')
            ->postJson("/tasks/{$task->id}/rules", [
                'name' => 'Test Rule #2',
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
            'name' => 'Test Rule #2',
            'model_type' => Task::class,
            'model_id' => $task->id,
            'event' => 'created',
        ]);
    }
}
