<?php

require_once('ParserFunctions.php');

$query_txt = "BUILD users { user_id:int:autoinc:notnull, username:string:notnull };";
$MySQL_Result = null;


if ($_REQUEST) {
    $query_txt = strtolower($_REQUEST['query']);
}

$query_input = new BiscuitQuery($query_txt);

$mysql_biscuit = new BiscuitMySQL($query_input);
$MySQL_Result = $mysql_biscuit->getMySQLResult();


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MySQL Parser Tests</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/font-awesome.min.css"/>
    <link rel="stylesheet" href="../css/jquery-ui.min.css"/>
    <style>
        #biscuit-examples {

        }
    </style>
</head>
<body>

<div class="container">
    <h1>BISCUIT MySQL Parser</h1>

    <div id="biscuit-input">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><label for="biscuit-query-input">Enter BISCUIT Query</label></h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="post">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="query" id="biscuit-query-input"
                                   placeholder="Enter Query" value="<?php echo $query_txt; ?>" autocomplete="off"/>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" class="btn btn-success" value="Run Query"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <hr/>
    <div id="mysql-output">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">MySQL Output</h3>
            </div>
            <div class="panel-body">
                <pre><?php echo $MySQL_Result; ?></pre>
            </div>
        </div>
        <div id="biscuit-examples">
            <h3>BISCUIT Examples</h3>
            <ul>
                <li><strong>BUILD:</strong> BUILD users { user_id:int:autoinc:notnull, username:string:notnull };</li>
                <li><strong>CHANGE:</strong> CHANGE users { ADD(join_date:date) };</li>
                <li><strong>DESTROY:</strong> DESTROY users;</li>
                <li><strong>FETCH:</strong> FETCH users { [user_id, status] WHERE(status:eq:A) SORT(asc:user_id) };</li>
                <li><strong>INSERT:</strong> INSERT users { user_id:bcd001, age:45, status:A };</li>
                <li><strong>UPDATE:</strong> UPDATE users { SET(user_id:123) WHERE(status:eq:A) };</li>
                <li><strong>REMOVE:</strong> REMOVE users { WHERE(username:eq:user001) };</li>
            </ul>
        </div>
        <footer>
            <p>BISCUIT was developed by Carlos Ferreira<br/>Copyright &copy; <?php echo date('Y'); ?></p>
        </footer>
    </div>


</body>
</html>