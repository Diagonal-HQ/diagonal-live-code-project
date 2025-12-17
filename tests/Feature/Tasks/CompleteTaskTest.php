<?php

namespace Tests\Feature\Tasks;

use Database\Factories\TaskFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CompleteTaskTest extends TestCase
{
    #[Test]
    public function itCompletesATask(): void
    {
        // Given
        $task = TaskFactory::new()->create();

        // When
        $this->actingAs($task->user)
            ->from('/dashboard')
            ->postJson("/tasks/{$task->id}/complete")
            ->assertRedirect('/dashboard')
            ->assertSessionDoesntHaveErrors();

        // Then
        $this->assertNotNull($task->fresh()->completed_at);
    }

    #[Test]
    public function itRequiresAuthToCompleteATask(): void
    {
        $task = TaskFactory::new()->create();

        $this->postJson("/tasks/{$task->id}/complete")->assertUnauthorized();
    }
}
