<script>
    function ajaxFunc(urlX, methodX, callback){
                $.ajax({
                    url: urlX,
                    method: methodX,
                    success: function(res){
                        let response = JSON.parse(res);
                        callback(response);
                        // response.forEach(function(res){
                        //     list.innerHTML = res.colX/R_name);
                        // });
                    }, 
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });            
    }
</script>