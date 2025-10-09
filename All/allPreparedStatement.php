<?php
    function preparedStmt($query, $con, $types, $params) {
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);

        $res = mysqli_stmt_execute($stmt);
        if (!$res) {
            die("Execution failed: " . mysqli_stmt_error($stmt));
        }
        
        return [
            'stmt' => $stmt,
            'res' => $res
        ];
    }
?>