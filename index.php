<?php 
require_once "koneksi.php";

    function get_tree($name = "root") { //40pts
    //mengambil struktur tree dari database dengan top level item sesuai input
    //mengembalikan assoc array dengan property nama dan children
    //dimana children adalah array of assoc array berisi child dibawah member ybs
        $tree = array();
        $newArr = array(
            "name" => $name,
            "children" => array()
        );
        array_push($tree, $newArr);
        $children = get_children($name);
        for($i=0; $i<count($children); $i++){
            $newArr[$i] = array(
                "name" => $children[$i],
                "children" => array()
            );
            array_push($tree[0]['children'], $newArr[$i]);
            $children2 = get_children($children[$i]);

            for($j=0; $j<count($children2); $j++){
                $newArr[$j] = array(
                    "name" => $children2[$j],
                    "children" => array()
                );
                array_push($tree[0]['children'][$i]['children'], $newArr[$j]);
                $children3 = get_children($children2[$j]);

                for($k=0; $k<count($children3); $k++){
                    $newArr[$k] = array(
                        "name" => $children3[$k],
                        "children" => array()
                    );
                    array_push($tree[0]['children'][$i]['children'][$j]['children'], $newArr[$k]);
                }

            }

        }

        return $tree;
    }

    function get_parents($name) { //25pts
    //mengambil semua parent dari input mulai dari direct-parent sampai root member
    //mengembalikan array of string berisi nama parent urut dari yang paling dekat
        $parents = array();
        $status = true;
        while($status){
            global $mysqli;
            $query = "SELECT a.`name`
                        FROM `member` a
                        INNER JOIN `member` b ON a.id=b.parent_id
                    WHERE b.`name` = '".$name."'";
            
            $result = $mysqli->query($query);
            $row = mysqli_fetch_array($result);

            array_push($parents, $row['name']);
            $name = $row['name'];
            
            if($name == "root"){
                $status = false;
            }
        }

        return $parents;
    }

    function get_children($name) { //15pts
        //mengambil semua direct-child dari input
        //mengembalikan array of string yang berisi daftar nama child
        global $mysqli;
        $query = "SELECT b.`name`
                    FROM `member` a
                    INNER JOIN `member` b ON a.id=b.parent_id
                WHERE a.`name` = '".$name."' AND b.name <> 'root'";

        $children = array();
        $result = $mysqli->query($query);
        while($row = mysqli_fetch_array($result)){
            array_push($children, $row['name']);
        }
        return $children;
    }

    // $children = get_children('Samantha');
    // echo json_encode($children);

    // $parents = get_parents('Derpina');
    // echo json_encode($parents);

    // $tree = get_tree();
    // echo json_encode($tree);

?>