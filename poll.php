<?php
//
// WIP
function ConnectDb(){
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "fietsenmaker";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully 1<br>";
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    // echo "Connected successfully 2<br>";
    return $conn;
}

function GetData($table) {
    // Connect database
    $conn = ConnectDb();
    
    // Select data uit de opgegeven table
    $query = $conn->prepare("SELECT * FROM $table");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function Poll(){
    try {
        $result = GetData('poll');
        foreach($result as &$data) {
            echo "<h1>".$data['vraag'] . "</h1>";
            echo "<br><br>";
        }} catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage() . "<br><br>";
    }
}

function Optie(){
    try {
        $result = GetData('optie');
        echo '<form action="#" method="POST">';
            foreach($result as &$data) {
                echo '<input type="radio" id="'. $data["id"] .'" name="select" value="select">';
                echo ' <label for="'. $data["id"] .'">'. $data["optie"] .'</label>';
                echo "<br>";
            }
            echo'<input type="submit" name="submit" value="Verzenden">';
        echo"</form>";
        Stemmen();
        #header('Location: opdracht_9.7.php');

    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage() . "<br><br>";
    }

}

function Stemmen1(){
    /*try {*/
        try {
            if(isset($_POST["submit"])){
                $conn = ConnectDb();
                $query = $conn->prepare("UPDATE optie SET gastenboek(stemmen) VALUES('stemmen = stemmen+1')");
                $query->execute();
                echo"Bericht Toegevoegd. <br><br><br>";
            } else {
                echo "Er is een fout opgetreden! <br><br>";
            }} catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage() . "<br><br>";
        }
    }

function Stemmen(){
    try {
        $conn = ConnectDb();
        $sql = "SELECT stem1, stem2, stem3, stem4 FROM poll";
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $choice = $_POST["choice"];
            switch($choice){
                case 1:
                    $sql = "UPDATE poll SET stem1 = stem1 + 1";
                    break;
                case 2:
                    $sql = "UPDATE poll SET stem2 = stem2 + 1";
                    break;
                case 3:
                    $sql = "UPDATE poll SET stem3 = stem3 + 1";
                    break;
                case 4:
                    $sql = "UPDATE poll SET stem4 = stem4 + 1";
                    break;
            }
        }
        $conn->exec($sql);
    }
}
?>