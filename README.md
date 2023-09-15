# Symfony Telegram Notifier

Symfony приложение для отправки уведомлений в Telegram

## Локальная разработка

&#x2705; Выполнить клонирование проекта\
&#x2705; Создать `.env` файл на основе [.env.example](./.env.example)\
&#x2705; Создать `phpstan.neon` файл (если требуется личная настройка)

Затем выполнить сборку контейнеров

```bash
docker-compose -f docker-compose.yml up -d --build
```

## Общий рабочий процесс

&#x2705; Пишем код\
&#x2705; Пишем тесты\
&#x2705; Проходим ректор\
&#x2705; Проходим линтер\
&#x2705; Обновляем [openapi документацию](./openapi.json) (при необходимости)

## Команды

### Сборка контейнеров

```bash
docker-compose -f docker-compose.yml up -d --build
```

### Запуск контейнеров

```bash
docker-compose -f docker-compose.yml up -d
```

### Запуск ректора (показывает изменения, которые он выполнит)

```bash
composer rector:diff
```

### Запуск ректора (выполняет рефакторинг)

```bash
composer rector:run
```

### Запуск тестов

```bash
composer test
```

### Запуск линтера

```bash
composer lint
```
