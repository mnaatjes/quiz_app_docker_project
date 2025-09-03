# Login User Flow

## 1.0 Graphial Route Map
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
