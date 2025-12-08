# 🔍 Laravel 11 – Dynamic Filtering System (Search + Sort + Pagination)  
![Laravel](https://img.shields.io/badge/Laravel-11-orange)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![MySQL](https://img.shields.io/badge/Database-MySQL-yellow)

This documentation explains how to build a **Dynamic Filtering System** inside a **Product CRUD** using Laravel 11.  
It includes **live search, price sorting, pagination, AJAX filtering, CRUD, image upload, and customer product page**.

---

# ⭐ Overview  
This system provides:

- Dynamic search  
- Price sorting (Low → High, High → Low)  
- AJAX filter updates  
- Pagination  
- Product CRUD  
- Admin panel layout  
- Customer product display  
- Breeze authentication  

---

# 📦 Folder Structure  
```
project/
│── app/
│   ├── Models/Product.php
│   ├── Http/Controllers/ProductController.php
│   └── Http/Controllers/CustomerProductsController.php
│
│── resources/views/products/
│── resources/views/layouts/
│── resources/views/customer/
│
│── database/migrations/
│── public/images/
│── routes/web.php
│── README.md
```

---

# 🧱 Step 1 — Install Laravel 11  
```
composer create-project laravel/laravel example-app
```

---

# 🛠 Step 2 — Configure Database  
```
DB_CONNECTION=mysql
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=root
```

---

# 🧱 Step 3 — Create Products Table Migration  
Columns include:  
- name  
- details  
- price  
- size  
- color  
- category  
- image  

Run migration:  
```
php artisan migrate
```

---

# 🧠 Step 4 — Add Resource Route  
```php
Route::resource('products', ProductController::class);
```

---

# 🧠 Step 5 — Product Model  
```php
protected $fillable = [
    'name','details','price','size','color','category','image'
];
```

---

# 🧠 Step 6 — ProductController (Dynamic Filters)

### ✔ Live Search  
Searches across:  
- name  
- category  
- size  
- color  
- details  
- price  

### ✔ Price Sorting  
- Low → High (`price-asc`)  
- High → Low (`price-desc`)  

### ✔ Pagination  
```
$products = $query->paginate(3);
```

### ✔ AJAX Filter Loading  
Used to update results without reloading page.

---

# 🎨 Step 7 — Product Listing UI (index.blade.php)

Includes:

✔ Search Input  
✔ Sorting Dropdown  
✔ "Apply Filters" Button  
✔ Pagination  
✔ Image preview  
✔ Edit / Delete Actions  

---

# 🎨 Step 8 — Create & Edit Pages  
Forms include:

- Text inputs  
- Textarea  
- Image upload  
- Size / category / price fields  
- Preview old image on edit  

---

# 🧩 Step 9 — AJAX Script for Live Filtering  
Handles:

- Search typing event  
- Sort dropdown change  
- Filter button click  
- Pagination  

Uses jQuery to dynamically update the products table.

---

# 🎨 Step 10 — Customer Product Page  

Public-facing customer interface showing:

- Product card  
- Image  
- Details  
- Category  
- Price  

Route:  
```php
Route::get('/customer/products', [CustomerProductsController::class, 'index']);
```

---

# 🎨 Step 11 — Admin & Customer Layout Files  
Two layouts included:

### ✔ layouts.admin.blade.php  
Bootstrap-based admin panel UI

### ✔ layouts.customer.blade.php  
Simple card-based customer view

---

# 🔐 Step 12 — Laravel Breeze Authentication  

Install:  
```
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev
php artisan migrate
```

Protect routes:  
```php
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});
```

Login redirect:  
```
public const HOME = '/products';
```

---

# ▶ Run Application  
```
php artisan serve
```

Visit:

Admin side:  
```
http://localhost:8000/products

<img width="676" height="212" alt="image" src="https://github.com/user-attachments/assets/75d91b74-8b51-4ea6-b766-ff5465798d87" />

