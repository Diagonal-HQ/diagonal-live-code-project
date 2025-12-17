# Diagonal Take Home  Project

## Project Set Up

Copy the `.env.example` to `.env`. Everything should be configured for you, assuming:

- You hve PHP 8.4 running
- You have NodeJS >= 22 running

Then run these commands to get up and running:

```bash
npm install
npm run build
composer install
php artisan migrate --seed
php artisan serve
```

The app should now be accessible at http://127.0.0.1:8000/login

---

## Project

### What’s already been built

We have an application that allows users to track their to-do items, including an extensible rules engine. The following has already been built:

- a `User` model
- a `Task` model
- the ability to create a new task
- the ability to complete a task
- a rules engine for users to configure rules for tasks

The following rules have been added to the frontend (`TaskRules.tsx`):

- Setting the task's due date when a `#deadline` tag is present
- Appending a `#deadline` tag when the task's due date is within 2 days
- Appending a `#pastdue` tag when the task's due date is in the past

Note: The UI is intentionally basic and missing features such as the ability to delete a rule.

### For you to do on your own

Ahead of our call, we'd like to make sure you have the repo set up and running locally. You should review the existing code and become familiar with as we'll ask you to implement a new feature during our call.
