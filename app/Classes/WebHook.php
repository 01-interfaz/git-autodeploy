<?php


namespace App\Classes;


use App\Classes\Command\CommandPayload;
use App\Classes\Repository\IDriver;

class WebHook
{
    private array $_hooks;
    private string $_appName;
    private ?IDriver $_driver = null;

    public function __construct(string $json_path)
    {
        $this->_hooks = json_decode(file_get_contents($json_path), true);
        $this->_appName = request_input('app') ?? "";
    }

    public function validate(?string &$error): bool
    {
        $error = "";
        if (!$this->validAppName()) $error .= "invalid app name\n";
        if (!$this->hasHook()) $error .= "not has hook\n";
        return strlen($error) == 0;
    }

    public function validAppName(): bool
    {
        return strlen($this->_appName) > 0;
    }

    public function hasHook(): bool
    {
        return array_key_exists($this->_appName, $this->_hooks);
    }

    public function getHook(): array
    {
        return $this->_hooks[$this->_appName];
    }

    public function getPath(): string
    {
        return $this->getHook()['path'];
    }

    public function getBranchs(): array
    {
        return explode(',', $this->getHook()['branch']);
    }

    public function getDeployer(): Deployer
    {
        return new Deployer($this->getDriver(), $this->getCommandPayload());
    }

    private function getDriver(): IDriver
    {
        if ($this->_driver == null) $this->_driver = DriverFactory::create($this->getHook()['driver']);
        return $this->_driver;
    }

    private function getCommandPayload(): CommandPayload
    {
        $payload = new CommandPayload();
        $payload->FolderLocation = $this->getPath();
        $branchs = $this->getBranchs();
        foreach ($branchs as $branch) $payload->AddGitBranch($branch);
        return $payload;
    }
}