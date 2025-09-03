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

## 2.0 Login Sequence Diagram