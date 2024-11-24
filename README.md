
# Price Watcher

**Price Watcher** — це сервіс для відстеження змін цін на оголошення на платформі OLX.

---

## Вимоги

Перед початком переконайтеся, що у вас встановлено:

- **PHP** версії 8.1 або вище
- **Composer** для керування залежностями
- **MySQL** для роботи з базою даних

---

## 1. Клонування проекту

Клонування проекту з репозиторію (або скопіюйте файли вручну):
```bash
git clone https://github.com/salionovich/price_watcher.git
cd price_watcher
```

---

## 2. Встановлення залежностей

Виконайте команду:
```bash
composer install
```

Це встановить усі необхідні залежності, зокрема:
- Symfony DomCrawler
- Symfony HttpClient
- PHPUnit для тестування

---

## 3. Налаштування бази даних

1. Створіть базу даних у MySQL:
   ```sql
   CREATE DATABASE price_watcher;
   ```

2. Налаштуйте підключення до бази даних у файлі `config/database.php`:
   ```php
   return [
       'host' => '127.0.0.1',
       'database' => 'price_watcher',
       'username' => 'your_username',
       'password' => 'your_password',
       'charset' => 'utf8mb4',
   ];
   ```

3. Імпортуйте структуру бази з дампу `db/dump_price_watcher.sql`:
   ```bash
   mysql -u your_username -p price_watcher < dump_price_watcher.sql
   ```

---

## 4. Запуск локального сервера

Для локального тестування запустіть вбудований сервер PHP:
```bash
... (команди запуску у вашому середовищі)
```

Після цього проект буде доступний за адресою:
```
http://localhost:8000
```

---

## 5. Тестування проекту

### 5.1 Запуск тестів
Запустіть тести для перевірки функціональності:
```bash
./vendor/bin/phpunit tests/SubscriptionTest.php
```

### 5.2 Генерація звіту про покриття тестами
Запустіть тести з генерацією звіту про покриття:
```bash
./vendor/bin/phpunit --coverage-html coverage
```

Після завершення команда згенерує звіт у папці `coverage`. Відкрийте файл `coverage/index.html` у браузері для перегляду детальної інформації.

---

## 6. Тестування API (Postman)

### 6.1 Підписка на оголошення
- **Метод:** `POST`
- **URL:** `http://localhost:8000/subscribe`
- **Headers:**
  ```json
  {
      "Content-Type": "application/json"
  }
  ```
- **Body (raw):**
  ```json
  {
      "url": "https://www.olx.ua/d/uk/obyavlenie/toyota-scion-avtomat-1-5b-IDVKEc6.html",
      "email": "test@example.com"
  }
  ```

### 6.2 Підтвердження email
- **Метод:** `GET`
- **URL:** `http://localhost:8000/confirm?token=<your_confirmation_token>`

### 6.3 Перегляд підписок
- **Метод:** `GET`
- **URL:** `http://localhost:8000/subscriptions`

---

## 7. Структура проекту

```plaintext
price-watcher/
├── config/                      # Конфігурація бази даних
├── index.php                    # Роутінг
├── src/                         # Основна логіка проекту
│   ├── Database/                # Робота з базою даних
│   ├── Services/                # Сервіси (підписка, підтвердження тощо)
├── tests/                       # Тестові файли
├── composer.json                # Залежності проекту
├── db/
│   └── dump_price_watcher.sql   # SQL-дамп для створення бази даних
└── README.md                    # Інструкція
```

---

## 8. Підтримка

Якщо у вас виникли проблеми, створіть запит в репозиторії GitHub або напишіть на [salionovych@gmail.com].

---

## 9. Запуск готового проекту

Після виконання вищевказаних кроків проект буде готовий до роботи. Використовуйте його для відстеження змін цін на платформі OLX.

---

## Команди для швидкого запуску

- **Запуск серверу:**
  ```bash
  ... (команди вашого середовища)
  ```
  
- **Встановлення залежностей:**
  ```bash
  composer install
  ```

- **Тестування:**
  ```bash
  ./vendor/bin/phpunit
  ```

- **Генерація покриття:**
  ```bash
  ./vendor/bin/phpunit --coverage-html coverage
  ```
  
- **Налаштування cron:**
  ```bash
  crontab -e
  ```
  
- **Налаштування cron:**
  ```bash
  */5 * * * * php /path/to/project/cron/monitor_prices.php
  ```

---

Тепер ваш проект має бути повністю налаштований і готовий до використання!
