<?php

namespace Enhacudima\DynamicExtract\Helper;

use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\Schema\Column;

class DoctrineHelper
{
    public static function GetColumnType($connectionName, $tableOrView, $columnName)
    {
        $connection = DB::connection($connectionName);
        $schema = $connection->getDoctrineSchemaManager();

        try {
            // Try Doctrine first (works for tables, sometimes for views)
            $columns = $schema->listTableColumns($tableOrView);

            if (isset($columns[$columnName])) {
                // Doctrine DBAL 3.x: use static getName()
                return $columns[$columnName]->getType()->getName();

            }
        } catch (\Throwable $e) {
            // If Doctrine fails, fall back to INFORMATION_SCHEMA
        }

        // Fallback: query INFORMATION_SCHEMA for views or complex cases
        $platform = $connection->getDoctrineConnection()->getDatabasePlatform()->getName();

        switch ($platform) {
            case 'mysql':
                $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = ?
                      AND COLUMN_NAME = ?";
                $result = $connection->selectOne($sql, [$tableOrView, $columnName]);
                return $result->DATA_TYPE ?? null;

            case 'postgresql':
                $sql = "SELECT data_type FROM information_schema.columns
                    WHERE table_name = ? AND column_name = ?";
                $result = $connection->selectOne($sql, [$tableOrView, $columnName]);
                return $result->data_type ?? null;

            // Add other platforms as needed
        }

        return null;
    }
}
