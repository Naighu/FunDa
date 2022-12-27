<?php
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    require_once "db.php";
    $id = (int)$_GET["id"];
    $sql = "SELECT * FROM forms WHERE id=$id;";
    $forms;
    $email;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $forms = json_decode($row["form_data"]);        
        $email = $row["email"];
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="css/preview.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>



    <title>Funda Forms</title>
</head>

<body>


    <div class="form-area">
        <div class="form-card form-title-card">
            <p class="form-title">
                <?php
                    echo $forms->title;
                ?>
            </p>
            <p class="form-description">
                <?php
                    echo $forms->description;
                ?>
            </p>

        </div>

        <?php 
            foreach ($forms->fields as $e) {
                if($e->type == 'short') {
                    echo "<div class='form-card' id='$e->id'>
                        <div class='form-qns'>
                            $e->qns
                        </div>
                        <input type='text' class='form-textfield'  placeholder='Your answer'>
                    </div>";
                }
                elseif($e->type == 'radio') {
                    $a =  "<div class='form-card' id='$e->id'>
                            <div class='form-qns'>
                                $e->qns
                            </div>
                            <div id='inner-field$e->id' style='margin-top:20px;'>";
                    foreach($e->data as $v) {
                        $a = $a . "<div class='form-check'>
                                    <input class='form-check-input' type='radio' name='$e->id'/>
                                    <label class='form-check-label' style='margin-left:10px;margin-bottom:10px;' > $v->value </label>
                                </div>";
                    }
                                    
                    $a = $a . "</div></div>";

                    echo $a;
                }
                elseif($e->type == 'checkbox') {
                    $a =  "<div class='form-card' id='$e->id'>
                            <div class='form-qns'>
                                $e->qns
                            </div>
                            <div id='inner-field$e->id' style='margin-top:20px;'>";
                    foreach($e->data as $v) {
                        $a = $a . "<div class='form-check'>
                                    <input class='form-check-input' type='checkbox' name='$e->id'/>
                                    <label class='form-check-label' style='margin-left:10px;margin-bottom:10px;' > $v->value </label>
                                </div>";
                    }
                                    
                    $a = $a . "</div></div>";

                    echo $a;
                }
                elseif($e->type == 'dropdown') {
                    $a =  "<div class='form-card' id='$e->id'>
                            <div class='form-qns'>
                                $e->qns
                            </div>
                            <div id='inner-field$e->id' style='margin-top:20px;'>
                            <div class='dropdown'>
                                <button class='btn btn-primary dropdown-toggle' style='background-color: rgb(103, 58, 183); border:none;' type='button' data-toggle='dropdown'>
                                    Dropdown button
                                </button>
                                <div class='dropdown-menu'>
                            ";
                    foreach($e->data as $v) {
                        $a = $a . "<a class='dropdown-item' href='#'>$v->value</a>";
                    }
                                    
                    $a = $a . "</div></div></div></div>";

                    echo $a;
                }

                
            }

        ?>
<div class="submit">
        <button class="btn btn-primary" style="background-color: rgb(103, 58, 183);border:none;">
            Submit
        </button>
    </div>
    </div>
    
</body>

</html>