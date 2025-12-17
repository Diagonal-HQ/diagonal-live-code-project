<?php

namespace Tests\Unit\Engine\Value;

use Override;
use App\Data\ContextData;
use App\Engine\Value\Date;
use App\Models\Rule;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DateTest extends TestCase
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
    public function itResolvesDateWithAdd(): void
    {
        // Given
        $config = [
            'add' => '1 day'
        ];

        // When
        $result = Date::resolve($this->context, $config);

        // Then
        $this->assertIsString($result);
        $this->assertEquals(
            Carbon::now()->addDay()->toDateTimeString(),
            $result
        );
    }

    #[Test]
    public function itResolvesDateWithSubtract(): void
    {
        // Given
        $config = [
            'subtract' => '1 day'
        ];

        // When
        $result = Date::resolve($this->context, $config);

        // Then
        $this->assertIsString($result);
        $this->assertEquals(
            Carbon::now()->subDay()->toDateTimeString(),
            $result
        );
    }

    #[Test]
    public function itResolvesDateWithFormat(): void
    {
        // Given
        $config = [
            'format' => 'Y-m-d'
        ];

        // When
        $result = Date::resolve($this->context, $config);

        // Then
        $this->assertIsString($result);
        $this->assertEquals(
            Carbon::now()->format('Y-m-d'),
            $result
        );
    }
}
