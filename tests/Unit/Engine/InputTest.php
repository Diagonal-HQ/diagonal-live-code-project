<?php

namespace Tests\Unit\Engine;

use Override;
use App\Data\ContextData;
use App\Engine\Input;
use App\Models\Rule;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InputTest extends TestCase
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
    public function itResolvesSimpleValues(): void
    {
        // Given
        $input = [
            'name' => 'Test',
            'value' => 123
        ];

        // When
        $result = Input::resolve($this->context, $input);

        // Then
        $this->assertEquals('Test', $result['name']);
        $this->assertEquals(123, $result['value']);
    }

    #[Test]
    public function itResolvesValueTypes(): void
    {
        // Given
        $input = [
            'name' => [
                'type' => 'model',
                'value' => 'name'
            ],
            'user' => [
                'type' => 'user',
                'value' => 'name'
            ],
            'date' => [
                'type' => 'date',
                'value' => [
                    'add' => '1 day'
                ]
            ]
        ];

        // When
        $result = Input::resolve($this->context, $input);

        // Then
        $this->assertEquals('Test Task', $result['name']);
        $this->assertEquals('Test User', $result['user']);
        $this->assertEquals(
            Carbon::now()->addDay()->toDateTimeString(),
            $result['date']
        );
    }

    #[Test]
    public function itHandlesEmptyInput(): void
    {
        // Given
        $input = [];

        // When
        $result = Input::resolve($this->context, $input);

        // Then
        $this->assertEmpty($result);
    }
}
