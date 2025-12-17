import { useForm } from '@inertiajs/react';
import { Button } from './ui/Button';
import { Input } from './ui/Input';

export default function AddTaskForm() {
  const form = useForm({
    name: '',
    due_date: ''
  });

  function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();

    form.post('/tasks', {
      onSuccess: () => {
        form.setData({
          name: '',
          due_date: ''
        });
      }
    });
  }

  return (
    <form className="flex flex-col gap-2" onSubmit={handleSubmit}>
      <div className="flex gap-2">
        <Input
          type="text"
          placeholder="Add a task"
          value={form.data.name}
          onChange={(e) => form.setData('name', e.target.value)}
        />
        <Input
          type="date"
          placeholder="Due date"
          value={form.data.due_date}
          onChange={(e) => form.setData('due_date', e.target.value)}
          className="w-40"
        />
      </div>
      <Button type="submit" variant="default">
        Add Task
      </Button>
    </form>
  );
}
