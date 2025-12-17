import { Button } from '@/components/ui/Button';
import { useForm } from '@inertiajs/react';

export default function Guest() {
  const form = useForm();

  function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();

    form.post('/login');
  }

  return (
    <div className="flex h-screen w-screen items-center justify-center">
      <form onSubmit={handleSubmit}>
        <Button type="submit">Log in as a user</Button>
      </form>
    </div>
  );
}
