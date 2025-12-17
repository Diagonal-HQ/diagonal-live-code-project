<?php

namespace Tests\Unit\Engine\Action\Model;

use Override;
use DomainException;
use App\Data\ContextData;
use App\Engine\Action\Model\Set;
use App\Models\Rule;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SetTest extends TestCase
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
    public function itSetsModelAttribute(): void
    {
        // Given
        $input = [
            'field' => 'name',
            'value' => 'Updated Task'
        ];

        // When
        $result = Set::execute($this->context, $input);

        // Then
        $this->assertEquals('Updated Task', $this->task->name);
    }

    #[Test]
    public function itThrowsExceptionWhenAttributeIsNotProvided(): void
    {
        // Given
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Field is not provided');

        // When
        Set::execute($this->context, []);
    }

    #[Test]
    public function itThrowsExceptionWhenValueIsNotProvided(): void
    {
        // Given
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Value is not provided');

        // When
        Set::execute($this->context, [
            'field' => 'name'
        ]);
    }
}
