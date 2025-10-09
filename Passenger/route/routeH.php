<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route</title>
    <link rel="stylesheet" href="route.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
        include_once("../../All/allHeader.php");
    ?>

    <div class="background">
        <?php include '../../All/allLogo.php' ?>
    </div>

    <div class="routeContainerH">
        
        <div class="routeLeftH">
            <div class="routeSearchH">
                <input type="search" placeholder="search">
            </div>
            <div class="routeCateH">
            
            <?php
                //Connect to database.
                include_once('../../All/allDatabaseConnection.php');
                $con = dbConnection();

                //Prepared statement function.
                include_once('../../All/allPreparedStatement.php');

                //Params
                $queryRoute = "SELECT DISTINCT r.R_name, r.R_id
                                    FROM route AS r
                                    WHERE ? = ?";
                $typeRoute = "ii";
                $paramsRoute = ['1', '1'];

                //Call prepared statement function
                $resArrRoute = preparedStmt($queryRoute, $con, $typeRoute, $paramsRoute);
                $stmtRoute = $resArrRoute['stmt'];

                //Get result from query
                $resultRouteData = mysqli_stmt_get_result($stmtRoute);
                $dataRouteD = [];

                //Check if anything is retrieved.
                while($row = mysqli_fetch_assoc($resultRouteData)){
                    $dataRouteD[] = $row;
                }
                $con->close();
                mysqli_stmt_close($stmtRoute);
            ?>

            <?php
                //Function for ajax
                include_once('../../All/allAjax.php');
                
                foreach($dataRouteD as $index => $dataRoute){
                ?>
                    
                    <h1><?php echo $dataRoute['R_name']; ?> <button id="rC<?php echo $dataRoute['R_id'] ?>" class="routesCompsH"><i class="fas fa-chevron-down"></i></button></h1> 
                    <ul class="routeDropDownH">
                        <li id="rL<?php echo $dataRoute['R_id'] ?>" class='routeList'>
                        </li>
                    </ul> 
            <?php
                }
            ?>

             <script>
                $(document).ready(function() {
                    $('.routesCompsH').on('click', function() {
                        let urlRoute = 'routeP.php';
                        let methodRoute = 'GET';
                        var secondLevelEl = $(this).attr('id');
                        
                        $(this).toggleClass('active');

                        let btnIdNum = parseInt((secondLevelEl).split("C")[1]);

                        let listElement = document.getElementById((secondLevelEl).replace("C", "L"));
                        if($(this).hasClass('active')){
                            //Call ajaxFunc()
                            ajaxFunc(urlRoute, methodRoute, function(response){
                                listElement.innerHTML = '';

                                //Make a set for bus model
                                const addedRouteNames = new Set();

                                response.forEach(function(res){
                                    //Create element only once for each unique bus model.
                                    if(btnIdNum === res.R_id && !addedRouteNames.has(res.B_model)){
                                        //Add to the set
                                        addedRouteNames.add(res.B_model);
                                        //Create new element
                                        newElii = document.createElement('li');
                                        newElii.className = "routeListTwo";
                                        newElii.id = `rLT${res.B_id}`;
                                        newElii.textContent = res.B_model;
                                        listElement.appendChild(newElii);
                                    }
                                });
                                listElement.style.display = "block";
                            });
                        } else {
                            listElement.style.display = "none";
                        }
                    });

                    // Dynamically add a new item to the list
                    $(document).on('click', '.routeListTwo', function() {
                        $(this).toggleClass('active');
                       
                        let rightContClassCheck = $('.routeRightH');
                        let rightContV = $('.routeRightH');

                        if(rightContClassCheck.length === 0){
                            rightContV = $('<div class="routeRightH"></div>');
                            $('body').append(rightContV);
                        }

                        var thirdLevelEl = $(this).attr('id');
                        var rouIdNum = parseInt((thirdLevelEl).split("rLT")[1]);

                        let urlRoute = 'routeP.php';
                        let methodRoute = 'GET';
                        let rightRoutesV = $(`.rS${rouIdNum}`);

                        if($(this).hasClass('active')){
                            if (rightRoutesV.length === 0) {
                                ajaxFunc(urlRoute, methodRoute, function(response){
                                    const addedBusNames = new Set();
                                    response.forEach(function(res){
                                        if(rouIdNum === res.R_id && !addedBusNames.has(res.B_id)){
                                            addedBusNames.add(res.B_id);

                                            rightRoutesV = $(`<div id="rS${rouIdNum}" class="routesH rS${rouIdNum}" data-l-id="${res.L_id}"></div>`);
                                            rightContV.append(rightRoutesV);
                                    
                                            let newHoneRoute = $(`<h1>${res.R_start} - ${res.R_end}</h1>`);
                                            let newHtwoRoute = $(`<h2>${res.B_reg_no}</h2>`);
                                            let newParaRoute = $(`<p>${getDistance(userLat, userLng, res.L_latitude, res.L_longitude)}m</p>`);

                                            rightRoutesV.append(newHoneRoute, newHtwoRoute, newParaRoute);
                                        }
                                    });
                                });
                            }
                        } else {
                            rightRoutesV.remove();
                        }
                    });

                    //Take user to track page.
                    $(document).on('click', '.routesH', function(){
                        var l_id = $(this).data('l-id');

                        localStorage.setItem('busId', l_id);
                        window.location.href = "../track/trackH.php";
                    });
                });
            </script>
            
    <div class="footerH">
        <?php
            include_once("../../All/allMenu.php");
        ?>
    </div>
                    
    <script src="./route.js"></script>
</body>
</html>