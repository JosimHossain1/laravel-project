# Laravel 13 — Zero থেকে CRUD: এই প্রজেক্টের সম্পূর্ণ গাইড

> এই গাইডটা তোমার `first-app` প্রজেক্টের **আসল** স্ট্রাকচার দেখে লেখা।
> তুমি যেখানে আছো সেখান থেকেই শুরু করো। প্রতিটা ধাপ কপি-পেস্ট করে চালাতে পারবে।

---

## সূচিপত্র

1. [এই প্রজেক্টে ঠিক কী কী আছে](#1-এই-প্রজেক্টে-ঠিক-কী-কী-আছে)
2. [প্রজেক্ট চালু / বন্ধ করা](#2-প্রজেক্ট-চালু--বন্ধ-করা)
3. [Root ফোল্ডারগুলো এক নজরে](#3-root-ফোল্ডারগুলো-এক-নজরে)
4. [প্রতিটা ফোল্ডারের কাজ (বিস্তারিত)](#4-প্রতিটা-ফোল্ডারের-কাজ-বিস্তারিত)
5. [একটা Request কীভাবে পুরো অ্যাপ পার হয়](#5-একটা-request-কীভাবে-পুরো-অ্যাপ-পার-হয়)
6. [কে কোথায় থাকে — ম্যাপিং টেবিল](#6-কে-কোথায়-থাকে--ম্যাপিং-টেবিল)
7. [CRUD: শূন্য থেকে অ্যাডভান্স](#7-crud-শূন্য-থেকে-অ্যাডভান্স)
8. [Laravel-এর Naming Convention](#8-laravel-এর-naming-convention)
9. [দরকারি Artisan কমান্ড চিটশিট](#9-দরকারি-artisan-কমান্ড-চিটশিট)
10. [সাধারণ এরর ও সমাধান](#10-সাধারণ-এরর-ও-সমাধান)
11. [এরপর কী শিখবে](#11-এরপর-কী-শিখবে)

---

## 1. এই প্রজেক্টে ঠিক কী কী আছে

| জিনিস | ভার্সন / অবস্থা | কোথায় দেখা যায় |
|---|---|---|
| Laravel | **13.42.0** | `composer.json`, `vendor/laravel/framework` |
| PHP | **8.5.12** | Docker container-এর ভেতরে |
| ডাটাবেজ | **MySQL 8.4** (Docker container `first-app-mysql-1`) | `.env` → `DB_CONNECTION=mysql` |
| Redis | আছে (কিন্তু এখন ব্যবহার হচ্ছে না) | `compose.yaml` → `redis` service |
| Frontend build | **Vite 8 + Tailwind CSS 4** | `package.json`, `vite.config.js` |
| Docker tool | **Laravel Sail** | `compose.yaml`, `vendor/bin/sail` |
| Authentication (login/register) | **নেই** — ফাঁকা প্রজেক্ট | `routes/web.php`-এ শুধু `/` |
| API | **নেই** (`routes/api.php` তৈরি হয়নি) | `bootstrap/app.php` |
| টেস্ট | PHPUnit 12 | `phpunit.xml`, `tests/` |

**গুরুত্বপূর্ণ ৩টা কথা:**

1. **তোমার `.env`-এ MySQL সেট করা আছে**, তাই `database/database.sqlite` ফাইলটা এখন **ব্যবহার হচ্ছে না** (এটা শুধু ফলব্যাক)।
2. এই মুহূর্তে ডাটাবেজে ৯টা টেবিল আছে: `users`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `password_reset_tokens`, `migrations`। এগুলোর কোনোটাই তোমার হাতে লেখা না — Laravel-এর ডিফল্ট migration।
3. `public/hot` ফাইলটা আছে মানে **Vite dev server চলছে**। তাই এখন `npm run dev` চালু রাখলে Tailwind/CSS সাথে সাথে আপডেট হবে।

---

## 2. প্রজেক্ট চালু / বন্ধ করা

তুমি WSL/Linux-এ আছো, `php` বা `composer` হোস্টে ইনস্টল নেই — সব কিছু **Docker container-এর ভেতরে** চলে। তাই সব কমান্ড `sail` দিয়ে চালাতে হবে।

```bash
cd /home/laptop/apps/laravel/first-app

# ১) Docker container চালু করা (ব্যাকগ্রাউন্ডে)
./vendor/bin/sail up -d

# ২) চলছে কিনা দেখা
docker ps

# ৩) অ্যাপ খোলো
#    http://localhost
```

> `compose.yaml`-এ `80:80` ম্যাপ করা আছে, তাই অ্যাপ চলবে **http://localhost** -এ।
> `.env`-এর `APP_URL=http://localhost:8000` শুধু লিংক বানানোর জন্য; চাইলে `http://localhost` করে দিতে পারো।

প্রতিদিনের কমান্ড:

```bash
# Vite (CSS/JS live reload) — আলাদা টার্মিনালে চালু রাখো
./vendor/bin/sail npm run dev

# বন্ধ করা
./vendor/bin/sail down
```

**টিপস:** প্রতিবার `./vendor/bin/sail` লিখতে কষ্ট হলে ঐ টার্মিনালে একবার:

```bash
alias sail='./vendor/bin/sail'
```

এরপর শুধু `sail up -d`, `sail artisan ...` লিখলেই হবে। (নতুন টার্মিনালে আবার alias দিতে হবে।)

Laravel 13-এ নতুন `artisan dev` কমান্ড আছে, যেটা একসাথে ৪টা প্রসেস চালায়:

```bash
./vendor/bin/sail artisan dev:list
# server  -> php artisan serve
# queue   -> php artisan queue:listen
# logs    -> php artisan pail
# vite    -> npm run dev
```

তবে Sail-এ `serve` পোর্ট 8000 (container-এর ভেতরে, বাইরে খোলা নেই), তাই সবচেয়ে নির্ভরযোগ্য উপায় হলো `sail up -d` + `sail npm run dev`।

---

## 3. Root ফোল্ডারগুলো এক নজরে

```
first-app/
├── app/                  ← তোমার নিজের PHP কোড (controller, model, provider)
├── bootstrap/            ← অ্যাপ "বুট" হয় এখান থেকে (app.php = নতুন Kernel)
├── config/               ← সব সেটিংসের ডিফল্ট মান
├── database/             ← migration, factory, seeder, sqlite ফাইল
├── public/               ← ওয়েবসাইটের আসল root (index.php, build assets)
├── resources/            ← Blade view + CSS + JS (build হওয়ার আগে)
├── routes/               ← URL ↔ কোডের ম্যাপ (web.php)
├── storage/              ← log, cache, session, আপলোড করা ফাইল
├── tests/                ← automated test
├── vendor/               ← Composer-এর নামানো PHP প্যাকেজ (হাত দিও না)
├── node_modules/         ← npm-এর নামানো JS প্যাকেজ (হাত দিও না)
├── .env                  ← তোমার আসল সেটিংস (secret)
├── .env.example          ← টিমের জন্য টেমপ্লেট (secret ছাড়া)
├── artisan               ← CLI এন্ট্রি পয়েন্ট (php artisan ...)
├── composer.json         ← PHP ডিপেন্ডেন্সি
├── package.json          ← JS ডিপেন্ডেন্সি
├── vite.config.js        ← CSS/JS build কনফিগ
├── compose.yaml          ← Docker (Sail) সার্ভিস: app + mysql + redis
├── phpunit.xml           ← টেস্ট কনফিগ
└── AGENTS.md / CLAUDE.md ← AI assistant-দের জন্য ইনস্ট্রাকশন
```

---

## 4. প্রতিটা ফোল্ডারের কাজ (বিস্তারিত)

### 4.1 `app/` — তোমার কোড

এখন এখানে মাত্র ৩টা জিনিস আছে:

```
app/
├── Http/
│   └── Controllers/
│       └── Controller.php      ← সব controller-এর বেস (abstract, খালি)
├── Models/
│   └── User.php                ← User মডেল (Eloquent)
└── Providers/
    └── AppServiceProvider.php  ← অ্যাপ চালু হওয়ার সময় কোড চালানোর জায়গা
```

- **`Http/Controllers`** — HTTP request ধরে কাজ করে। `store()`, `index()`, `update()` ইত্যাদি মেথড এখানে থাকে।
- **`Models`** — ডাটাবেজ টেবিলের PHP প্রতিনিধি। `User` মডেল ↔ `users` টেবিল।
- **`Providers`** — service container-এ কিছু রেজিস্টার করা, বা অ্যাপ শুরুর সময় কিছু সেট করা (যেমন `Model::preventLazyLoading()`).

> **নোট (Laravel 11+ / 13):** পুরনো টিউটোরিয়ালে `app/Http/Middleware/`, `app/Http/Kernel.php`, `app/Exceptions/Handler.php` দেখবে — এগুলো **এখন নেই**। ওই কাজগুলো এখন `bootstrap/app.php`-তে হয়।

`app/Http/Controllers/Controller.php`:

```php
<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
}
```

এটা ইচ্ছাকৃতভাবে **খালি**। মানে `$this->authorize(...)` বা `$this->validate(...)` ডিফল্টে পাবে না। দরকার হলে `Gate::authorize()` ব্যবহার করো (নিচে দেখানো আছে)।

---

### 4.2 `bootstrap/` — অ্যাপের "ইঞ্জিন স্টার্ট"

```
bootstrap/
├── app.php          ← ★ নতুন Kernel: routing, middleware, exception
├── providers.php    ← কোন provider লোড হবে
└── cache/           ← performance cache (packages.php, services.php)
```

`bootstrap/app.php` এখানেই ঠিক হয়:

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',                 // http://localhost/up -> health check
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // এখানে global middleware যোগ/বাদ দেওয়া হয়
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // error কীভাবে দেখাবে তা এখানে
    })->create();
```

নতুন middleware বানালে সেটা এখানে alias/append করতে হয় — আগের মতো `Kernel.php`-তে না।

---

### 4.3 `config/` — সব সেটিংসের ডিফল্ট

```
config/
├── app.php         ← নাম, timezone, locale, providers, aliases
├── auth.php        ← কে/login কীভাবে (guard, password broker)
├── cache.php       ← cache driver (database/redis/file)
├── database.php    ← DB connection (mysql, sqlite, pgsql...)
├── filesystems.php ← disk (local, public, s3)
├── logging.php     ← log কোথায় যাবে
├── mail.php        ← mail driver
├── queue.php       ← background job driver
├── services.php    ← third-party API key
└── session.php     ← session driver/lifetime
```

**সবচেয়ে গুরুত্বপূর্ণ ধারণা:** `config/*.php` ফাইল **`.env` পড়ে**।

```php
// config/database.php
'default' => env('DB_CONNECTION', 'sqlite'),
'mysql' => [
    'host'     => env('DB_HOST', '127.0.0.1'),
    'database' => env('DB_DATABASE', 'laravel'),
    ...
],
```

তাই কোডে সবসময় `config('database.default')` ব্যবহার করো, `env('DB_CONNECTION')` **না**।
কারণ production-এ `php artisan config:cache` চালালে `.env` আর পড়া যায় না — শুধু config cache কাজ করে।

> `.env` বদলালে যদি পরিবর্তন না দেখো: `./vendor/bin/sail artisan config:clear`

---

### 4.4 `routes/` — URL-এর ম্যাপ

```
routes/
├── web.php      ← ব্রাউজারের সব URL (session, CSRF, cookie middleware সহ)
└── console.php  ← artisan কমান্ডের closure + schedule
```

`routes/web.php` এখন:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');     // resources/views/welcome.blade.php
});
```

- `web.php`-এ থাকা route-এ **session, cookie, CSRF protection** অটো থাকে।
- API বানাতে চাইলে: `./vendor/bin/sail artisan install:api` → `routes/api.php` তৈরি হবে (Sanctum সহ)।

চেক করার কমান্ড:

```bash
./vendor/bin/sail artisan route:list
```

আউটপুটে দেখবে `/` আর `/up` ছাড়া কিছু নেই — কারণ এখনো কোনো CRUD বানানো হয়নি।

---

### 4.5 `database/` — ডাটাবেজের সবকিছু

```
database/
├── migrations/     ← টেবিল বানানোর "গিট হিস্টোরি" (PHP কোডে)
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   └── 0001_01_01_000002_create_jobs_table.php
├── factories/      ← ডেমি/ফেক ডাটা বানানোর রেসিপি
│   └── UserFactory.php
├── seeders/        ← ডাটাবেজ ভরার স্ক্রিপ্ট
│   └── DatabaseSeeder.php
└── database.sqlite ← ফলব্যাক SQLite ফাইল (তোমার ক্ষেত্রে অব্যবহৃত)
```

**Migration কেন?** phpMyAdmin-এ হাতে টেবিল বানালে টিমের অন্য কেউ সেটা পাবে না, ডিপ্লয়েও না। Migration হলো টেবিলের ইতিহাস — কোড হিসেবে। `git pull` করে `php artisan migrate` চালালেই সবার ডাটাবেজ এক।

`migrations` টেবিলে Laravel মনে রাখে কোন migration চলেছে। তাই একই migration দুইবার চলে না।

**Factory** = ভুয়া ডাটা (টেস্ট/সিডিং-এর জন্য)।
**Seeder** = ডাটাবেজে ডাটা ঢোকানোর স্ক্রিপ্ট (factory ব্যবহার করে)।

---

### 4.6 `resources/` — যা ইউজার দেখে

```
resources/
├── views/
│   └── welcome.blade.php   ← Blade টেমপ্লেট (HTML + {{ }} + @if ...)
├── css/
│   └── app.css             ← Tailwind এন্ট্রি (@import 'tailwindcss';)
└── js/
    └── app.js              ← JS এন্ট্রি
```

**Blade** = Laravel-এর টেমপ্লেট ইঞ্জিন। ফাইল নাম `xxx.blade.php`।

```blade
{{ $post->title }}         ← escape করে প্রিন্ট করে (নিরাপদ)
{!! $post->body !!}        ← escape ছাড়া (খুব সাবধান!)
@if ($post->is_published) ... @endif
@foreach ($posts as $post) ... @endforeach
@csrf                      ← form-এ সিকিউরিটি টোকেন
```

`css/app.css`, `js/app.js` সরাসরি `<link>` করা হয় না। `vite.config.js`-এর `input`-এ থাকে, আর Blade-এ:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

`npm run dev` চললে Vite সাথে সাথে সার্ভ করে, `npm run build` চালালে `public/build/`-এ আসল ফাইল যায়।

---

### 4.7 `public/` — ওয়েব সার্ভারের আসল root

```
public/
├── index.php    ← ★ প্রতিটা request প্রথমে এখানে আসে
├── hot          ← Vite dev server চলছে বোঝায়
├── robots.txt
└── favicon.ico
```

Nginx/Apache-এর document root হলো `public/`। তাই `storage` বা `.env` ব্রাউজার থেকে দেখা যায় না — এটাই নিরাপত্তার মূল ভিত্তি।

> ইউজার আপলোড করা ফাইল ব্রাউজারে দেখাতে হলে: `php artisan storage:link`
> এটা `public/storage` → `storage/app/public`-এ symlink বানায়।

---

### 4.8 `storage/` — অ্যাপের "কাজের জায়গা"

```
storage/
├── app/
│   ├── private/     ← প্রাইভেট আপলোড
│   └── public/      ← পাবলিক আপলোড (storage:link দরকার)
├── framework/
│   ├── cache/       ← compiled cache
│   ├── sessions/    ← file session (তোমার ক্ষেত্রে DB session)
│   └── views/       ← compile হওয়া Blade টেমপ্লেট
└── logs/
    └── laravel.log  ← ★ এরর দেখার আসল জায়গা
```

কিছু কাজ না করলে প্রথমে দেখো: `storage/logs/laravel.log`, অথবা লাইভ দেখতে:

```bash
./vendor/bin/sail artisan pail
```

---

### 4.9 `tests/` — অটোমেটেড টেস্ট

```
tests/
├── Feature/         ← পুরো HTTP request টেস্ট (route + controller + DB)
│   └── ExampleTest.php
├── Unit/            ← ছোট আলাদা ক্লাসের টেস্ট
│   └── ExampleTest.php
└── TestCase.php     ← বেস ক্লাস
```

চালাও:

```bash
./vendor/bin/sail artisan test
```

`phpunit.xml`-এ টেস্টের জন্য আলাদা সেটিং: `DB_DATABASE=testing`, `CACHE_STORE=array`, `QUEUE_CONNECTION=sync`। অর্থাৎ টেস্ট আসল ডাটাবেজ নষ্ট করে না (Sail `testing` নামে আলাদা DB বানিয়ে রাখে)।

---

### 4.10 `vendor/` আর `node_modules/`

- `vendor/` = Composer দিয়ে নামানো PHP প্যাকেজ (Laravel framework, PHPUnit...)
- `node_modules/` = npm দিয়ে নামানো JS প্যাকেজ (Vite, Tailwind...)

**এগুলো কখনো হাতে এডিট করবে না।** `.gitignore`-এ আছে, git-এ যায় না।

লাগলে:

```bash
./vendor/bin/sail composer install   # vendor আবার তৈরি
./vendor/bin/sail npm install        # node_modules আবার তৈরি
```

---

### 4.11 Root-এর কনফিগ ফাইল

| ফাইল | কাজ |
|---|---|
| `.env` | আসল সিক্রেট সেটিংস (DB পাসওয়ার্ড, APP_KEY)। **git-এ যাবে না** |
| `.env.example` | টিমের জন্য টেমপ্লেট। নতুন কেউ কপি করে `.env` বানায় |
| `artisan` | CLI এন্ট্রি: `php artisan ...` |
| `composer.json` | PHP প্যাকেজ + autoload (`App\` → `app/`) |
| `package.json` | JS প্যাকেজ + `dev`/`build` স্ক্রিপ্ট |
| `vite.config.js` | কোন ফাইল build হবে, Tailwind প্লাগিন, font |
| `compose.yaml` | Docker: `laravel.test` (PHP 8.5), `mysql`, `redis` |
| `phpunit.xml` | টেস্ট কনফিগ |
| `AGENTS.md` | AI কোডিং অ্যাসিস্ট্যান্টের জন্য নিয়ম |

---

## 5. একটা Request কীভাবে পুরো অ্যাপ পার হয়

ব্রাউজারে `http://localhost/posts` লিখলে:

```
ব্রাউজার
   │  GET /posts
   ▼
public/index.php                  ← সব request-এর একমাত্র প্রবেশদ্বার
   │  (vendor/autoload.php + bootstrap/app.php লোড)
   ▼
bootstrap/app.php                 ← Application তৈরি, routing/middleware সেট
   │
   ▼
Router  ◄── routes/web.php        ← "/posts" কোন ক্লাসের কোন মেথডে যাবে
   │
   ▼
Middleware group "web"
   │  • cookie encrypt/decrypt
   │  • session শুরু
   │  • CSRF verify (POST/PUT/DELETE-এ)
   │  • route model binding (Post $post)
   ▼
PostController@index              ← app/Http/Controllers/PostController.php
   │
   ▼
Eloquent Model: Post              ← app/Models/Post.php
   │  Post::latest()->paginate(10)
   ▼
MySQL container (host: mysql)      ← .env / config/database.php
   │  SELECT * FROM posts ORDER BY created_at DESC LIMIT 10
   ▼
Blade View                        ← resources/views/posts/index.blade.php
   │  (HTML + Tailwind + Vite assets)
   ▼
HTTP Response 200 → ব্রাউজার
```

POST (form submit) হলে একই পথ, শুধু:

```
@csrf টোকেন → middleware verify → FormRequest validate → Controller@store
→ Model::create() → redirect()->route('posts.index')->with('success', ...)
```

আর ভুল হলে:

```
Validation fail → আগের পেজে redirect + error session-এ
Exception      → storage/logs/laravel.log + (APP_DEBUG=true হলে) error page
```

**এই কারণেই ৫টা জিনিস একসাথে মিলতে হয়:**

| প্রশ্ন | উত্তর দেওয়া ফাইল |
|---|---|
| URL কোন কোডে যাবে? | `routes/web.php` |
| কী কাজ হবে? | `app/Http/Controllers/...` |
| ডাটা কোথা থেকে আসবে? | `app/Models/...` |
| টেবিলের গঠন কী? | `database/migrations/...` |
| ইউজার কী দেখবে? | `resources/views/...` |

---

## 6. কে কোথায় থাকে — ম্যাপিং টেবিল

`Post` নামের একটা ফিচার বানালে সব জায়গায় একই নাম ঘুরবে:

| জিনিস | নাম | পাথ |
|---|---|---|
| Model | `Post` | `app/Models/Post.php` |
| টেবিল | `posts` | migration |
| Controller | `PostController` | `app/Http/Controllers/PostController.php` |
| Route নাম | `posts.index`, `posts.store`... | `routes/web.php` |
| View | `posts/index.blade.php` | `resources/views/posts/` |
| Form Request | `StorePostRequest` | `app/Http/Requests/StorePostRequest.php` |
| Factory | `PostFactory` | `database/factories/PostFactory.php` |
| Seeder | `PostSeeder` | `database/seeders/PostSeeder.php` |
| Policy | `PostPolicy` | `app/Policies/PostPolicy.php` |

Laravel এই নামগুলো **নিজে থেকে** মিলিয়ে নেয় (convention over configuration)। তাই নাম ভাঙলে অটো-ম্যাজিক কাজ করে না — এটা নতুনদের সবচেয়ে বড় ফাঁদ।

---

## 7. CRUD: শূন্য থেকে অ্যাডভান্স

আমরা একটা ছোট **Blog Post** বানাবো। ফিল্ড: `title`, `body`, `is_published`।

> CRUD = **C**reate, **R**ead, **U**pdate, **D**elete।

### Step 0 — ডাটাবেজ ঠিক আছে কিনা দেখে নাও

```bash
cd /home/laptop/apps/laravel/first-app
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:show
```

`MySQL 8.4`, `Database: laravel`, `Tables: 9` দেখলে তুমি রেডি।

---

### Step 1 — Model + Migration + Controller + Factory একসাথে বানাও

```bash
./vendor/bin/sail artisan make:model Post --migration --controller --resource --factory
```

সংক্ষেপে: `./vendor/bin/sail artisan make:model Post -mcrf`

তৈরি হবে ৪টা ফাইল:

```
app/Models/Post.php
app/Http/Controllers/PostController.php
database/migrations/2026_11_14_XXXXXX_create_posts_table.php
database/factories/PostFactory.php
```

`--resource` দেওয়ার কারণে controller-এ **৭টা মেথড** (index, create, store, show, edit, update, destroy) আর `Post $post` **route model binding** সহ কোড আসবে।

> **কেন `Post` আর `posts`?** একবচন class → বহুবচন টেবিল। Laravel নিজেই ধরে নেয় `Post` মডেল `posts` টেবিলে যাবে।

---

### Step 2 — Migration লিখো (টেবিলের গঠন)

`database/migrations/xxxx_create_posts_table.php` খুলে `Schema::create` এর ভেতরে ফিল্ড যোগ করো:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();                                   // BIGINT auto increment primary key
            $table->string('title');                        // VARCHAR(255)
            $table->text('body');                           // TEXT
            $table->boolean('is_published')->default(false);
            $table->timestamps();                           // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');                     // rollback
    }
};
```

চালাও:

```bash
./vendor/bin/sail artisan migrate
```

দেখো:

```bash
./vendor/bin/sail artisan db:table posts
```

**দরকারি column টাইপ:**

| কোড | MySQL |
|---|---|
| `$table->id()` | `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY` |
| `$table->string('title', 100)` | `VARCHAR(100)` |
| `$table->text('body')` | `TEXT` |
| `$table->integer('views')` | `INT` |
| `$table->boolean('active')` | `TINYINT(1)` |
| `$table->decimal('price', 8, 2)` | `DECIMAL(8,2)` |
| `$table->enum('status', ['draft','live'])` | `ENUM` |
| `$table->timestamp('published_at')->nullable()` | nullable datetime |
| `$table->foreignId('user_id')->constrained()` | FK → `users.id` |
| `$table->softDeletes()` | `deleted_at` (soft delete) |
| `$table->timestamps()` | `created_at`, `updated_at` |

> **নিয়ম:** একবার `migrate` করার পর পুরনো migration এডিট করলে সেটা DB-তে যায় না। তখন নতুন migration বানাও:
> `./vendor/bin/sail artisan make:migration add_slug_to_posts_table --table=posts`

---

### Step 3 — Model সাজাও

`app/Models/Post.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'body', 'is_published'])]
class Post extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }
}
```

**`Fillable` কী?** কোন কোন column-এ `create()`/`update()` দিয়ে ডাটা লেখা যাবে।
না দিলে `MassAssignmentException` এরর আসবে — এটা নিরাপত্তার জন্য (ইউজার যেন `is_admin` টাইপ ফিল্ড চুরি করে পাঠাতে না পারে)।

> Laravel 13-এ `#[Fillable([...])]` attribute-ই নতুন স্টাইল (তোমার `User.php`-তেও এটা আছে)।
> পুরনো টিউটোরিয়ালের `protected $fillable = ['title', ...];`-ও এখনো কাজ করে — দুটোই ঠিক।

**`casts()` কী?** DB থেকে আসা string-কে সঠিক টাইপে বদলায়। `is_published` DB-তে `0/1`, কিন্তু PHP-তে `true/false`।

টেস্ট করে দেখো:

```bash
./vendor/bin/sail artisan tinker
```

```php
>>> $post = App\Models\Post::create(['title' => 'প্রথম পোস্ট', 'body' => 'হ্যালো লারাভেল']);
>>> $post->id;
>>> App\Models\Post::count();
>>> $post->is_published;   // false
>>> exit
```

---

### Step 4 — Route যোগ করো

`routes/web.php`:

```php
<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// এক লাইনে ৭টা route + নাম + model binding
Route::resource('posts', PostController::class);
```

চেক করো:

```bash
./vendor/bin/sail artisan route:list --name=posts
```

যা পাবে:

| Method | URL | Action | Route name |
|---|---|---|---|
| GET | `/posts` | index | `posts.index` |
| GET | `/posts/create` | create | `posts.create` |
| POST | `/posts` | store | `posts.store` |
| GET | `/posts/{post}` | show | `posts.show` |
| GET | `/posts/{post}/edit` | edit | `posts.edit` |
| PUT/PATCH | `/posts/{post}` | update | `posts.update` |
| DELETE | `/posts/{post}` | destroy | `posts.destroy` |

**মনে রাখো:** HTML form শুধু GET আর POST পারে। তাই update/delete-এর জন্য form-এ লুকানো `@method('PUT')` / `@method('DELETE')` দিতে হয়।

---

### Step 5 — Controller-এর ৭টা মেথড

`app/Http/Controllers/PostController.php` পুরোটা এমন করো (এখনই validation inline রাখছি, পরে FormRequest-এ নেবো):

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /posts — সব পোস্টের লিস্ট
    public function index()
    {
        $posts = Post::latest()->paginate(10);

        return view('posts.index', compact('posts'));
    }

    // GET /posts/create — ফর্ম দেখাও
    public function create()
    {
        return view('posts.create');
    }

    // POST /posts — নতুন পোস্ট সেভ করো
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $post = Post::create($validated);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'পোস্ট তৈরি হয়েছে।');
    }

    // GET /posts/{post} — একটা পোস্ট দেখাও
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // GET /posts/{post}/edit — এডিট ফর্ম
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // PUT /posts/{post} — আপডেট
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $post->update($validated);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'পোস্ট আপডেট হয়েছে।');
    }

    // DELETE /posts/{post} — ডিলিট
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'পোস্ট মুছে ফেলা হয়েছে।');
    }
}
```

**Route Model Binding:** `show(Post $post)` — Laravel নিজেই URL-এর `{post}` দেখে `Post::findOrFail($id)` করে দেয়। না পাওয়া গেলে অটো 404। হাতে `findOrFail` লিখতে হয় না।

**`compact('posts')`** মানে `['posts' => $posts]` — view-তে `$posts` পাওয়া যাবে।

---

### Step 6 — View (Blade) বানাও

চারটা ফাইল + একটা লেআউট + একটা shared form দরকার:

```
resources/views/
├── layouts/
│   └── app.blade.php
└── posts/
    ├── index.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    ├── show.blade.php
    └── _form.blade.php
```

#### `resources/views/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Blog')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    <nav class="bg-white border-b">
        <div class="mx-auto max-w-3xl p-4 flex gap-4">
            <a href="{{ route('posts.index') }}" class="font-semibold">Blog</a>
            <a href="{{ route('posts.create') }}" class="text-blue-600">নতুন পোস্ট</a>
        </div>
    </nav>

    <main class="mx-auto max-w-3xl p-6">
        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 text-green-800 p-3">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
```

#### `resources/views/posts/index.blade.php`

```blade
@extends('layouts.app')

@section('title', 'সব পোস্ট')

@section('content')
    <h1 class="text-2xl font-bold mb-4">সব পোস্ট</h1>

    @forelse ($posts as $post)
        <article class="border-b py-4">
            <h2 class="text-lg font-semibold">
                <a href="{{ route('posts.show', $post) }}" class="hover:underline">
                    {{ $post->title }}
                </a>
            </h2>
            <p class="text-sm text-gray-500">
                {{ $post->created_at->diffForHumans() }}
                @if ($post->is_published)
                    · <span class="text-green-600">প্রকাশিত</span>
                @endif
            </p>
            <p class="mt-2">{{ Str::limit($post->body, 120) }}</p>
        </article>
    @empty
        <p class="text-gray-500">এখনো কোনো পোস্ট নেই।</p>
    @endforelse

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
@endsection
```

#### `resources/views/posts/_form.blade.php`

```blade
{{-- $post null হলে create, নাহলে edit --}}

<div class="mb-4">
    <label for="title" class="block font-medium mb-1">শিরোনাম</label>
    <input type="text" id="title" name="title"
           value="{{ old('title', $post?->title) }}"
           class="w-full rounded border border-gray-300 p-2">
    @error('title')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="body" class="block font-medium mb-1">বিস্তারিত</label>
    <textarea id="body" name="body" rows="6"
              class="w-full rounded border border-gray-300 p-2">{{ old('body', $post?->body) }}</textarea>
    @error('body')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<label class="flex items-center gap-2">
    <input type="checkbox" name="is_published" value="1"
           @checked(old('is_published', $post?->is_published))>
    <span>প্রকাশিত</span>
</label>
```

#### `resources/views/posts/create.blade.php`

```blade
@extends('layouts.app')

@section('title', 'নতুন পোস্ট')

@section('content')
    <h1 class="text-2xl font-bold mb-4">নতুন পোস্ট</h1>

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        @include('posts._form', ['post' => null])

        <button type="submit" class="mt-4 rounded bg-blue-600 px-4 py-2 text-white">
            সেভ করো
        </button>
    </form>
@endsection
```

#### `resources/views/posts/edit.blade.php`

```blade
@extends('layouts.app')

@section('title', 'এডিট: ' . $post->title)

@section('content')
    <h1 class="text-2xl font-bold mb-4">পোস্ট এডিট</h1>

    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')
        @include('posts._form', ['post' => $post])

        <button type="submit" class="mt-4 rounded bg-blue-600 px-4 py-2 text-white">
            আপডেট করো
        </button>
    </form>
@endsection
```

#### `resources/views/posts/show.blade.php`

```blade
@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <h1 class="text-2xl font-bold">{{ $post->title }}</h1>
    <p class="text-sm text-gray-500 mt-1">
        {{ $post->created_at->format('d M Y') }}
    </p>

    <div class="mt-4">
        {!! nl2br(e($post->body)) !!}
    </div>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('posts.edit', $post) }}"
           class="rounded border px-4 py-2">এডিট</a>

        <form action="{{ route('posts.destroy', $post) }}" method="POST"
              onsubmit="return confirm('সত্যিই মুছে ফেলবে?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white">
                ডিলিট
            </button>
        </form>
    </div>
@endsection
```

> `{!! nl2br(e($post->body)) !!}` — আগে `e()` দিয়ে HTML escape করে নিরাপদ করলাম, তারপর `nl2br` দিয়ে লাইনব্রেক। শুধু `{!! $post->body !!}` কখনো করো না, XSS হবে।

---

### Step 7 — চালাও

```bash
./vendor/bin/sail npm run dev      # আলাদা টার্মিনালে (CSS/JS)
```

তারপর ব্রাউজারে:

- http://localhost/posts → লিস্ট
- http://localhost/posts/create → নতুন
- তৈরি করে দেখো, এডিট করো, ডিলিট করো

কিছু কাজ না করলে `storage/logs/laravel.log` দেখো, আর route ঠিক আছে কিনা `sail artisan route:list` দিয়ে।

> **এতক্ষণে তোমার প্রথম CRUD শেষ।** এখন যেগুলো আসে সেগুলো "professional" লেভেলের জন্য।

---

### Step 8 — Validation-কে FormRequest-এ নাও (recommended)

Controller-এ validation inline রাখলে controller মোটা হয় আর নিয়ম দুইবার লিখতে হয়। FormRequest আলাদা ক্লাস:

```bash
./vendor/bin/sail artisan make:request StorePostRequest
./vendor/bin/sail artisan make:request UpdatePostRequest
```

`app/Http/Requests/StorePostRequest.php`:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;   // সবাই পারবে; পরে policy দিয়ে বদলাবে
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'শিরোনাম দিতে হবে।',
            'body.min' => 'বিস্তারিত অন্তত :min অক্ষরের হতে হবে।',
        ];
    }
}
```

`UpdatePostRequest` একই, শুধু slug/unique থাকলে নিজের id ignore করবে:

```php
'title' => ['required', 'string', 'max:255'],
```

তারপর controller:

```php
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

public function store(StorePostRequest $request)
{
    $validated = $request->validated();
    $validated['is_published'] = $request->boolean('is_published');

    $post = Post::create($validated);

    return redirect()->route('posts.show', $post)->with('success', 'পোস্ট তৈরি হয়েছে।');
}

public function update(UpdatePostRequest $request, Post $post)
{
    $validated = $request->validated();
    $validated['is_published'] = $request->boolean('is_published');

    $post->update($validated);

    return redirect()->route('posts.show', $post)->with('success', 'পোস্ট আপডেট হয়েছে।');
}
```

**এখন কী হয়:**

1. request আসে → FormRequest `authorize()` চেক করে
2. `rules()` অনুযায়ী validate করে
3. ফেল করলে **নিজে থেকেই** আগের পেজে redirect করে, error session-এ পাঠায়
4. পাস করলে `$request->validated()`-এ শুধু validate হওয়া ফিল্ড থাকে

`$request->validate([...])` আর FormRequest — দুটোই ঠিক, কিন্তু FormRequest বড় প্রজেক্টে অনেক পরিষ্কার।

**দরকারি rule:**

```php
'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
'age'      => ['nullable', 'integer', 'between:13,120'],
'price'    => ['required', 'numeric', 'min:0'],
'image'    => ['nullable', 'image', 'max:2048'],          // KB
'status'   => ['required', Rule::in(['draft', 'live'])],
'password' => ['required', 'confirmed', Password::min(8)],
'user_id'  => ['required', 'exists:users,id'],
'slug'     => ['required', Rule::unique('posts', 'slug')->ignore($post?->id)],
```

---

### Step 9 — Factory + Seeder (ডেমি ডাটা)

`database/factories/PostFactory.php`:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'body' => fake()->paragraphs(3, true),
            'is_published' => fake()->boolean(70),
        ];
    }
}
```

`database/seeders/DatabaseSeeder.php`-এ যোগ করো:

```php
public function run(): void
{
    User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    \App\Models\Post::factory()->count(20)->create();   // ← নতুন লাইন
}
```

চালাও:

```bash
./vendor/bin/sail artisan db:seed
# অথবা সব টেবিল মুছে আবার সব migration + seed:
./vendor/bin/sail artisan migrate:fresh --seed
```

> `migrate:fresh` **সব ডাটা মুছে ফেলে**। শুধু ডেভেলপমেন্টে চালাবে।

`tinker`-এ দ্রুত টেস্ট:

```php
>>> App\Models\Post::factory()->count(5)->create();
>>> App\Models\Post::where('is_published', true)->count();
```

---

### Step 10 — Auth + Ownership (কে কার পোস্ট এডিট করতে পারবে)

এই প্রজেক্টে login/register UI **নেই**। শুরুতে দুটো পথ:

**পথ A — Starter kit / Breeze (সহজ):**

```bash
./vendor/bin/sail composer require laravel/breeze --dev
./vendor/bin/sail artisan breeze:install blade
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
./vendor/bin/sail artisan migrate
```

(যদি Laravel 13-এর সাথে compatible ভার্সন না পাও, `laravel new` দিয়ে নতুন প্রজেক্টে starter kit নিয়ে কোড কপি করা সহজ।)

**পথ B — শুধু middleware দিয়ে রক্ষা করা (UI পরে):**

```php
// routes/web.php
// পাবলিক: লিস্ট আর একক পোস্ট দেখা
Route::resource('posts', PostController::class)->only(['index', 'show']);

// বাকি সব কাজে লগইন লাগবে
Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});
```

এতে `index`/`show` সবাই দেখতে পাবে, কিন্তু create/store/edit/update/destroy-এ login লাগবে।

**পোস্টকে ইউজারের সাথে বাঁধো** — নতুন migration:

```bash
./vendor/bin/sail artisan make:migration add_user_id_to_posts_table --table=posts
```

```php
public function up(): void
{
    Schema::table('posts', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->after('id')
              ->constrained()->cascadeOnDelete();
    });
}

public function down(): void
{
    Schema::table('posts', function (Blueprint $table) {
        $table->dropConstrainedForeignId('user_id');
    });
}
```

```bash
./vendor/bin/sail artisan migrate
```

মডেলে relation:

```php
// app/Models/Post.php
public function user()
{
    return $this->belongsTo(User::class);
}

// app/Models/User.php
public function posts()
{
    return $this->hasMany(Post::class);
}
```

Controller-এ ownership:

```php
public function store(StorePostRequest $request)
{
    $validated = $request->validated();
    $validated['is_published'] = $request->boolean('is_published');

    $post = $request->user()->posts()->create($validated);

    return redirect()->route('posts.show', $post)->with('success', 'পোস্ট তৈরি হয়েছে।');
}
```

**Policy (কে এডিট/ডিলিট করতে পারবে):**

```bash
./vendor/bin/sail artisan make:policy PostPolicy --model=Post
```

`app/Policies/PostPolicy.php`:

```php
public function update(User $user, Post $post): bool
{
    return $user->id === $post->user_id;
}

public function delete(User $user, Post $post): bool
{
    return $user->id === $post->user_id;
}
```

Controller-এ (base Controller খালি, তাই `Gate::authorize`):

```php
use Illuminate\Support\Facades\Gate;

public function update(UpdatePostRequest $request, Post $post)
{
    Gate::authorize('update', $post);
    // ...
}

public function destroy(Post $post)
{
    Gate::authorize('delete', $post);
    $post->delete();
    // ...
}
```

Blade-এ বাটন লুকাতে:

```blade
@can('update', $post)
    <a href="{{ route('posts.edit', $post) }}">এডিট</a>
@endcan
```

> `Gate::authorize` fail করলে অটো **403 Forbidden**।

---

### Step 11 — Feature Test (ভবিষ্যতের নিজেকে বাঁচাও)

```bash
./vendor/bin/sail artisan make:test PostCrudTest
```

`tests/Feature/PostCrudTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;   // প্রতিবার টেস্টের আগে DB রিসেট

    public function test_guest_can_see_posts_list(): void
    {
        Post::factory()->create(['title' => 'Hello Laravel']);

        $this->get(route('posts.index'))
            ->assertOk()
            ->assertSee('Hello Laravel');
    }

    public function test_user_can_create_a_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'নতুন পোস্ট',
            'body' => 'এটা টেস্টের জন্য লেখা বডি টেক্সট।',
            'is_published' => '1',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'title' => 'নতুন পোস্ট',
        ]);
    }

    public function test_title_is_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('posts.store'), ['title' => '', 'body' => 'short'])
            ->assertSessionHasErrors(['title']);
    }
}
```

```bash
./vendor/bin/sail artisan test
```

---

### Step 12 — অ্যাডভান্স টপিক (ছোট ছোট উদাহরণ)

#### 12.1 Search + Filter + Pagination

```php
public function index(Request $request)
{
    $posts = Post::query()
        ->when($request->filled('q'), function ($query) use ($request) {
            $query->where('title', 'like', '%' . $request->input('q') . '%');
        })
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('is_published', $request->input('status') === 'published');
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();   // পেজ বদলালেও ?q=... থাকে

    return view('posts.index', compact('posts'));
}
```

Blade-এ সার্চ ফর্ম:

```blade
<form method="GET" action="{{ route('posts.index') }}" class="mb-4 flex gap-2">
    <input name="q" value="{{ request('q') }}" placeholder="খুঁজো...">
    <button>সার্চ</button>
</form>
```

#### 12.2 N+1 সমস্যা ও Eager Loading

```php
// খারাপ: প্রতি পোস্টে আলাদা query (N+1)
$posts = Post::all();
foreach ($posts as $post) { echo $post->user->name; }

// ভালো: ২টা query
$posts = Post::with('user')->latest()->paginate(10);

// relation থেকে count
$posts = Post::withCount('comments')->get();
```

ডেভেলপমেন্টে লুকানো N+1 ধরতে `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Database\Eloquent\Model;

public function boot(): void
{
    Model::preventLazyLoading(! app()->isProduction());
}
```

#### 12.3 Soft Delete

Migration: `$table->softDeletes();`
Model:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
}
```

`$post->delete()` এখন ডাটা মুছবে না, `deleted_at` সেট করবে। লিস্টে অটো বাদ যাবে। ফিরাতে: `Post::withTrashed()->...`, `$post->restore()`, স্থায়ীভাবে: `$post->forceDelete()`।

#### 12.4 ফাইল/ইমেজ আপলোড

```bash
./vendor/bin/sail artisan storage:link
```

```php
'cover' => ['nullable', 'image', 'max:2048'],
```

> আগে migration-এ কলাম যোগ করো: `$table->string('cover')->nullable();` আর form-এ `enctype="multipart/form-data"` দাও।

```php
$path = $request->file('cover')->store('covers', 'public');
// DB-তে $path সেভ করো

// Blade-এ দেখাও
<img src="{{ Storage::url($post->cover) }}" alt="">
```

#### 12.5 Transaction (একাধিক টেবিল একসাথে)

```php
use Illuminate\Support\Facades\DB;

DB::transaction(function () use ($validated) {
    $post = Post::create($validated);
    $post->tags()->attach($validated['tags']);
});
// কোনোটা fail করলে সব rollback
```

#### 12.6 Event + Observer (side effect আলাদা রাখা)

```bash
./vendor/bin/sail artisan make:observer PostObserver --model=Post
```

`created()`-এ ইমেইল/নোটিফিকেশন/লগ পাঠাতে পারো — controller পরিষ্কার থাকে।

#### 12.7 API (mobile/JS client-এর জন্য)

```bash
./vendor/bin/sail artisan install:api
./vendor/bin/sail artisan make:controller Api/PostController --api --model=Post
```

`routes/api.php`:

```php
use App\Http\Controllers\Api\PostController;

Route::apiResource('posts', PostController::class);
```

API-তে JSON response:

```php
return response()->json($posts);
// অথবা Laravel Resource:
// sail artisan make:resource PostResource
// return PostResource::collection($posts);
```

#### 12.8 Cache

```php
$posts = Cache::remember('posts.index', 60, fn () => Post::latest()->get());
```

মডেল বদলালে cache ভাঙতে observer-এ `Cache::forget('posts.index')`।

#### 12.9 Queue / Job (ধীর কাজ background-এ)

```bash
./vendor/bin/sail artisan make:job SendPostPublishedMail
```

```php
SendPostPublishedMail::dispatch($post);
```

`.env`: `QUEUE_CONNECTION=database` (আছে), চালাও: `sail artisan queue:work`।

#### 12.10 Enum + Cast

```php
enum PostStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
}
```

Model-এ: `'status' => PostStatus::class`। তারপর `$post->status === PostStatus::Published`।

#### 12.11 Scope

```php
// Model
public function scopePublished($query)
{
    $query->where('is_published', true);
}

// ব্যবহার
Post::published()->latest()->get();
```

#### 12.12 Accessor

```php
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

protected function excerpt(): Attribute
{
    return Attribute::make(
        get: fn () => Str::limit($this->body, 100),
    );
}

// $post->excerpt
```

---

### Step 13 — Production-এ দেওয়ার আগে

```bash
# .env
APP_ENV=production
APP_DEBUG=false          # ★ অবশ্যই false, নাহলে সিক্রেট ফাঁস হবে
APP_URL=https://your-domain.com

# কমান্ড
./vendor/bin/sail composer install --no-dev --optimize-autoloader
./vendor/bin/sail artisan migrate --force
./vendor/bin/sail npm ci && ./vendor/bin/sail npm run build
./vendor/bin/sail artisan optimize     # config + route + view cache
```

`optimize` করার পর `.env` বদলালে `sail artisan optimize:clear` চালাতে হবে, নাহলে পুরনো মান থাকবে।

---

## 8. Laravel-এর Naming Convention

| জিনিস | নিয়ম | উদাহরণ |
|---|---|---|
| Model | একবচন, PascalCase | `Post`, `BlogComment` |
| টেবিল | বহুবচন, snake_case | `posts`, `blog_comments` |
| Controller | Model + `Controller` | `PostController` |
| Route name | বহুবচন, dot | `posts.index` |
| Foreign key | একবচন + `_id` | `user_id`, `post_id` |
| Pivot টেবিল | দুই নাম alphabetical | `post_tag` |
| View | ফোল্ডার/model নাম | `posts/index.blade.php` |
| Migration | তারিখ_prefix + কাজ | `2026_11_14_000000_create_posts_table.php` |
| Method | camelCase | `store`, `update` |

**কেন এত নিয়ম?** Laravel convention ধরে নেয় — নাম ঠিক থাকলে কোড কম লিখতে হয় (`Post` → `posts`, `post_id`, `posts.index` সব অটো)। নাম ভাঙলে সব হাতে কনফিগার করতে হয়।

---

## 9. দরকারি Artisan কমান্ড চিটশিট

> নিচে `sail` লেখা মানে `./vendor/bin/sail` (alias সেট করা আছে ধরে নিয়ে)।

```bash
# --- তৈরি করা ---
sail artisan make:model Post -mcrf          # model + migration + resource controller + factory
sail artisan make:model Post -a             # সবকিছু (seeder, policy, form request সহ)
sail artisan make:controller PostController --resource --model=Post
sail artisan make:migration create_posts_table --create=posts
sail artisan make:migration add_slug_to_posts_table --table=posts
sail artisan make:seeder PostSeeder
sail artisan make:factory PostFactory --model=Post
sail artisan make:request StorePostRequest
sail artisan make:policy PostPolicy --model=Post
sail artisan make:observer PostObserver --model=Post
sail artisan make:middleware EnsureIsAdmin
sail artisan make:test PostCrudTest
sail artisan make:component Alert

# --- ডাটাবেজ ---
sail artisan migrate
sail artisan migrate:rollback
sail artisan migrate:fresh --seed          # ⚠️ সব ডাটা মুছে যায়
sail artisan db:seed
sail artisan db:show
sail artisan db:table posts
sail artisan db                              # mysql CLI

# --- ডিবাগ ---
sail artisan route:list
sail artisan route:list --name=posts
sail artisan tinker
sail artisan pail                            # লাইভ log
sail artisan about
sail artisan env

# --- cache পরিষ্কার ---
sail artisan optimize:clear                  # config+route+view+cache একসাথে
sail artisan config:clear
sail artisan route:clear
sail artisan view:clear
sail artisan cache:clear

# --- টেস্ট / কোড স্টাইল ---
sail artisan test
sail test --filter=PostCrudTest
sail pint                                    # কোড ফরম্যাট (Laravel Pint)

# --- Sail ---
sail up -d
sail down
sail npm run dev
sail npm run build
sail composer require vendor/package
sail artisan storage:link
```

---

## 10. সাধারণ এরর ও সমাধান

| এরর / সমস্যা | কারণ | সমাধান |
|---|---|---|
| **419 Page Expired** | form-এ `@csrf` নেই বা session expire | `@csrf` যোগ করো, `SESSION_DRIVER` চেক করো |
| **405 Method Not Allowed** | update/delete-এ `@method('PUT')` ভুলে গেছ | `@method('PUT')` / `@method('DELETE')` দাও |
| **404 Not Found** (route আছে) | route cache পুরনো | `sail artisan route:clear` |
| **SQLSTATE[HY000] [2002] Connection refused** | Docker/MySQL চলছে না | `sail up -d`, তারপর `docker ps` |
| **SQLSTATE: Unknown database** | DB নাম মিলছে না | `.env`-এর `DB_DATABASE` আর `migrate` চেক করো |
| **MassAssignmentException** | Model-এ `Fillable` নেই | `#[Fillable([...])]` বা `$fillable` দাও |
| **Class "App\Models\Post" not found** | autoload map পুরনো / নাম ভুল | `sail composer dump-autoload` |
| **Target class [XController] does not exist** | namespace/ফাইল নাম ভুল | `use` statement আর ক্লাস নাম মিলাও |
| **Vite manifest not found** | build/dev চলছে না | `sail npm run dev` বা `sail npm run build` |
| **CSS বদলাচ্ছে না** | Vite বন্ধ | `sail npm run dev` চালু রাখো |
| **Blank page / 500** | লগে আসল কারণ | `storage/logs/laravel.log` বা `sail artisan pail` |
| **Permission denied (storage)** | ফোল্ডার permission | `chmod -R ug+rwx storage bootstrap/cache` |
| **No application encryption key** | `APP_KEY` খালি | `sail artisan key:generate` |
| **Table already exists** | migration আধা-চলা | `sail artisan migrate:fresh` (⚠️ ডাটা যায়) |
| **`php`/`composer` command not found** | হোস্টে PHP নেই | সব কমান্ড `sail` দিয়ে চালাও |
| **Route [posts.index] not defined** | route নাম ভুল / resource দেওয়া হয়নি | `route:list` দেখো |
| **Class "Str" not found (Blade)** | alias নেই (কদাচিৎ) | `\Illuminate\Support\Str::limit(...)` ব্যবহার করো |

**ডিবাগের সঠিক ক্রম:**

1. `storage/logs/laravel.log` দেখো (আসল এরর সেখানে)
2. `sail artisan route:list` — route আছে?
3. URL ঠিক? method ঠিক?
4. `sail artisan optimize:clear`
5. `sail composer dump-autoload`
6. ব্রাউজার hard refresh (Ctrl+Shift+R)

---

## 11. এরপর কী শিখবে

**এই ক্রমে এগোও:**

1. **Eloquent relationships** — `hasMany`, `belongsTo`, `belongsToMany` (tag)
2. **Blade components** — `x-alert`, layout component
3. **Authentication** — Breeze/starter kit, `auth` middleware, policy
4. **File upload + storage**
5. **API + Sanctum** — mobile/React frontend-এর জন্য
6. **Testing** — Feature test সব CRUD-এর জন্য
7. **Queue, Event, Notification** — ধীর কাজ background-এ
8. **Deployment** — VPS/Forge, `optimize`, HTTPS

**ভালো রিসোর্স:**

- Official docs: https://laravel.com/docs
- Laravel Bootcamp (হাতে-কলমে): https://bootcamp.laravel.com
- Laracasts (ভিডিও): https://laracasts.com
- Laravel Daily (ছোট টিপস): https://laraveldaily.com

**তোমার প্রজেক্টে এখনকার অবস্থা (এই গাইড শেষ করার পর):**

```
app/Models/Post.php                         ← নতুন
app/Http/Controllers/PostController.php     ← নতুন
app/Http/Requests/StorePostRequest.php      ← Step 8
database/migrations/..._create_posts_table.php
database/factories/PostFactory.php
database/seeders/DatabaseSeeder.php         ← এডিট
resources/views/layouts/app.blade.php       ← নতুন
resources/views/posts/*.blade.php           ← নতুন (৫টা)
routes/web.php                              ← Route::resource
tests/Feature/PostCrudTest.php              ← Step 11
```

---

## শেষ কথা

Laravel শেখার সবচেয়ে বড় "আনলক" হলো এই ৫টা লাইনের সম্পর্ক বুঝে ফেলা:

```
URL  →  routes/web.php  →  Controller  →  Model  →  Database
                                  ↓
                            Blade View  →  Browser
```

যেকোনো নতুন ফিচার বানানোর সময় নিজেকে ৫টা প্রশ্ন করো:

1. **URL কী হবে?** (route)
2. **কোন controller মেথড?** (action)
3. **ডাটা কোন model/টেবিলে?** (Eloquent)
4. **ইউজার কী দেখবে?** (Blade)
5. **ভুল ইনপুট হলে কী হবে?** (validation)

এই ৫টার উত্তর পরিষ্কার হলে Laravel আর কঠিন লাগবে না। শুভকামনা! 🚀
