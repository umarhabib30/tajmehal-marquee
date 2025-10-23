 
 <?php
 use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
Schema::create('salaries', function (Blueprint $table) {
$table->id();
$table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
$table->integer('month'); // numeric month (1–12)
$table->integer('year');
$table->decimal('basic', 10, 2)->default(0);
$table->integer('absent_days')->default(0);
$table->decimal('deduction_per_absent', 10, 2)->default(0);
$table->decimal('net_salary', 10, 2)->default(0);
$table->timestamps();
});
}

public function down(): void
{
Schema::dropIfExists('salaries');
}
};
?>