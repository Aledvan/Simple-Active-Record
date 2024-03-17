<?php
declare(strict_types=1);

namespace Src\Exception;

use Src\Logging\Logger;

class DbException
{
    /**
     * @var array $errorMessages
     */
    private static array $errorMessages = [
        100 => 'Failed to create database.',
        101 => 'Failed to create table.',
        102 => 'Failed to use database.',
        103 => 'Failed to drop database.',
        104 => 'Failed to drop table.',
        105 => 'Failed to create table.',
        106 => 'Failed to create table.',
        108 => 'Failed to create table.',
        109 => 'Failed to create table.',
        110 => 'Failed to create table.',
        111 => 'Failed to create table.',
    ];

    /**
     * @param array $e
     * @param int $codeError
     *
     * @return void
     */
    public static function setError(array $e, int $codeError): void
    {
        if (isset(self::$errorMessages[$codeError])) {
            $errorMessage = self::$errorMessages[$codeError];
            Logger::setLog('Error: ' . $errorMessage . ' | ' . $e->getMessage());
        }
    }
}