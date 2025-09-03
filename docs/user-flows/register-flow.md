# Registration User Flow

## 1.0 Graphial User Flow
```
[Entry-point]
|
└─── GET: /
    └─── GET: /register
        └─── POST: /users
            ├─── (Failure) Redirect /register
            └─── (Success) POST /login
                ├─── (Failure) Redirect /login
                └─── (Success) Redirect /dashboard
```