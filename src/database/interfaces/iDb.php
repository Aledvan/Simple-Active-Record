<?php

namespace Src\Database\Interfaces;

interface iDb
{
    public static function create(string $entity, array $options);

    public static function use(string $database);
    
    public static function drop(string $entity, array $options);
    
    public static function truncate(string $table);
    
    public static function rename(string $oldTable, string $newTable);
    
    public static function insert(string $table, array $options);
    
    public static function select(string $table, array $options);
    
    public static function update(string $table, array $options);

    public static function delete(string $table, array $options);
}