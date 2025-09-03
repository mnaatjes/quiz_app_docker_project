# Web Application Sitemap & User Flow

This document outlines the user-facing routes of the Quiz App, their purpose, and the navigation paths (links and forms) that connect them.

---

## Public Routes (No Login Required)

These pages are accessible to any visitor.

### 1. Landing / Login Page

- **Route**: `GET /` or `GET /login`
- **Description**: Displays the main login page for users.
- **User Actions**:
    - **Submits a form** to `POST /login` to attempt authentication.
    - **Links to** `GET /register` for new user registration.

### 2. User Authentication

- **Route**: `POST /login`
- **Description**: Handles the user's login attempt. On success, redirects to `GET /dashboard`.

### 3. Registration Page

- **Route**: `GET /register`
- **Description**: Displays a form for new users to create an account.
- **User Actions**:
    - **Submits a form** to `POST /users` to create a new user.
    - **Links to** `GET /login` to go back to the login page.

### 4. User Creation

- **Route**: `POST /users`
- **Description**: Handles the new user creation. On success, redirects to the login page.

### 5. Static Pages

- **Route**: `GET /about`
  - **Description**: Displays the "About" page.
- **Route**: `GET /contact`
  - **Description**: Displays the "Contact" page.

---

## Authenticated Routes (Login Required)

These pages and actions require an active user session.

### 1. User Dashboard

- **Route**: `GET /dashboard`
- **Description**: The main landing page after a user logs in. Displays a list of quizzes they have taken or can take.
- **User Actions**:
    - **Links to** `GET /quizzes/create` to start building a new quiz.
    - **Links to** `GET /quizzes/{quiz_id}` to play an existing quiz again.

### 2. Create Quiz Page

- **Route**: `GET /quizzes/create`
- **Description**: Shows a form where a user can define the parameters for a new quiz (e.g., category, difficulty).
- **User Actions**:
    - **Submits a form** to `POST /quizzes/create` to generate the new quiz.

### 3. Generate New Quiz

- **Route**: `POST /quizzes/create`
- **Description**: Handles the generation of a new quiz. On success, redirects to `GET /quizzes/{quiz_id}` to begin playing immediately.

### 4. Play Quiz Page

- **Route**: `GET /quizzes/{quiz_id}`
- **Description**: Displays the questions and answers for the user to play the quiz.
- **User Actions**:
    - **Submits a form** to `POST /quizzes/{quiz_id}/submit` with the user's answers.

### 5. Submit Quiz Answers

- **Route**: `POST /quizzes/{quiz_id}/submit`
- **Description**: Processes the user's answers, calculates the score, and saves the results. On success, redirects to `GET /quizzes/{quiz_id}/results`.

### 6. Quiz Results Page

- **Route**: `GET /quizzes/{quiz_id}/results`
- **Description**: Displays the results of the completed quiz, including score, correct/incorrect counts, etc.
- **User Actions**:
    - **Links to** `GET /dashboard` to return to the main dashboard.

---

## Site-Wide Navigation & Notes

- **Main Navigation (`main.php`)**: The header on all pages contains links to Home (`/dashboard`), About (`/about`), and Contact (`/contact`).
- **User Navigation (`user_nav.php`)**: 
    - When logged in, this bar provides a **Logout** link pointing to `/logout`.
    - When logged out, it provides links to `Login` and `Register`.
- **Missing Route**: The `user_nav.php` partial links to `/logout`, but this route is **not defined** in `routes/web.php`. You will need to add it to handle user logout.

---

## Graphical Route Map

This tree illustrates the hierarchy of the application's URLs.

```
/
├─── login (GET, POST)
├─── logout (GET)
├─── register (GET)
├─── users (POST)
├─── dashboard (GET)
├─── about (GET)
├─── contact (GET)
└─── quizzes/
    ├─── create (GET, POST)
    └─── {quiz_id}/
        ├─── (play) (GET)
        ├─── submit (POST)
        └─── results (GET)
```