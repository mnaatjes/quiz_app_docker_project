# Login User Flow

## 1.0 Graphial User Flow Map
```
[Entry-point]
|
└─── GET: /
    └─── POST: /login
        ├─── (Failure) Redirect /login
        └─── (Success) Redirect /dashboard
```

```
[Redirect]
|
└─── GET: /login
    └─── POST: /login
        ├─── (Failure) Redirect /login
        └─── (Success) Redirect /dashboard
```

## 2.0 Login Sequence of Sessions

*How long should a session persist?*

*When should a session be unset?* 

*Why is session_start() necessary?*

*Why does the session_status say "active" yet there are no session properties?*


### 2.1 Middleware Authorization Failure

The standard and most secure practice in a middleware authorization failure is:

1. **Check Authentication:** The middleware checks for a valid session or authentication token.
2. **Authentication Fails:** The check returns false.
3. **Destroy the Session:** Call session_destroy() (and other necessary session cleanup functions like unsetting $_SESSION and the session cookie) to completely terminate the session.
4. **Redirect:** Send the user to the login page. Encode error message in URL parameter

## 3.0 Session Flow through Application


### 3.1 Session Management Flow

The typical workflow for session management is as follows:

1. **Start the Session:** On every page load, including the entry point, you should call session_start(). This function either resumes an existing session or starts a new one if none exists. It does not automatically destroy the session.
2. **Check for Authentication:** After starting the session, a middleware or an authentication check on protected pages (any page other than the login or public pages) verifies if the user is authenticated. This check usually involves looking for a specific key in the $_SESSION superglobal, such as $_SESSION['user_id'].
3. **Redirect if Unauthenticated:** If the authentication check fails, the user is redirected to the login page. This is the only time the session might be destroyed—specifically, to clear out any invalid data before redirecting.
4. **Allow Access if Authenticated:** If the authentication check passes, the user is allowed to view the requested page, and their session remains active.

### 3.2 Session Management Graph

**Path:** `/`
**Desc:** User entry point / login
**Flow:**
1. User arrives
   1. `UserAuth@handler` checks for session and user_id
      1. **On Success** Proceed to dashboard
      2. **On Failure** Remain and await for credentials
         1. destroy_session()
2. User Submits credentials via POST form
   1. `UserAuth@handler` checks for session and user_id
      1. **On Success** Proceed to dashboard
      2. **On Failure** Remain and await for credentials
         1. destroy_session()
         2. Redirect to `/login` with URI error message

```
│
├─── / Entry-point UserAuth@handler checks for session and user_id
│   ├─── Proceed to dashboard if user_id correct
│   └─── Remain on / to enter credentials and destroy_session() just in case


├───
└───
```
