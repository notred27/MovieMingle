<?php

    function isFriend($id, $other_id, $conn) {
        $sql = "SELECT COUNT(*) AS c FROM (SELECT * FROM friends WHERE friend_id = ? and user_id = ? UNION SELECT * FROM friends WHERE friend_id = ? and user_id = ?) as F;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $id, $other_id, $other_id, $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()["c"] == 1;
    }
?>