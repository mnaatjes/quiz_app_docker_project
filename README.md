# Quiz Application with Docker

# Contents

# 1.0. Overview
Quiz App Repo in VM with Docker

# 2.0 PHP Configuration

## 2.1 Extensions
Below are extensions addded to `php-fpm/Dockerfile`

* **BC Math:** 
    ```yml
    RUN docker-php-ext-install bcmath
    ```
* **GMP (GNU Multiple Precision):**
    **IMPORTANT:** Install dependencies BEFORE installing gmp
    ```yml
    RUN apt-get update && \
    apt-get install -y libgmp-dev && \
    rm -rf /var/lib/apt/lists/*
    ```

    ```yml
    RUN docker-php-ext-install gmp
    ```

    * **Testing...**