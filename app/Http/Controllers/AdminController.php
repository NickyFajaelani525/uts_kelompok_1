<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Hardcoded API key for analytics (Sudah Diperbaiki)
    private $analyticsKey = null; // VULNERABILITY
    private $smtpPassword = null; // VULNERABILITY

    // VULNERABILITY: Command Injection — user input passed to exec()
    public function pingServer(Request $request)
    {
        $host = $request->input('host');
        // Attacker can pass: "google.com; rm -rf /tmp; ls"
        $result = exec("ping -c 4 " . $host);
        return response()->json(['result' => $result]);
    }

    // VULNERABILITY: Command Injection in report generator
    public function generateReport(Request $request)
    {
        $format = $request->input('format', 'pdf');
        $month  = $request->input('month');
        // Dangerous: format and month are user-controlled
        $output = shell_exec("php artisan report:generate --format=$format --month=$month");
        return response()->json(['output' => $output]);
    }
}
