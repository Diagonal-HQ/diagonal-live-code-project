<?php

namespace Tests\Unit\Engine\Action\Text;

use Override;
use App\Data\ContextData;
use App\Engine\Action\Text\Concat;
use App\Models\Rule;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConcatTest extends TestCase
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
    public function itConcatenatesText(): void
    {
        // Given
        $input = [
            'text' => 'Hello',
            'addition' => ' World'
        ];

        // When
        $result = Concat::execute($this->context, $input);

        // Then
        $this->assertEquals('Hello World', $result);
    }

    #[Test]
    public function itHandlesEmptyInput(): void
    {
        // Given
        $input = [];

        // When
        $result = Concat::execute($this->context, $input);

        // Then
        $this->assertEquals('', $result);
    }
}
