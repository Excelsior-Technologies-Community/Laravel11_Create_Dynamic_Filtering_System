

Laravel 11 – Dynamic Filtering System (Search + Price Sorting)

![Laravel](https://img.shields.io/badge/Laravel-11-orange)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![MySQL](https://img.shields.io/badge/Database-MySQL-yellow)

This documentation explains the **Dynamic Product Filtering System** in Laravel 11, including:

- ✔ Live search  
- ✔ Price sorting (Low → High & High → Low)  
- ✔ Pagination  
- ✔ Image preview  
- ✔ Clean admin UI  
- ✔ Full controller + blade explanation  

This README is designed for **GitHub**, with full styling and clean structure.  
Source content: Dynamic Filtering System document fileciteturn2file0

---

 Features Included

| Feature | Description |
|--------|-------------|
| **Live Search** | Search by name, size, color, category, details, price |
| **Price Sorting** | Ascending & descending sort using dropdown |
| **Pagination** | 3 items per page |
| **Image Preview** | Displays uploaded product image |
| **AJAX Ready** | Controller supports AJAX-based reloading |
| **Clean UI** | Bootstrap-based admin layout |

---

 Step 1 – Install Laravel 11

```bash
composer create-project laravel/laravel example-app
```

---

 Step 2 – Configure Database (.env)

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=root
```

---

 Step 3 – Product Migration

Migration includes:

- name  
- details  
- price  
- size  
- color  
- category  
- image  

Run migration:

```bash
php artisan migrate
```

---
 Step 4 – Resource Route

```php
Route::resource('products', ProductController::class);
```

---
 Step 5 – Dynamic Filtering Logic (Controller)

 Full index() Method (Search + Sorting + Pagination)
 SEARCH LOGIC  
- If keyword is numeric → price exact match  
- Otherwise → text search in multiple columns  

 SORT LOGIC  
Supports:
- price-asc
- price-desc

 PAGINATION  
Shows 3 products per page.

---

 Step 6 – Blade UI (Products List)

 Search + Sort Panel

```html
<input type="text" id="search" class="form-control" placeholder="Search products...">

<select id="sort" class="form-select">
    <option value="">Default Sorting</option>
    <option value="price-asc">Price: Low → High</option>
    <option value="price-desc">Price: High → Low</option>
</select>
```

---

 AJAX Script (Dynamic Filtering)

```javascript
$('#search').on('keyup', function(){
    fetch_data(1, $('#search').val(), $('#sort').val());
});

$('#sort').on('change', function(){
    fetch_data(1, $('#search').val(), $(this).val());
});
```

---
Product Table Formatting

Includes:

- Name  
- Details (shortened)  
- Image preview  
- Category  
- Price formatting  
- Edit/Delete buttons  

---

 Create & Edit Pages

Both forms include:

- Name  
- Details  
- Size  
- Color  
- Category  
- Price  
- Image upload with preview  

---

 Layout Requirements

Admin layout uses:

- Bootstrap 5  
- Navigation bar  
- Container layout  

Located in:

```
resources/views/layouts/admin.blade.php
```

---
 How to Run Project

```bash
php artisan serve
```

Open:

```
http://localhost:8000/products
```



<img width="676" height="212" alt="image" src="https://github.com/user-attachments/assets/75d91b74-8b51-4ea6-b766-ff5465798d87" />

