<?php

namespace App\Traits\Seeder;

use RuntimeException;

trait ImportDatasets
{
    public function __invoke(array $parameters = [])
    {
        $this->importDatasets();

        parent::__invoke($parameters);
    }

    private function importDatasets()
    {
        if (! isset($this->model)) {
            throw new \InvalidArgumentException('Property $model does not exist: '.get_class($this));
        }

        $model = new $this->model;
        $table = $model->getTable();
        $dataset = $table.'.php';
        $datasetPath = database_path('datasets/'.$dataset);

        $this->command->getOutput()->writeln("<comment>  Using dataset:</comment> {$dataset}");

        if (!is_file($datasetPath)) {
            throw new RuntimeException('Dataset missing: '.$datasetPath);
        }

        $importRules = require($datasetPath);
        if (isset($importRules['columns']) && isset($importRules['imports'])) {
            $columns = $importRules['columns'];
            $lookups = $importRules['lookups'] ?? null;
            $imports = $importRules['imports'];
            $columnCount = count($columns);

            if (is_array($imports)) {
                //// import from array

                $this->createFromDatasets($columns, $lookups, $imports);

            } else if (is_string($imports)) {
                //// import from csv file

                if (is_file($imports)) {
                    // continue
                } else if (is_file(database_path('datasets/'.$table.'.csv'))) {
                    $imports = database_path('datasets/'.$table.'.csv');
                } else {
                    throw new \RuntimeException('File CSV does not exist: '.$imports);
                }

                $csvContent = file_get_contents($imports);

                // iterasi per line
                $csv = [];
                $csvRows = str_getcsv($csvContent, "\n", "'", "\\");
                foreach($csvRows as $row) {
                    $csv[] = str_getcsv($row, ",", "'", "\\");
                }

                $this->createFromDatasets($columns, $lookups, $csv);
            }
        }
    }

    private function createFromDatasets($columns, $lookups, $imports)
    {
        $columnCount = count($columns);

        // check data length
        foreach ($imports as $key => $row) {
            if (count($row) != $columnCount) {
                throw new RuntimeException("Mismatch data length row: $key");
            }
        }

        // import for real
        foreach ($imports as $key => $row) {
            // transform ke assoc array
            $data = array_combine($columns, $row);

            // resolving loookups
            if ($lookups) {
                $data = static::resolveLookup($data, $lookups, $this->command->getOutput());
            }

            $this->model::create($data);
        }
    }

    // Insert foreign id hasil lookup pada $data
    private static function resolveLookup($data, $lookups, $console = null)
    {
        foreach ($data as $column => $value) {
            // set empty string as null
            if ($value == '') {
                $data[$column] = null;
                continue;
            }

            // lookup
            if (isset($lookups[$column])) {
                $data[$column] = self::foreignId($lookups[$column], $column, $data);

                // tampilakan error ketika hasil lookup NULL
                if ($console && is_null($data[$column])) {
                    $dataStr = is_array($data) ? json_encode($data) : $data;
                    $lookupStr = is_array($lookups[$column]) ? json_encode($lookups[$column]) : $lookups[$column];
                    $console->writeln("  <error>Got NULL:</error> {$column} => {$lookupStr} ::: {$dataStr}");
                }
            }
        }

        return $data;
    }

    // Cari foreign id sesuai lookup & nama kolom
    private static function foreignId($lookup, $column, $data)
    {
        $model = null;
        $refColumn = null;

        if (is_array($lookup)) {
            $model = $lookup[0];
            $refColumn = $lookup[1];
        }

        if ($model) {
            $row = $model::where([$refColumn => $data[$column]])->select('id')->first();

            return $row->id ?? null;
        } else {
            throw new RuntimeException('Invalid lookup: '. json_encode($lookup));
        }
    }
}
