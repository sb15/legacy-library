<?php

namespace Models\Phalcon;

class Generator
{

    private $di = null;

    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return \Phalcon\Db\Adapter\Pdo\Mysql
     */
    public function getConnection()
    {
        return $this->di->getDb();
    }

    public function __construct($di)
    {
        $this->setDi($di);
    }

    public function getFilter()
    {
        return $this->di->getFilter();
    }

    public function getTablesData()
    {

        $connection = $this->getConnection();

        $filter = $this->getFilter();

        $tables = array();

        $tablesList = $connection->listTables();

        foreach ($tablesList as $table) {

            $columns = array();
            $primary = array();
            $columnsList = $connection->describeColumns($table);

            foreach ($columnsList as $column) {
                $columns[] = $column->getName();
                if ($column->isPrimary()) {
                    $primary[] = $column->getName();
                }

                //var_dump($connection->getColumnDefinition($column));
            }


            //print_r($columnsList);

            $tables[$table] = array(
                'name' => $table,
                'model' => $filter->filter($table),
                'columns' => $columns,
                'primary' => $primary
            );
        }

        foreach ($tables as $table => &$tableFields) {
            $refs = $connection->describeReferences($table);
            $indexes = $connection->describeIndexes($table);
//print_r($indexes);
            foreach ($refs as $ref) {
                $referencedTable = $ref->getReferencedTable();
                $columns = $ref->getColumns();
                $referencedColumns = $ref->getReferencedColumns();

                $firstReferencedColumn = reset($referencedColumns);
                $firstColumn = reset($columns);
//var_dump($firstReferencedColumn, $firstColumn, $tableFields);
                if (in_array($firstColumn, $tableFields['primary']) && in_array($firstReferencedColumn, $tables[$referencedTable]['primary'])) {

                    $tableFields['ref_one_to_one'][] = array(
                        'column' => $firstColumn,
                        'model' => $tables[$referencedTable]['model'],
                        'ref_column' => $firstReferencedColumn
                    );

                    $tables[$referencedTable]['ref_one_to_one'][] = array(
                        'column' => $firstReferencedColumn,
                        'model' => $tableFields['model'],
                        'ref_column' => $firstColumn
                    );

                } else {

                    $tableFields['ref_one_to_many'][] = array(
                        'column' => $firstColumn,
                        'model' => $tables[$referencedTable]['model'],
                        'ref_column' => $firstReferencedColumn
                    );

                    $tables[$referencedTable]['ref_many_to_one'][] = array(
                        'column' => $firstReferencedColumn,
                        'model' => $tableFields['model'],
                        'ref_column' => $firstColumn
                    );

                }

            }

          //  print_r($tables);

        }

        return $tables;

    }
}
