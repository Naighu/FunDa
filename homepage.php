<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="css/toast.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="toast.js"></script>

    <title>Funda Forms</title>
</head>

<body>

<div class="toast_container">
    <div class="toast_cell">
    </div>
  </div>

    <nav class="navbar navbar-style">
        <div class="container">
            <div class="navbar-container">
                <a href="">
                    <img class="app-logo" src="assets/logo.png" alt="App Logo">
                    <img class="app-name" src="assets/logo_name.png" alt="App name">
                </a>
            </div>
            <div class="navbar-right" style="background-color: white;display:flex;align-items:center;">
                <div style="background-color: white;">
                    <img style="background-color: white;margin-right:20px" src="assets/avatar.svg"
                        class="profile-pic" />
                </div>
                <div style="background-color: white;">
                    <?php 
                            session_start();
                             echo $_SESSION['name'];
                            echo "<br>";
                            echo $_SESSION['email'];

                            ?>
                </div>


            </div>


        </div>


    </nav>


    <div id="gray-strip">
        <div id="inside-strip">
            <h4 class="sub-heading1">Start a new form</h4>
            <a href="./create.php">
                <div id="white-rect">
                    <img src="assets/plus4.png" alt="new spreadsheet" width="85" height="85"
                        class="d-inline-block align-text-top" />
                </div>
            </a>

            <h6 class="mini-heading">Blank</h6>
        </div>
    </div>
    <h2 class="sub-heading">Created Forms</h2>
    <div class="list-forms">
        <?php
            
            require_once "db.php";
            session_start();
            $email = $_SESSION['email'];

            $sql = "SELECT * FROM forms WHERE email='$email';";
            
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    $form_data = json_decode($row['form_data']);
                    
                    $title = $form_data->title;
                    $updated = $row["updated"];
                    $description =$form_data->description;
                    $id = $row['id'];
                    echo "<div class='form-card' id='$id'> 
                        <a href='create.php?id=$id' class='form-card-anchor' style='text-decoration:none;'>
                            <div class='card text-justify' style='width: 20rem;margin-left:30px'>
                                <img class='card-img-top card-img' src='assets/form1.png' alt='Card image cap'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$title</h5>
                                    <p class='card-text'>$description</p>
                                    <div style='display:flex;justify-content:space-between;'>
                                        <p class='card-text'><small class='text-muted'>Last updated $updated</small></p>
                                        <button class= 'delete' id='delete'  >
                                            <img src='assets/delete.svg'/>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </a></div>";
                }
            }else{
                
            }

            $conn->close();
        ?>

    </div>
    <script>
        $('.list-forms').on('mouseenter','.form-card-anchor',function() {
            var div0 =$(this).children()[0]; 
            var div1 = $(div0).children()[1];
            var div2  = $(div1).children()[2];
            var delete_div = $(div2).children()[1];
            $(delete_div).css('display', 'inline');
        })
        $('.list-forms').on('mouseleave','.form-card-anchor',function() {
            var div0 =$(this).children()[0]; 
            var div1 = $(div0).children()[1];
            var div2  = $(div1).children()[2];
            var delete_div = $(div2).children()[1];
            $(delete_div).css('display', 'none');
        })

    $(".list-forms").on('click', '#delete', function(event) {
        event.preventDefault();
        var top_div = $(this).parents()[4];
        var form_id = $(top_div).attr("id");
        

        $.post("delete.php",{"form_id": form_id},function(data) {
            var result = JSON.parse(data);
            if(result["success"]) {
                success_toast(result["message"]);
                $(`#${form_id}`).remove();
            }else{
                error_toast(result["error"]);
            }
        })
    })


    
    </script>

</body>

</html>