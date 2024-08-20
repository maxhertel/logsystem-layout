<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AdminController extends Controller
{
    public function prelogin(Request $request)
    {
        return response()->json([
            'action' => 'prelogin',
            'login' => session('login'),
            'authenticated' => session('authenticated'),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $storedHash = '$6$rounds=10000$VH3L37P2dn$LaZudFc0mu641wFm.5DzExUqhQl.U8hUFqJaawx5l49R1unv.l1Ah8lm0xxR0nypZ4i0zBKhZfmndwtXlH55n1'; // hash armazenado

        if ($request->login == 'admin' && Hash::check($request->password, $storedHash)) {
            session(['authenticated' => true, 'login' => $request->login]);
            return response()->json(['status' => 'OK']);
        }

        return response()->json(['status' => 'Login incorrect']);
    }

    public function logout(Request $request)
    {
        session()->forget(['authenticated', 'login']);
        return response()->json(['status' => 'Logged out']);
    }

    protected function checkauth()
    {
        if (!session('authenticated')) {
            abort(403, 'Security violation');
        }
    }

    protected function runcmd($cmd)
    {
        $process = Process::fromShellCommandline($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    public function stat(Request $request)
    {
        $this->checkauth();
        $output = $this->runcmd('accel-cmd show stat');
        return response()->json(['output' => $output]);
    }

    public function users(Request $request)
    {
        $this->checkauth();
        $output = $this->runcmd('accel-cmd show sessions');
        return response()->json($this->parseOutput($output));
    }

    public function startDump(Request $request)
    {
        $this->checkauth();
        $interface = escapeshellcmd($request->input('interface'));
        $this->runcmd("sudo tcpdump -l -n -i $interface 2>/dev/null > /var/tmp/$interface.dump & echo $! > /var/tmp/$interface.pid");
        return response()->json(['status' => 'Dump started']);
    }

    public function stopDump(Request $request)
    {
        $this->checkauth();
        $interface = escapeshellcmd($request->input('interface'));
        $output = $this->runcmd("sudo kill -15 $(cat /var/tmp/$interface.pid) && cat /var/tmp/$interface.dump");
        $this->runcmd("rm /var/tmp/$interface.*");
        return response()->json(['output' => $output]);
    }

    public function drop(Request $request)
    {
        $this->checkauth();
        $csid = escapeshellcmd($request->input('csid'));
        $output = $this->runcmd("accel-cmd terminate csid $csid");
        return response()->json(['output' => $output]);
    }

    public function kill(Request $request)
    {
        $this->checkauth();
        $interface = escapeshellcmd($request->input('interface'));
        $output = $this->runcmd("accel-cmd terminate if $interface hard");
        return response()->json(['output' => $output]);
    }

    protected function parseOutput($output)
    {
        $lines = explode("\n", trim($output));
        $parsed = [];

        foreach ($lines as $line) {
            $values = array_filter(explode('|', str_replace(' ', '', $line)));
            if (!empty($values)) {
                $parsed[] = $values;
            }
        }

        return ['output' => $parsed];
    }
}
