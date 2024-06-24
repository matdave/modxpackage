<?php

namespace MatDave\MODXPackage\Traits\Processors;

use xPDO\Om\xPDOQuery;

trait Duplicate
{
    /**
     * @param $class
     * @param string $alias
     * @param string $unset
     */
    public function duplicateOne($class, string $alias, string $unset): void
    {
        $one = $this->object->getOne($alias);
        if (empty($one)) {
            return;
        }
        $oneArray = $one->toArray();
        if ($this->hasConflict($oneArray)) {
            return;
        }
        if ($unset && isset($oneArray[$unset])) {
            unset($oneArray[$unset]);
            if (empty($oneArray)) {
                return;
            }
        }
        $newOne = $this->modx->newObject($class);
        foreach ($oneArray as $k => $v) {
            $newOne->set($k, $v);
        }
        $this->newObject->addOne($newOne, $alias);
    }

    /**
     * @param $class
     * @param string $alias
     * @param string $unset
     */
    public function duplicateMany($class, string $alias, string $unset): void
    {
        $many = $this->object->getMany($alias);
        if (empty($many)) {
            return;
        }
        $manyArray = [];
        foreach ($many as $one) {
            $oneArray = $one->toArray();
            if ($this->hasConflict($oneArray)) {
                return;
            }
            if ($unset && isset($oneArray[$unset])) {
                unset($oneArray[$unset]);
                if (empty($oneArray)) {
                    continue;
                }
            }
            $newOne = $this->modx->newObject($class);
            foreach ($oneArray as $k => $v) {
                $newOne->set($k, $v);
            }
            $manyArray[] = $newOne;
        }
        if (empty($manyArray)) {
            return;
        }
        $this->newObject->addMany($manyArray, $alias);
    }

    /**
     * @param $array
     * @return bool
     */
    public function hasConflict($array): bool
    {
        if (!empty($this->skipKeys)) {
            $keyCheck = array_intersect_key($array, $this->skipKeys);
            foreach ($keyCheck as $key) {
                if ($array[$key] === true || $array[$key] === 1) {
                    return true;
                }
            }
        }
        return false;
    }
}
