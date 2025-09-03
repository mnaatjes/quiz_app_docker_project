# Quiz App Project Sitemap

This document provides a tree-like representation of all files and directories within the `quiz_app` project.

```
quiz_app/
├─── .gitignore
├─── README.md
├─── app/
│   ├─── Controllers/
│   │   ├─── AppController.php
│   │   ├─── DashboardController.php
│   │   ├─── QuizController.php
│   │   ├─── TestController.php
│   │   └─── UserController.php
│   ├─── Middleware/
│   │   └─── UserAuth.php
│   ├─── Models/
│   │   ├─── AnswerModel.php
│   │   ├─── CategoryModel.php
│   │   ├─── DifficultyModel.php
│   │   ├─── QuestionModel.php
│   │   ├─── QuizModel.php
│   │   ├─── TestModel.php
│   │   ├─── UserModel.php
│   │   └─── UserQuizModel.php
│   ├─── Repositories/
│   │   ├─── AnswerRepository.php
│   │   ├─── CategoryRepository.php
│   │   ├─── DifficultyRepository.php
│   │   ├─── QuestionRepository.php
│   │   ├─── QuizRepository.php
│   │   ├─── TestRepository.php
│   │   ├─── UserQuizRepository.php
│   │   └─── UserRepository.php
│   ├─── Services/
│   │   ├─── ErrorService.php
│   │   ├─── QuizService.php
│   │   └─── UserService.php
│   ├─── Utils/
│   │   └─── Utility.php
│   └─── Views/
│       ├─── about.php
│       ├─── contact.php
│       ├─── create_quiz.php
│       ├─── dashboard.php
│       ├─── layouts/
│       │   ├─── main.php
│       │   └─── user_nav.php
│       ├─── login.php
│       ├─── play_quiz.php
│       ├─── quiz_results.php
│       └─── register.php
├─── composer.json
├─── composer.lock
├─── config/
│   ├─── .env
│   ├─── services.php
│   └─── Services/
│       ├─── Middleware.php
│       ├─── QuizServices.php
│       ├─── Shared.php
│       └─── UserServices.php
├─── docs/
│   ├─── site-map.md
│   └─── todo.md
├─── public/
│   ├─── .htaccess
│   ├─── index.php
│   └─── static/
│       └─── home.html
├─── routes/
│   ├─── _api.php
│   ├─── _web.php
│   └─── web.php
└─── vendor/
    ├─── autoload.php
    ├─── composer/
    │   ├─── autoload_classmap.php
    │   ├─── autoload_namespaces.php
    │   ├─── autoload_psr4.php
    │   ├─── autoload_real.php
    │   ├─── autoload_static.php
    │   ├─── ClassLoader.php
    │   ├─── installed.json
    │   ├─── installed.php
    │   ├─── InstalledVersions.php
    │   ├─── LICENSE
    │   └─── platform_check.php
    └─── mnaatjes/
        └─── mvc-framework/
            ├─── .gitignore
            ├─── bootstrap.php
            ├─── composer.json
            ├─── LICENSE
            ├─── README.md
            ├─── resources/
            │   └─── data/
            │       ├─── create_orders.sql
            │       ├─── create_products.sql
            │       ├─── insert_orders.sql
            │       ├─── insert_products.sql
            │       └─── users.json
            ├─── src/
            │   ├─── Container.php
            │   ├─── DataAccess/
            │   │   ├─── BaseRepository.php
            │   │   ├─── Database.php
            │   │   ├─── ORM.php
            │   │   └─── SchemaManager.php
            │   ├─── HttpCore/
            │   │   ├─── HttpRequest.php
            │   │   ├─── HttpResponse.php
            │   │   └─── Router.php
            │   ├─── MVCCore/
            │   │   ├─── BaseController.php
            │   │   ├─── BaseModel.php
            │   │   └─── BaseView.php
            │   ├─── SessionsCore/
            │   │   └─── SessionManager.php
            │   └─── Utils/
            │       ├─── DataGenerator.php
            │       └─── DotEnv.php
            └─── Tests/
                ├─── .env
                ├─── main.php
                ├─── SomeController.php
                ├─── SomeModel.php
                ├─── SomeRepository.php
                └─── SomeService.php
```