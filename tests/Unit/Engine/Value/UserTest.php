<?php

namespace Tests\Unit\Engine\Value;

use Override;
use App\Data\ContextData;
use App\Engine\Value\User;
use App\Models\Rule;
use App\Models\Task;
use App\Models\User as UserModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private ContextData $context;
    private Task $task;
    private UserModel $user;
    private Rule $rule;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = UserModel::create([
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
    public function itResolvesUserAttribute(): void
    {
        // Given
        $attribute = 'name';

        // When
        $result = User::resolve($this->context, $attribute);

        // Then
        $this->assertEquals('Test User', $result);
    }

    #[Test]
    public function itReturnsNullWhenUserIsNotInContext(): void
    {
        // Given
        $emptyUser = new UserModel();
        $context = new ContextData($this->task, $this->rule, $emptyUser);
        $attribute = 'name';

        // When
        $result = User::resolve($context, $attribute);

        // Then
        $this->assertNull($result);
    }
}
