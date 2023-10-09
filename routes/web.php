<?php

use App\Models\governorates;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
$governorates = [
    'Cairo' => 'القاهرة',
    'Giza' => 'الجيزة',
    'Alexandria' => 'الإسكندرية',
    'Dakahlia' => 'الدقهلية',
    'Red Sea' => 'البحر الأحمر',
    'Sharkia' => 'الشرقية',
    'Monufia' => 'المنوفية',
    'Gharbia' => 'الغربية',
    'Fayoum' => 'الفيوم',
    'Minya' => 'المنيا',
    'Asyut' => 'أسيوط',
    'Sohag' => 'سوهاج',
    'Qena' => 'قنا',
    'Aswan' => 'أسوان',
    'New Valley' => 'الوادي الجديد',
    'North Sinai' => 'شمال سيناء',
    'South Sinai' => 'جنوب سيناء',
    'Beheira' => 'البحيرة',
    'Ismailia' => 'الإسماعيلية',
    'Kafr El Sheikh' => 'كفر الشيخ',
    'Matrouh' => 'مطروح',
    'Luxor' => 'الأقصر',
    'Suez Canal' => 'قناة السويس',
];
foreach ($governorates as $s=>$m){
    governorates::create([
            'name' => $m
    ]);
}


