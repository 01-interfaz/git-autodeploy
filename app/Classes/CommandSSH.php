<?php


namespace App\Classes;

use phpseclib3\Net\SSH2;

class CommandSSH
{
    private string $lastSSHResult;
    private ?SSH2 $sshSession = null;

    private static ?CommandSSH $instance = null;

    public static function default() : CommandSSH
    {
        if (self::$instance === null) self::$instance = new static();
        return self::$instance;
    }

    public function run($commands): ?string
    {
        $sshCommand = "";
        foreach ($commands as $command) $sshCommand .= "$command\n";
        if ($this->connectAndRunCommand($sshCommand)) return $this->lastSSHResult;
        return null;
    }

    public function openSession() : bool
    {
        try {
            if ($this->sshSession === null) {
                $ssh = new SSH2(SSH_HOST, SSH_PORT, 5);
                if (!$ssh->login(SSH_USER, SSH_PASS)) throw new Exception('Login failed');
                $this->sshSession  = $ssh;
            }

            if($this->sshSession->isConnected() && $this->sshSession->isAuthenticated()) {
                FileLogger::default()->writeLine("SSH open session");
                return true;
            }
            return false;
        } catch (Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    public function closeSession() : bool
    {
        try {
            if ($this->sshSession !== null && $this->sshSession->isConnected()) {
                $this->sshSession->disconnect();
            }
            FileLogger::default()->writeLine("SSH closed session");
            return true;
        } catch (Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    private function connectAndRunCommand(string $command): bool
    {
        try {
            if (!$this->openSession()) throw new Exception("Hasn't valid ssh session");
            $this->lastSSHResult = $this->sshSession->exec($command);
            return true;
        } catch (Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }
}