<?php

/*
 * DEBUGGING FUNCTIONS p_var AND p_info
 */

function p_var($variable)
{
    echo '<pre style="border:1px solid black;background:#eee;padding:10px;">';
    var_dump($variable);
    echo '</pre>';
}

function p_info($name, $variable, $colour = 'red')
{
    echo '<p style="color:' . $colour . ';border:1px solid black;padding:10px;">';
    echo '<strong>' . $name . '</strong>: ' . $variable;
    echo '</p>';
}

// Require the classes
require_once('ParserClasses.php');
