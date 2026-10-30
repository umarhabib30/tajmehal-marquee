<?php

namespace Tests\Feature;

use App\Http\Controllers\admin\BookingController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BookingDateFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->date('event_date');
        });
        Schema::create('customers', function (Blueprint $table) { $table->id(); });
        foreach (['2026-09-30', '2026-10-01', '2026-10-29', '2026-10-31', '2026-11-01', '2025-10-29'] as $date) {
            DB::table('bookings')->insert(['event_date' => $date]);
        }
    }

    private function filteredIds(array $filters): array
    {
        $view = (new BookingController)->index(Request::create('/admin/booking', 'GET', $filters));
        return $view->getData()['bookings']->pluck('id')->all();
    }

    public function test_month_includes_its_boundaries_and_excludes_other_months_and_years(): void
    {
        $this->assertSame([4, 3, 2], $this->filteredIds(['month' => '2026-10']));
    }

    public function test_exact_day_takes_priority_over_month(): void
    {
        $this->assertSame([3], $this->filteredIds(['day' => '2026-10-29', 'month' => '2026-09']));
    }

    public function test_reset_and_empty_results(): void
    {
        $this->assertSame([6, 5, 4, 3, 2, 1], $this->filteredIds([]));
        $this->assertSame([], $this->filteredIds(['day' => '2026-10-28']));
    }

    public function test_invalid_month_is_rejected(): void
    {
        $this->expectException(ValidationException::class);
        $this->filteredIds(['month' => '2026-13']);
    }
}
