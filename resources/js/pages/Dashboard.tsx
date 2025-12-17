import AddTaskForm from '@/components/AddTaskForm';
import Task from '@/components/Task';
import TaskRules from '@/components/TaskRules';
import { RuleData, TaskData, UserData } from '@/types';

export default function Dashboard({
  tasks,
  user,
  rules
}: {
  tasks: TaskData[];
  user: UserData;
  rules: RuleData[];
}) {
  return (
    <div className="flex h-screen w-screen items-center justify-center">
      <div className="flex flex-col gap-4 w-full max-w-lg">
        <h1 className="flex w-full justify-between">
          Tasks <span className="text-sm text-gray-500">{user.name}</span>
        </h1>
        <div className="border-y border-gray-200 py-4 space-y-4">
          <div className="py-4">
            {tasks.length ? (
              tasks.map((task) => <Task key={task.id} task={task} />)
            ) : (
              <div className="flex w-full justify-center text-gray-500 text-sm">
                <p>No tasks found</p>
              </div>
            )}
          </div>
          <AddTaskForm />
        </div>

        <TaskRules rules={rules} />
      </div>
    </div>
  );
}
