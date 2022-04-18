<?php

namespace App\Services;

use Exception;
use League\Csv\Reader;

class CsvReader 
{
    private $csvPath; 
    private $hasInternalHeader = false;
    private $headers = array();
    private $reader; 

    public function __construct($path, array $headers = array())
    {
        $this->csvPath = $path;
        $this->reader = Reader::createFromPath($path);

        if(count($headers) == 0) $this->hasInternalHeader = true;
        else $this->headers = $headers;
    }

    public function fetchToArray()
    {
        $data = array();

        foreach ($this->reader as $index => $row)
        {
            if ($index === 0 && $this->hasInternalHeader == true)
            {
                $this->headers = $row;
            } 
            else
            {
                if(count($this->headers) != count($row)) throw new Exception();
                
                $data[] = array_combine($this->headers, $row);
            }
        }

        return $data;
    }

    public static function lineCount($file)
    {
  
        $f = fopen($file, 'rb');
        $lines = 0; $buffer = '';

        while (!feof($f)) {
            $buffer = fread($f, 8192);
            $lines += substr_count($buffer, "\n");
        }

        fclose($f);

        if (strlen($buffer) > 0 && $buffer[-1] != "\n") {
            ++$lines;
        }
        
        return $lines;
    }
}