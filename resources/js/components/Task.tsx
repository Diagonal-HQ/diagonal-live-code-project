import { TaskData } from '@/types';
import { Button } from './ui/Button';
import { useForm } from '@inertiajs/react';

export default function Task({ task }: { task: TaskData }) {
  const form = useForm();

  function handleCompleteTask() {
    form.post(`/tasks/${task.id}/complete`);
  }

  return (
    <div className="w-full flex items-center justify-between gap-2">
      <div className="flex flex-col gap-0.5">
        <p className="font-medium">{task.name}</p>
        {task.due_date && <span className="text-xs text-gray-500">Due: {task.due_date}</span>}
      </div>
      <Button variant="secondary" size="sm" onClick={handleCompleteTask}>
        Complete task
      </Button>
    </div>
  );
}
