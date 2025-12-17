<?php

namespace Tests\Unit\Engine;

use App\Data\ContextData;
use App\Engine\Action;
use App\Models\Rule;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Override;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ActionTest extends TestCase
{
    use RefreshDatabase;

    private ContextData $context;
    private Task $task;
    private User $user;
    private Rule $rule;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $this->task = new Task([
            'name' => 'Test Task',
            'user_id' => $this->user->id,
            'due_date' => Carbon::now()->addDay(),
        ]);

        $this->rule = new Rule([
            'name' => 'Test Rule',
            'model_type' => Task::class,
            'event' => 'created',
        ]);

        $this->context = new ContextData($this->task, $this->rule, $this->user);
    }

    #[Test]
    public function itThrowsExceptionWhenTypeIsMissing(): void
    {
        // Given
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Action type is required');

        // When
        Action::execute($this->context, []);
    }

    #[Test]
    public function itThrowsExceptionForInvalidType(): void
    {
        // Given
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid action type: invalid');

        // When
        Action::execute($this->context, [
            'type' => 'invalid',
            'input' => []
        ]);
    }

    #[Test]
    public function itExecutesModelSetAction(): void
    {
        // Given
        $action = [
            'type' => 'model.set',
            'input' => [
                'field' => 'name',
                'value' => 'Updated Task'
            ]
        ];

        // When
        $result = Action::execute($this->context, $action);

        // Then
        $this->assertNull($result);
        $this->assertEquals('Updated Task', $this->task->name);
    }

    #[Test]
    public function itExecutesTextConcatAction(): void
    {
        // Given
        $action = [
            'type' => 'text.concat',
            'input' => [
                'text' => 'Hello',
                'addition' => ' World'
            ]
        ];

        // When
        $result = Action::execute($this->context, $action);

        // Then
        $this->assertEquals('Hello World', $result);
    }
}
