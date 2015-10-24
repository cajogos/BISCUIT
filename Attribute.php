<?php

// Attribute class (an extended key value pair)
class Attribute
{
    private $key;
    private $value;
    private $extras;

    public function __construct($input)
    {
        $bits = explode(':', trim($input));
        if (count($bits) > 1) {
            $this->key = $bits[0];
            $this->value = $bits[1];
            $extras_array = array();
            // Check if there is extras
            if (count($bits) > 2) {
                for ($i = 2; $i < count($bits); $i++) {
                    $extras_array[] = $bits[$i];
                }
            }
            $this->extras = $extras_array;
        } else {
            throw new BiscuitException('Invalid amount of arguments given for Attribute: ' . $input);
        }
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

    // Return the attribute extras (true) will return the extras as literals
    public function getExtras($literal = false)
    {
        if (!$literal) {
            return $this->extras;
        } else {
            $literals = array();
            for ($i = 0; $i < count($this->extras); $i++) {
                $literals[$i] = Attribute::extraAttributeLiteral($this->extras[$i]);
            }
            return $literals;
        }
    }

    public function toString()
    {
        $str = "Attribute Key: " . $this->key;
        $str .= " || Attribute Value: " . $this->value;
        $extras_array = $this->extras;
        foreach ($extras_array as $extra) {
            $str .= " || Attribute Extra: " . $extra;
        }
        return $str;
    }

    public static function extraAttributeLiteral($extra_attribute)
    {
        switch (strtolower($extra_attribute)) {
            case 'notnull':
                return 'Not Null';
            case 'autoinc':
                return 'Auto Increment';
            case 'unique':
                return 'Unique';
            default:
                return 'Not Defined';
        }
    }
}

?>