<?php

namespace Tests\Unit\Engine\Value;

use Override;
use App\Data\ContextData;
use App\Engine\Value\Action;
use App\Models\Rule;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    public function itResolvesAction(): void
    {
        // Given
        $config = [
            'type' => 'text.concat',
            'input' => [
                'text' => [
                    'type' => 'model',
                    'value' => 'name'
                ],
                'addition' => [
                    'type' => 'value',
                    'value' => ' (test)'
                ]
            ]
        ];

        // When
        $result = Action::resolve($this->context, $config);

        // Then
        $this->assertEquals('Test Task (test)', $result);
    }
}
