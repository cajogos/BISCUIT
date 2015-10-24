<?php

require_once('parser/ParserFunctions.php');

$query = null;
$biscuit_query = 'Not started';
if ($_POST)
{
    $query = strtolower($_POST['biscuit-input']);
    try {
        $biscuit_query = new BiscuitQuery($query);
        $biscuit_query = BiscuitQuery::convertToChildQueryType($biscuit_query);
    } catch (BiscuitException $e) {
        $biscuit_query = $e->getExceptionMessage();
    }
}

require_once('_header.php');

?>

    <h2>BISCUIT Parser</h2>
    <div class="row">
        <div class="col-md-6">
            <p>Use the form below to try out BISCUIT's parser.</p>

            <form method="post" action="parser.php" autocomplete="off">
                <div class="form-group">
                    <label for="biscuit-input"></label>
                    <input class="form-control" type="text" name="biscuit-input" id="biscuit-input"
                           placeholder="BUILD biscuit {};"
                           value="<?php if ($query != null) echo $query; ?>"/>
                </div>
                <button class="btn btn-lg btn-info" type="submit" onclick="runBiscuit()">Run BISCUIT!</button>
            </form>
            <hr/>
            <ul>
                <li>
                    <strong>BUILD: </strong>BUILD users { user_id:int:autoinc:notnull, username:string:notnull };
                </li>
                <li>
                    <strong>CHANGE: </strong>CHANGE users { add(join_date:date) };
                </li>
                <li>
                    <strong>DESTROY: </strong>DESTROY users;
                </li>
                <li>
                    <strong>FETCH: </strong>FETCH users { [user_id, status] WHERE(status:eq:A) SORT(asc:user_id) };
                </li>
                <li>
                    <strong>INSERT: </strong>INSERT users { user_id:bcd001, age:45, status:A };
                </li>
                <li>
                    <strong>UPDATE: </strong>UPDATE users { SET(user_id:123) WHERE(status:eq:A) };
                </li>
                <li>
                    <strong>REMOVE: </strong>REMOVE users { WHERE(username:eq:user001) };
                </li>
            </ul>
        </div>
        <div class="col-md-6">
            <label for="biscuit-output"></label>
            <textarea class="form-control" rows="10" id="biscuit-output" name="biscuit-output"
                      readonly><?php if ($biscuit_query != null) var_dump($biscuit_query); ?></textarea>
        </div>
    </div>

    <script>

    </script>

<?php require_once('_footer.php'); ?>