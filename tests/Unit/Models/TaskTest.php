<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Database\Factories\TaskFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskTest extends TestCase
{
    #[Test]
    public function itBelongsToUser(): void
    {
        // Given
        $task = TaskFactory::new()->create();

        // Then
        $this->assertInstanceOf(User::class, $task->user);
    }
}
