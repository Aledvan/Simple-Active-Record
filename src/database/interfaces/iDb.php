<?php

namespace Src\Database\Interfaces;

interface iDb
{
    public function create($entity, $options);

    public function use($database);
    
    public function drop($entity, $options);
    
    public function truncate($table);
    
    public function rename($oldTable, $newTable);
    
    public function insert($table, $options);
    
    public function select($table, $options);
    
    public function update($table, $options);

    public function delete($table, $options);
}