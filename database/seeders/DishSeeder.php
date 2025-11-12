<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dish;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dishes = [
            'Spaghetti Bolognese',
            'Chicken Biryani',
            'Beef Steak',
            'Grilled Fish',
            'Vegetable Fried Rice',
            'Mutton Karahi',
            'Chicken Alfredo Pasta',
            'Paneer Handi',
            'Tandoori Chicken',
            'Caesar Salad',
        ];

        foreach ($dishes as $dishName) {
            Dish::create(['name' => $dishName]);
        }
    }
}
