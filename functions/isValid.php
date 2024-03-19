<?php
    function isValid($id, $model) {
        $model->id = $id;
        // Call read_single from model
        $result = $model->read_single();
        // Return results
        return ($result->rowCount() > 0);
    }