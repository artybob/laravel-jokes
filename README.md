# Laravel Jokes API & Visitor Tracker

## Технологии
- Laravel 13
- PHP 8.4
- MySQL 8.0
- Docker / Docker Compose
- JavaScript (Vanilla)
- Chart.js

## Реализованные задачи

### 1. Laravel проект с Docker
- Консольная команда `jokes:fetch` для получения шуток из внешнего API
- Планировщик задач (каждые 5 минут)
- API эндпоинты для получения данных в JSON формате

### 2. Динамические поля формы
- JavaScript dynamic-fields.js скрипт для фильтрации полей по выбранному типу
- Показываются только поля, в атрибуте `name` которых есть значение выбранного элемента списка

### 3. Счетчик посещений
- Клиентский скрипт для сбора данных tracker.js (IP, город, устройство, браузер). Тестировать в (http://localhost:8080/test-tracker.html) 
- Страница статистики (http://localhost:8080/statistics) с графиками (почасовые посещения, разбивка по городам)
- Бэкенд для хранения статистики в БД

## Установка

```bash
# Клонировать репозиторий
git clone <repository-url>
cd laravel-jokes

# Запустить установку
bash setup.sh
```

### API Endpoints
```bash
Method	Endpoint	Description
GET	/api/jokes	Получить все шутки
GET	/api/jokes?type={type}	Получить шутки по типу
GET	/api/jokes/types	Получить список типов
POST	/api/track	Отправить данные о посещении
GET	/api/stats	Получить статистику
```

## Запустить получение шуток вручную
```bash
docker compose exec php php artisan jokes:fetch
```
## Запустить планировщик
```bash
docker compose exec php php artisan schedule:work
```
## Посмотреть API ответ
```bash
curl http://localhost:8080/api/jokes
```
