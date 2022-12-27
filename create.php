<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    
        <link rel="stylesheet" href="css/create.css">
        <link rel="stylesheet" href="css/toast.css">
    
        <!-- bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        
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
   
          
       
        <nav class="navbar navbar-style" >
            <div class="container" style="background-color: white;">
                <div class="navbar-container" style="background-color: white;">
                    <a href="/web-programming">
                        <img style="background-color: white;" class="app-logo" src="assets/logo.png" alt="App Logo">
                        <img style="background-color: white;" class="app-name" src="assets/logo_name.png" alt="App name">
                    </a>
                </div>
                <div class="navbar-right" style="background-color: white;display:flex;align-items:center;">
                        <div style="background-color: white;">
                            <img style="background-color: white;margin-right:20px" src="assets/avatar.svg" class="profile-pic"/>
                        </div>
                        <div style="background-color: white; ">
                            <?php 
                            session_start();
                             echo $_SESSION['name'];
                            echo "<br>";
                            echo $_SESSION['email'];

                            ?>
                        </div>
                        
                        <button type="button" class="btn btn-primary" onclick="save()"  style="margin-left:20px">Save</button>
                        <button type="button" style="margin-left:20px" class="btn btn-success" onclick="preview()">Preview</button>
                    
                </div>
                
                
            </div>

            
        </nav>
    

        <div class="form-area">
            <div class="form-card form-title">
                <input type="text" class="form-control form-textfield-title" id="title" value="Untitled form" placeholder="Untitled form">
                <input type="text" class="form-control form-textfield-description" id="description" placeholder="Form description">
            </div>
             
        </div>
        
        <div class="bottom-nav">
                
               
                <button class="bottom-icon" onclick="add_element('short')">
                   
                        <img style="width:28px; " src="assets/left-align-paragraph.svg" /></button>
                   
                <button class="bottom-icon" onclick="add_element('checkbox')"><img style="width:35px;" src="assets/check-box.svg" /></button>
            
                <button class="bottom-icon" onclick="add_element('radio')"><img style="width:32px;" src="assets/radio.svg" /></button>
            
                <button class="bottom-icon" onclick="add_element('dropdown')"><img style="width:35px;" src="assets/arrow-drop-down-circle.svg" /></button>

            </div>

        <script>
           function get_formid(){
                var a = window.location.href.split("?")
                if(a.length != 2){
                    return null;
                }
                a = a[1].replace("id=","");

                try{
                    var id = parseInt(a);
                    return id;
                }catch(e) {
                    return null;
                }
           }

           let form_created_id = get_formid();

           let generated_fields = [];
           let header = {};
        
           if(form_created_id != null) {
            $.post("get_form.php",{"id": form_created_id},function(data) {
                var result = JSON.parse(data);
                if(result["success"]) {
                    header["title"] = result["data"]["title"];
                    header["description"] = result["data"]["description"];

                    $(".form-textfield-description").val(header["description"]);
                    $(".form-textfield-title").val(header["title"]);

                    

                    result["data"]["fields"].forEach(reflect_changes);
                }
            })
           }        

        function preview() {
            if(form_created_id == null){
                error_toast("Save the form first");
                return;
            }
            window.open(`preview.php?id=${form_created_id}`);
        }

            $(".form-textfield-description").on("input",function() {
                header["description"] = $(this).val()
                
            })

            $(".form-textfield-title").on("input",function() {
                header["title"] = $(this).val();
            })
            
            function reflect_changes(field) {
                
                
                if(field["data"] != null) {
                    add_element(field["type"],field["qns"],field["data"][0]["value"]);
                    for(let i=1;i<field["data"].length;i++) {
                        add_sub_fields(field["type"],parseInt(field["id"]),field["data"][i]["value"]);
                    }  
                }else{
                    add_element(field["type"],field["qns"]);
                }
            }

            function save() {
            
                let result_form = {
                    "title": header["title"],
                    "description": header["description"],
                    "fields": generated_fields
                }

                $.post(
                    "save.php",{ form_data: result_form, form_id: form_created_id},
                    function(data) {
                    console.log(data)
                    var result = JSON.parse(data);
                    if(result["success"]) {
                        success_toast(result["message"]);
                        if(form_created_id === null){
                            form_created_id = result["data"]["id"];
                        }
                       
                    }else{
                        success_toast(result["message"]);
                       
                    }


                    }
                )

                
            }

            function get_element(id) {
                var i = 0;
                while(i < generated_fields.length) {
                    if(generated_fields[i]["id"] === id ) {
                        return generated_fields[i];
                    }
                    i = i+1;
                }
            }

            $(".form-area").on("input", '.form-textfield',function() {
               const value = $(this).val();
               let id;
               const parents = $(this).parents();
               for(var i = 0; i < parents.length; i++){
                    if($(parents[i]).attr("class") === "form-card"){
                        id = parseInt($(parents[i]).attr("id"));
                        break;
                    }
                }
              
                var element = get_element(id);

               if($(this).attr('name') === "qns") {
                    element["qns"] = value;
               }else{
                const index = parseInt($(this).attr('id'))
               
                var i=0;
                while(i < element["data"].length)
                {
                    if(element["data"][i]["id"] === index) {
                        
                        element["data"][i]["value"] = value;
                        break;
                    }

                    i += 1;
                }
               }

            });

            function delete_field(id) {
                var i = 0;
                console.log(id);
                while(i < generated_fields.length) {
                    if(generated_fields[i]["id"] == id ) {
                        generated_fields.splice(i,1);
                    }
                    i = i+1;
                }
                
               $('#' + id).remove();
            }
            
        

            function add_element(field_type,qns="",default_value="") {
                var form_id = generated_fields.length + 1;
                let r;
                if(field_type === "short"){
                    generated_fields.push({
                        'qns' : qns,
                        "id" :  form_id,
                        "type": field_type
                    });
                    r = `<div class="form-card" id="${form_id}">
                            <input type="text" class="form-control form-textfield" name="qns"  placeholder="Question" value="${qns}">
                            <input type="text" class="form-control form-textfield"  placeholder="short answer">
                            <button class= "delete" onclick="delete_field('${form_id}')">
                                <img src="assets/delete.svg"/>
                            </button>
                        </div>`
                    
                }else if(field_type === "radio"){

                    generated_fields.push({
                        "id" : form_id,
                        'qns' : qns,
                        "type": field_type,
                        "data": [
                            {
                                "id" :  1,
                                "value": default_value
                            }
                        ]
                    });

                    r = ` <div class="form-card" id="${form_id}">
                            <input type="text" class="form-control form-textfield" name="qns"  placeholder="Question" value="${qns}">
                            <div id="inner-field${form_id}">
                                <input type="radio">
                                <label style="background-color: white;"><input type="text" class="form-control form-textfield" id="1" placeholder="option1" value=${default_value}></label>
                            </div>
                            <button style= "border:none; background-color:white;" onclick="add_sub_fields('radio',${form_id})">
                                <img style= width:30px; margin-left:10px" src="assets/add-new.svg"/>
                            </button>
                            <button class= "delete" onclick="delete_field('${form_id}')">
                                <img src="assets/delete.svg"/>
                            </button>
                        </div>`
                }else if(field_type === "checkbox") {

                    generated_fields.push({
                        "id" :  form_id,
                        'qns' : qns,
                        "type": field_type,
                        "data": [
                            {
                                "id" :  1,
                                "value": default_value
                            }
                        ]
                    });

                    r = `<div class="form-card" id="${form_id}">
                            <input type="text" class="form-control form-textfield" name="qns"  placeholder="Question" value="${qns}">
                            <div class="flex-center">
                                <div id="inner-field${form_id}">
                                    <input type="checkbox">
                                    <label style="background-color: white;"><input type="text" class="form-control form-textfield" id="1" placeholder="option1" value=${default_value}></label>
                                </div>
                                <button style= "border:none; background-color:white;" onclick="add_sub_fields('checkbox',${form_id})">
                                    <img style= width:30px; margin-left:10px" src="assets/add-new.svg"/>
                                </button>
                            </div>
                            <button class= "delete" onclick="delete_field('${form_id}')">
                                <img src="assets/delete.svg"/>
                            </button>
                        </div>`
                }else if(field_type === "dropdown") {

                    generated_fields.push({
                        "id" : form_id,
                        'qns' : qns,
                        "type": field_type,
                        "data": [
                            {
                                "id" :  1,
                                "value": default_value
                            }
                        ]
                    });

                    r = `<div class="form-card" id="${form_id}">
                            <input type="text" class="form-control form-textfield" name="qns"  placeholder="Question" value="${qns}">
                            <div class="flex-center">
                                <div id="inner-field${form_id}">
                                    <input type="text" class="form-control form-textfield" id="1" placeholder="option 1" value=${default_value}>
                                </div>
                                <button style= "border:none; background-color:white;" onclick="add_sub_fields('dropdown',${form_id})">
                                    <img style= width:30px; margin-left:10px" src="assets/add-new.svg"/>
                                </button>
                            </div>
                            <button class= "delete" onclick="delete_field('${form_id}')">
                                <img src="assets/delete.svg"/>
                            </button>
                        </div>`
                }

                $('.form-area').append(r);
            }

            function add_sub_fields(field_type,form_id,val="") {
                var r;
                var element = get_element(form_id);
          
                
                var length = element["data"].length + 1;
               
                element["data"].push(
                    {
                        "id" :  length,
                        "value": val
                    }
                );
                if(field_type === "radio"){
                    r = `<br> <input type="radio">
                        <label style="background-color: white;">
                            <input type="text" class="form-control form-textfield"  placeholder="option${length}" id=${length} value=${val}>
                        </label>`
                }else if(field_type === "checkbox") {
                    
                    r = `<br><input type="checkbox">
                            <label style="background-color: white;" ><input type="text" class="form-control form-textfield" id=${length} placeholder="option${length}" value=${val}></label>`
                }else if(field_type === "dropdown"){
                    r = `<input type="text" id=${length} class="form-control form-textfield" style="margin-top:10px;" placeholder="option${length}" value=${val}>`
                }
                const a = "#inner-field" + form_id; 
                $(a).append(r)
            }

            function launch_toast(text) {
          var x = document.getElementById("toast")
          document.getElementById("desc").innerText = text;
          x.className = "show";
          setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
        }


        </script>
    </body>
</html>