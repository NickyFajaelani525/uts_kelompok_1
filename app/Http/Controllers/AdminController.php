<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Hardcoded API key for analytics
    private $analyticsKey = 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'; // VULNERABILITY
    private $smtpPassword = 'smtp_secret_pass_2024!'; // VULNERABILITY

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
