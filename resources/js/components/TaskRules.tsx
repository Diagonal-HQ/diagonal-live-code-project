import { router } from '@inertiajs/react';
import { FormDataConvertible } from '@inertiajs/core';
import { Button } from '@/components/ui/Button';
import { RuleData } from '@/types';

const availableRules = [
  {
    name: 'Set task due date when #deadline tag is present',
    event: 'creating',
    action: {
      type: 'model.set',
      input: {
        field: {
          type: 'value',
          value: 'due_date'
        },
        value: {
          type: 'date',
          value: {
            add: '2 days'
          }
        }
      }
    },
    guard: {
      name: [
        {
          type: 'regex',
          options: [
            {
              type: 'value',
              value: '/#deadline/'
            }
          ]
        }
      ]
    }
  },
  {
    name: 'Append #deadline tag when task is due within 2 days',
    event: 'creating',
    action: {
      type: 'model.set',
      input: {
        field: 'name',
        value: {
          type: 'action',
          value: {
            type: 'text.concat',
            input: {
              text: {
                type: 'model',
                value: 'name'
              },
              addition: '#deadline'
            }
          }
        }
      }
    },
    guard: {
      due_date: [
        {
          type: 'before_or_equal',
          options: [
            {
              type: 'date',
              value: {
                add: '2 days'
              }
            }
          ]
        }
      ]
    }
  },
  {
    name: 'Append #pastdue tag to task when due date is in the past',
    event: 'retrieved',
    action: {
      type: 'model.set',
      input: {
        field: 'name',
        value: {
          type: 'action',
          value: {
            type: 'text.concat',
            input: {
              text: {
                type: 'model',
                value: 'name'
              },
              addition: {
                type: 'value',
                value: '#pastdue'
              }
            }
          }
        }
      }
    },
    guard: {
      due_date: [
        {
          type: 'before_or_equal',
          options: [{ type: 'date' }]
        }
      ]
    }
  },
  {
    name: 'Empty rule... 👀',
    event: '',
    action: {},
    guard: {}
  }
];

export default function TaskRules({ rules }: { rules: RuleData[] }) {
  const filteredAvailableRules = availableRules.filter(
    (rule) => !rules.some((r) => r.name === rule.name)
  );

  function handleAddRule(rule: Record<string, FormDataConvertible>) {
    router.post('/tasks/rules', rule);
  }

  return (
    <div className="w-full mt-8 flex flex-col gap-4">
      <h2>Task Rules</h2>
      <div className="border-y border-gray-200 py-4 space-y-4">
        <div className="py-4">
          {rules.length ? (
            rules.map((rule) => (
              <div className="flex flex-col gap-1">
                <p className="font-medium p-1">
                  <span className="bg-green-100 text-green-600 rounded p-1 text-xs">Active</span>{' '}
                  {rule.name}
                </p>
              </div>
            ))
          ) : (
            <div className="flex w-full justify-center text-gray-500 text-sm">
              <p>No rules found</p>
            </div>
          )}
        </div>
      </div>
      <div className="flex flex-col gap-2">
        {filteredAvailableRules.map((rule) => (
          <Button
            className="w-full flex justify-between gap-0 leading-none"
            variant="secondary"
            size="lg"
            onClick={() => handleAddRule(rule)}
          >
            Add rule: {rule.name}
          </Button>
        ))}
      </div>
    </div>
  );
}
