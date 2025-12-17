<?php

namespace Tests\Unit\Engine;

use Override;
use DomainException;
use App\Data\ContextData;
use App\Engine\Guard;
use App\Models\Rule;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GuardTest extends TestCase
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
    public function itParsesSimpleRules(): void
    {
        // Given
        $guard = [
            'name' => [
                [
                    'type' => 'required'
                ]
            ]
        ];

        // When
        $result = Guard::parse($this->context, $guard);

        // Then
        $this->assertEquals(['name' => ['required']], $result);
    }

    #[Test]
    public function itParsesRulesWithOptions(): void
    {
        // Given
        $guard = [
            'name' => [
                [
                    'type' => 'regex',
                    'options' => [
                        [
                            'type' => 'value',
                            'value' => '/test/'
                        ]
                    ]
                ]
            ]
        ];

        // When
        $result = Guard::parse($this->context, $guard);

        // Then
        $this->assertEquals(['name' => ['regex:/test/']], $result);
    }

    #[Test]
    public function itThrowsExceptionWhenRuleTypeIsMissing(): void
    {
        // Given
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Guard rule type is required');

        // When
        Guard::parse($this->context, [
            'name' => [
                []
            ]
        ]);
    }

    #[Test]
    public function itValidatesSimpleRules(): void
    {
        // Given
        $guard = [
            'name' => [
                [
                    'type' => 'required'
                ]
            ]
        ];

        // When
        $result = Guard::validate($this->context, $guard);

        // Then
        $this->assertTrue($result);
    }

    #[Test]
    public function itValidatesRegexRules(): void
    {
        // Given
        $guard = [
            'name' => [
                [
                    'type' => 'regex',
                    'options' => [
                        [
                            'type' => 'value',
                            'value' => '/Test/'
                        ]
                    ]
                ]
            ]
        ];

        // When
        $result = Guard::validate($this->context, $guard);

        // Then
        $this->assertTrue($result);
    }

    #[Test]
    public function itFailsValidationWhenRuleIsNotSatisfied(): void
    {
        // Given
        $guard = [
            'name' => [
                [
                    'type' => 'regex',
                    'options' => [
                        [
                            'type' => 'value',
                            'value' => '/Invalid/'
                        ]
                    ]
                ]
            ]
        ];

        // When
        $result = Guard::validate($this->context, $guard);

        // Then
        $this->assertFalse($result);
    }
}
