<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013-2015 Marius Sarca
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Database\SQL;

use Opis\Database\Connection;

class Update extends UpdateStatement
{
    
    protected $connection;
    
    public function __construct(Connection $connection, $table)
    {
        parent::__construct($connection->compiler(), $table);
        $this->connection = $connection;
    }
    
    protected function incrementOrDecrement($sign, $columns, $value)
    {
        if(!is_array($columns))
        {
            $columns = array($columns);
        }
        
        $values = array();
        
        foreach($columns as $k => $v)
        {
            if(is_numeric($k))
            {
                $values[$v] = function($expr) use($sign, $v, $value){
                  $expr->column($v)->{$sign}->value($value);  
                };
            }
            else
            {
                $values[$k] = function($expr) use($sign, $k, $v){
                    $expr->column($k)->{$sign}->value($v);
                };
            }
        }
        
        return $this->set($values);
        
    }
    
    public function increment($column, $value = 1)
    {
        return $this->incrementOrDecrement('+', $column, $value);
    }
    
    public function decrement($column, $value = 1)
    {
        return $this->incrementOrDecrement('-', $column, $value);
    }
    
    public function set(array $columns)
    {
        parent::set($columns);
        return $this->connection->count((string) $this, $this->compiler->getParams());
    }
    
}
