# Todo List

## 1.0 General
- [x] Complete QuizController->submit();
- [x] Complete QuizController->results();
- [x] Create `docs/` directory for quiz_app
- [ ] Create Features list in `docs/`
- [ ] Move `todo.md` to docs

### 1.1 General Backend
- [ ] Refine **data-flow**  and **data-object** of objects passed from-database-table -> to-render
  - [ ] Refine how questions are called for **data-object**
  - [ ] Refine how answers are called for **data-object**
  - [ ] Refine how **data-object** is assembled

- [ ] Rename non-descriptive **UserService** and **QuizService** methods
  - [ ] UserService->
  - [ ] QuizService->

## 2.0 Sessions and User Login / Registration

- [x] Prototype User Nav Section

### 2.1 Sessions
- [ ] Integrate User Nav Section with UserService->$session
- [ ] Define Flow of User Session Persistence
- [ ] Determine conditions for User Session Unset

### 2.2 User Login Flow
- [ ] Define User Login Flow
- [ ] **Refine Password validation mechanisms**
  - [ ] Update Password Hashing method
  - [ ] Integrate password hashing
  - [ ] Define Password data flow

### 2.3 User Registration Flow
- [ ] Define User Registration Flow

## 3.0 Quiz Flow

### 3.1 Play Quiz
- [ ] Check that answers are shuffled during new play
- [ ] Check that questions are shuffled during new play
- [ ] Abort Mechanism for Quiz
- [ ] Pair Down data object sent to play_quiz.php
- [x] Quiz "results" page after completion

## 4.0 DB and Table Records

### 4.1 Users Table
- [ ] Create method for hashing and updating existing *dummy* users in **users** table

### 4.2 Quizzes Table
- [ ] Create method for auto-generating default **title**
- [ ] Clear existing Data **Data is incorrect / inconsistent**
- [ ] Create / Import new entries in table

### 4.3 UserQuizzes Table
- [ ] **Update user_quizzes table:**
  - [ ] Clear existing Data
  - [ ] Import new data from **quizzes** table

## Appendix A

*How to handle quiz abandonment?*

- *What happens to the session when the user:*
  - leaves
  - closes browser
  - reloads / refreshes

*What are best practices for passing data back from form?*

- What should object look like?
- Should object replicate model in any way?
- What happens to empty / unanswered questions?

*What happens to data when user hits "Reset" button?*

## 3.0 Logout Flow

*What is the best way to handle ending a session?*

## 4.0 Miscellaneous

*How to handle updates to some elements on sign-in, question pulls, etc?*

*When session active, how to automatically by-pass login if user goes to index.php?*