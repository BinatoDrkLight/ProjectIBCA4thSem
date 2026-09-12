<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="../../All/allMenu.css">
    <link rel="stylesheet" href="route.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./route.js"></script>
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
                <input type="search" id="routeSearchInput" placeholder="Search route...">
            </div>
            <div class="routeCateH">
            
            <?php
                //Connect to database.
                include_once('../../All/allDatabaseConnection.php');
                $con = dbConnection();

                //Prepared statement function.
                include_once('../../All/allPreparedStatement.php');

                //Params
                $queryRoute = "SELECT DISTINCT r.R_name, r.R_id FROM route AS r";
                $resQuery = mysqli_query($con, $queryRoute);
                $dataRouteD = [];

                if($resQuery){
                    while($row = mysqli_fetch_assoc($resQuery)){
                        $dataRouteD[] = $row;
                    }
                }
                $con->close();
            ?>

            <?php
                //Function for ajax
                include_once('../../All/allAjax.php');
                
                foreach($dataRouteD as $index => $dataRoute){
                    $rName = htmlspecialchars($dataRoute['R_name'], ENT_QUOTES, 'UTF-8');
                    $rId = (int)$dataRoute['R_id'];
                ?>
                    <h1 class="routeHeading"><?php echo $rName; ?> <button id="rC<?php echo $rId; ?>" class="routesCompsH" type="button" aria-label="Expand route"><i class="fas fa-chevron-down"></i></button></h1> 
                    <ul class="routeDropDownH">
                        <li id="rL<?php echo $rId; ?>" class='routeList'>
                        </li>
                    </ul> 
            <?php
                }
            ?>

             <script>
                $(document).ready(function() {
                    // Search filter
                    $('#routeSearchInput').on('keyup', function() {
                        let filter = $(this).val().toLowerCase();
                        $('.routeHeading').each(function() {
                            let text = $(this).text().toLowerCase();
                            if (text.includes(filter)) {
                                $(this).show();
                                $(this).next('.routeDropDownH').show();
                            } else {
                                $(this).hide();
                                $(this).next('.routeDropDownH').hide();
                            }
                        });
                    });

                    // First-level accordion: Route click (heading or button)
                    $(document).on('click', '.routeHeading', function() {
                        let urlRoute = 'routeP.php';
                        let methodRoute = 'GET';
                        let btn = $(this).find('.routesCompsH');
                        var secondLevelEl = btn.attr('id');
                        
                        btn.toggleClass('active');

                        let routeIdNum = parseInt((secondLevelEl).split("C")[1]);
                        let listElement = document.getElementById((secondLevelEl).replace("C", "L"));

                        if(btn.hasClass('active')){
                            ajaxFunc(urlRoute, methodRoute, function(response){
                                listElement.innerHTML = '';
                                const addedModels = new Set();

                                response.forEach(function(res){
                                    if(parseInt(res.R_id) === routeIdNum && res.B_model && !addedModels.has(res.B_model)){
                                        addedModels.add(res.B_model);
                                        let newElii = document.createElement('li');
                                        newElii.className = "routeListTwo";
                                        newElii.id = `rLT_${res.R_id}_${res.B_id}`;
                                        newElii.dataset.routeId = res.R_id;
                                        newElii.dataset.busId = res.B_id;
                                        newElii.dataset.busModel = res.B_model;
                                        newElii.textContent = res.B_model;
                                        listElement.appendChild(newElii);
                                    }
                                });
                                listElement.style.display = "block";
                            });
                        } else {
                            listElement.style.display = "none";
                            $(`.routeCard_R${routeIdNum}`).remove();
                            $(listElement).find('.routeListTwo').removeClass('active');
                        }
                    });

                    // Second-level click: Bus Model selection
                    $(document).on('click', '.routeListTwo', function(e) {
                        e.stopPropagation();

                        let $this = $(this);
                        let targetRouteId = parseInt($this.data('route-id'));
                        let targetBusModel = String($this.data('bus-model'));
                        let groupClass = `routeGroup_R${targetRouteId}_${encodeURIComponent(targetBusModel).replace(/[^a-zA-Z0-9]/g, '_')}`;

                        $this.toggleClass('active');
                       
                        let rightContV = $('.routeRightH');
                        if(rightContV.length === 0){
                            rightContV = $('<div class="routeRightH"></div>');
                            $('body').append(rightContV);
                        }

                        if($this.hasClass('active')){
                            ajaxFunc('routeP.php', 'GET', function(response){
                                if(!$this.hasClass('active')) return;

                                $(`.${groupClass}`).remove();

                                const renderedBusIds = new Set();
                                response.forEach(function(res){
                                    if(parseInt(res.R_id) === targetRouteId && res.B_model === targetBusModel && res.B_id && !renderedBusIds.has(res.B_id)){
                                        renderedBusIds.add(res.B_id);
                                        let distDisplay = '';
                                        if (typeof getDistance === 'function' && typeof userLat === 'number' && typeof userLng === 'number' && res.L_latitude && res.L_longitude) {
                                            let distM = getDistance(userLat, userLng, parseFloat(res.L_latitude), parseFloat(res.L_longitude));
                                            distDisplay = `${distM}m`;
                                        }

                                        let card = $(`
                                            <div class="routesH ${groupClass} routeCard_R${targetRouteId}" data-b-id="${res.B_id}" data-l-id="${res.L_id}">
                                                <h1>${res.R_start} - ${res.R_end}</h1>
                                                <h2>${res.B_reg_no}</h2>
                                                <p>${distDisplay}</p>
                                            </div>
                                        `);
                                        rightContV.append(card);
                                    }
                                });
                            });
                        } else {
                            $(`.${groupClass}`).remove();
                        }
                    });

                    // Take user to track page with exact bus ID
                    $(document).on('click', '.routesH', function(){
                        var b_id = $(this).data('b-id');
                        var l_id = $(this).data('l-id');
                        var targetId = b_id || l_id;

                        localStorage.setItem('busId', String(targetId));
                        window.location.href = `../track/trackH.php?bus_id=${targetId}`;
                    });
                });
            </script>
        </div>
    </div>
    </div>
            
    <div class="footerH">
        <?php
            include_once("../../All/allMenu.php");
        ?>
    </div>
</body>
</html>