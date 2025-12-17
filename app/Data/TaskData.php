<?php

namespace App\Data;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * Class TaskData
 *
 * Data transfer object for task information.
 */
class TaskData extends Data
{
    /**
     * Create a new task data instance.
     *
     * @param  string  $id  The task's unique identifier
     * @param  string  $name  The task's name
     * @param  bool|null  $is_completed  Whether the task is completed
     * @param  CarbonInterface|null  $due_date  The due date, if any
     */
    public function __construct(
        public string $id,
        public string $name,
        public ?bool $is_completed = false,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?CarbonInterface $due_date = null
    ) {}
}
