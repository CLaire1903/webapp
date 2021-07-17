<!DOCTYPE HTML>
<html>

<head>
    <title>Week 9</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container-flex d-flex justify-content-center" style="height:577px; background-color: #ffb3ff; ">
        <div class="d-flex justify-content-center flex-column m-5 border-3 col-4 rounded-3" style="background-color: #ffffff; ">
            <form action="action_page.php" onsubmit="return myFunction()">
                <div class=" m-3 input-group-lg">
                    <label>First Name:</label>
                    <input type="text" id="firstName" placeholder="First Name">
                </div>
                <div class=" m-3 input-group-lg">
                    <label>Last Name:</label>
                    <input type="text" id="lastName" placeholder="Last Name">
                </div>
                <div class="gender m-3 ">
                    <label>Gender:</label>
                    <div>
                        <label>
                            <input type="radio" id="genderM" value="male" name="gender">
                            Male <br>
                            <input type="radio" id="genderF" value="female" name="gender">
                            Female<br>
                        </label>
                    </div>
                </div>
                <div class="gender m-3 ">
                    <label>Hobby:</label>
                    <div>
                        <input type="checkbox" id="hobby1" value="swimming">
                        Swimming
                        <input type="checkbox" id="hobby2" value="reading">
                        Reading
                        <input type="checkbox" id="hobby3" value="gardening">
                        Gardening
                    </div>
                </div>
                <div class="m-3">
                    <label>Lucky Number:</label>
                    <select id = "luckyNum">
                    <option value='' disabled selected>-- Lucky Number --</option>
                        <option value='0'> 0 </option>
                        <option value='1'> 1 </option>
                        <option value='2'> 2 </option>
                        <option value='3'> 3 </option>
                        <option value='4'> 4 </option>
                        <option value='5'> 5 </option>
                        <option value='6'> 6 </option>
                        <option value='7'> 7 </option>
                        <option value='8'> 8 </option>
                        <option value='9'> 9 </option>
                    </select>

                </div>
                <div class="button m-3 d-grid">
                    <button type="submit" class='btn btn-primary btn-large'>Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        function myFunction() {
            var firstName = document.getElementById("firstName").value;
            var lastName = document.getElementById("lastName").value;
            var checkRB = document.querySelectorAll("input[type=radio]:checked");
            var checkCB = document.querySelectorAll("input[type=checkbox]:checked");
            var luckyNum = document.getElementById("luckyNum").value;
            var flag = false;
            var msg = "";
            if (firstName == "") {
                flag = true;
                msg = msg + "Please enter your first name!\r\n";
            }
            if (lastName == "") {
                flag = true;
                msg = msg + "Please enter your last name!\r\n";
            }
            if (checkRB.length == 0){
                flag = true;
                msg = msg + "Please select your gender!\r\n";
            }
            if (checkCB.length == 0){
                flag = true;
                msg = msg + "Please select at least one hobby!\r\n";
            }
            if(luckyNum == "") {
                flag = true;
                msg = msg + "Please select your lucky number!";
            }
            if (flag == true) {
                alert(msg);
                return false;
            }else{
                return true;
            }
        }
    </script>

</body>

</html>