<?php
header("Content-Type: application/json");
$mysqli = new mysqli("localhost","root","","gopan"); // ganti username/password sesuai

if($mysqli->connect_error){
    die(json_encode(["error"=>"DB Connection Failed: ".$mysqli->connect_error]));
}

$action = $_GET['action'] ?? '';

if($action == 'create'){
    $data = json_decode(file_get_contents("php://input"), true);

    $nama = $mysqli->real_escape_string($data['nama']);
    $hp = $mysqli->real_escape_string($data['hp']);
    $kandang = $mysqli->real_escape_string(json_encode($data['kandang']));
    $statusAnggota = $mysqli->real_escape_string($data['statusAnggota']);
    $alasan = $mysqli->real_escape_string($data['alasan']);
    $createdBy = (int)$data['createdBy'];

    $sql = "INSERT INTO anggota (nama,hp,kandang,statusAnggota,alasan,createdBy)
            VALUES ('$nama','$hp','$kandang','$statusAnggota','$alasan',$createdBy)";

    if($mysqli->query($sql)){
        echo json_encode(["success"=>true, "id"=>$mysqli->insert_id]);
    } else {
        echo json_encode(["success"=>false, "error"=>$mysqli->error, "sql"=>$sql]);
    }
}

if($action == 'read'){
    $res = $mysqli->query("SELECT * FROM anggota ORDER BY id DESC");
    $list = [];
    while($row = $res->fetch_assoc()){
        $row['kandang'] = json_decode($row['kandang'], true);
        $list[] = $row;
    }
    echo json_encode($list);
}
?>
