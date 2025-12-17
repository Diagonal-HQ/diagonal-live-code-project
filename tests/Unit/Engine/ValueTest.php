<?php

namespace Tests\Unit\Engine;

use Override;
use DomainException;
use App\Data\ContextData;
use App\Engine\Value;
use App\Models\Rule;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ValueTest extends TestCase
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
        $this->expectExceptionMessage('Value type is required');

        // When
        Value::resolve($this->context, []);
    }

    #[Test]
    public function itResolvesValueType(): void
    {
        // Given
        $config = [
            'type' => 'value',
            'value' => 'test value'
        ];

        // When
        $result = Value::resolve($this->context, $config);

        // Then
        $this->assertEquals('test value', $result);
    }

    #[Test]
    public function itResolvesDateType(): void
    {
        // Given
        $config = [
            'type' => 'date',
            'value' => [
                'add' => '1 day'
            ]
        ];

        // When
        $result = Value::resolve($this->context, $config);

        // Then
        $this->assertIsString($result);
        $this->assertEquals(
            Carbon::now()->addDay()->toDateTimeString(),
            $result
        );
    }

    #[Test]
    public function itResolvesModelType(): void
    {
        // Given
        $config = [
            'type' => 'model',
            'value' => 'name'
        ];

        // When
        $result = Value::resolve($this->context, $config);

        // Then
        $this->assertEquals('Test Task', $result);
    }

    #[Test]
    public function itResolvesUserType(): void
    {
        // Given
        $config = [
            'type' => 'user',
            'value' => 'name'
        ];

        // When
        $result = Value::resolve($this->context, $config);

        // Then
        $this->assertEquals('Test User', $result);
    }

    #[Test]
    public function itResolvesActionType(): void
    {
        // Given
        $config = [
            'type' => 'action',
            'value' => [
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
            ]
        ];

        // When
        $result = Value::resolve($this->context, $config);

        // Then
        $this->assertEquals('Test Task (test)', $result);
    }

    #[Test]
    public function itThrowsExceptionForInvalidType(): void
    {
        // Given
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid value type: invalid');

        // When
        Value::resolve($this->context, [
            'type' => 'invalid',
            'value' => 'test'
        ]);
    }
}
