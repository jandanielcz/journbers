<?php


namespace Journbers;


class Config
{
    const NOT_SET = 'd49242df-b373-4b8f-addb-3b98dee0ef2b';

    protected $values = [];

    public function require($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException(sprintf('Required config file %s not found.', $filePath));
        }

        $this->include($filePath);
    }

    public function include($filePath)
    {
        if (file_exists($filePath)) {
            $newConfig    = include $filePath;
            $this->values = array_merge($this->values, $newConfig);
        }
    }

    public function get($key, $defaultValue = self::NOT_SET)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        if ($defaultValue === self::NOT_SET) {
            throw new \RuntimeException(sprintf('Value for key %s is required somewhere and not configured.', $key));
        }

        return $defaultValue;
    }

    public function add(array $values)
    {
        $this->values = array_merge_recursive($this->values, $values);
    }
}
