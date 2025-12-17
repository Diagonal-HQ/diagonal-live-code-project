export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: App.Data.UserData;
  };
};

export interface RuleData {
  id: string;
  name: string;
  event: string;
  priority: number;
  guard: any;
  action: {
    type: string;
    input: Record<string, unknown>;
  };
}

export interface TaskData {
  id: string;
  name: string;
  due_date: string | null;
  is_completed: boolean;
}

export interface UserData {
  name: string;
}
