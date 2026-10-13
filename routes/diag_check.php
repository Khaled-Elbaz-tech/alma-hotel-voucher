<?php

// Check jobs table
echo '--- JOBS TABLE ---' . PHP_EOL;
try {
    $count = DB::table('jobs')->count();
    echo 'Jobs count: ' . $count . PHP_EOL;
    if ($count > 0) {
        $jobs = DB::table('jobs')->get();
        foreach ($jobs as $j) {
            echo 'Job payload: ' . substr($j->payload, 0, 800) . PHP_EOL;
            echo '---' . PHP_EOL;
        }
    }
} catch (\Throwable $e) {
    echo 'Jobs error: ' . $e->getMessage() . PHP_EOL;
}

echo '--- USERS ---' . PHP_EOL;
$users = \App\Models\User::all();
foreach ($users as $u) {
    echo 'User: id=' . $u->id . ', name=' . $u->name . ', email=' . $u->email . PHP_EOL;
}

echo '--- EMPLOYEES ---' . PHP_EOL;
$employees = \App\Models\Employee::all();
foreach ($employees as $e) {
    echo 'Employee: id=' . $e->id . ', name=' . $e->name . ', email=' . $e->email . ', is_active=' . $e->is_active . PHP_EOL;
}

echo '--- VOUCHERS (last 5) ---' . PHP_EOL;
$vouchers = \App\Models\Voucher::orderBy('id', 'desc')->limit(5)->get();
foreach ($vouchers as $v) {
    echo 'Voucher: id=' . $v->id . ', number=' . $v->voucher_number . ', type=' . $v->voucher_type . ', user_id=' . $v->user_id . ', employee_id=' . $v->employee_id . PHP_EOL;
}
