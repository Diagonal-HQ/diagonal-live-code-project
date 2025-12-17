<?php

namespace Tests\Unit\Models;

use App\Models\Task;
use Database\Factories\TaskFactory;
use Database\Factories\UserFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    #[Test]
    public function itHasManyTasks(): void
    {
        // Given
        $user = UserFactory::new()->create();

        // When
        TaskFactory::new()->create([
            'user_id' => $user->id,
        ]);

        // Then
        $this->assertInstanceOf(Task::class, $user->tasks->first());
    }
}