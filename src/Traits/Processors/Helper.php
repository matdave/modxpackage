<?php

namespace MatDave\MODXPackage\Traits\Processors;

trait Helper
{
    protected function handleBoolean(string $name, bool $default = false): bool
    {
        $value = $this->getProperty($name, $default);

        if (($value === true) || ($value === 'true') || ($value === 1) || ($value === '1')) {
            $value = true;
        } else {
            $value = false;
        }

        $this->setProperty($name, $value);

        return $value;
    }

    protected function handleInteger(string $name, int $default = 0): int
    {
        $value = $this->getProperty($name, $default);
        $value = (int) $value;

        $this->setProperty($name, $value);

        return $value;
    }

    protected function checkPermissions(): bool
    {
        foreach ($this->permissions as $permission) {
            if (!$this->modx->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }
}
