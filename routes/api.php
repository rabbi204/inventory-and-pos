<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PosController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SalaryController;
use App\Http\Controllers\Api\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Category;

Route::group(['middleware' => 'api'], function($router) {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/profile', [AuthController::class, 'profile']);
});

Route::apiResource('/employee',EmployeeController::class);
Route::apiResource('/supplier', SupplierController::class);
Route::apiResource('/category', CategoryController::class);
Route::apiResource('/product',ProductController::class);
Route::apiResource('/expense',ExpenseController::class);

//for salary
Route::post('/salary/paid/{id}', [SalaryController::class, 'paid']);
Route::get('/salary', [SalaryController::class, 'allSalary']);
Route::get('/salary/view/{id}', [SalaryController::class, 'viewSalary']);
Route::get('/edit/salary/{id}', [SalaryController::class, 'editSalary']);
Route::post('/salary/update/{id}', [SalaryController::class, 'updateSalary']);

// stock route
Route::post('/stock/update/{id}', [ProductController::class, 'stockUpdate']);

// customer api route
Route::apiResource('/customer',CustomerController::class);

Route::get('/getting/product/{id}', [PosController::class, 'getProduct']);

// Add to Cart Route
Route::get('/addToCart/{id}', [CartController::class, 'addToCart']);
Route::get('/cart/product/', [CartController::class, 'cartProduct']);
Route::get('/remove/cart/{id}', [CartController::class, 'cartRemove']);
Route::get('/increment/{id}', [CartController::class, 'increment']);
Route::get('/decrement/{id}', [CartController::class, 'decrement']);

// vat
Route::get('/vats', [CartController::class, 'vats']);

//order done
Route::post('/orderdone', [PosController::class, 'orderDone']);

// order details route
Route::get('/orders', [OrderController::class, 'todayOrder']);
Route::get('/order/details/{id}', [OrderController::class, 'orderDetails']);
Route::get('/order/orderdetails/{id}', [OrderController::class, 'orderDetailsAll']);
Route::post('/serach/order', [PosController::class, 'searchOrderDate']);

// admin dashboard route
Route::get('/today/sell', [PosController::class, 'todaySell']);
Route::get('/today/income', [PosController::class, 'todayIncome']);
Route::get('/today/due', [PosController::class, 'todayDue']);
Route::get('/today/expense', [PosController::class, 'todayExpense']);
Route::get('/today/stockout', [PosController::class, 'todayStockOut']);
