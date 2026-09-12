<script>
    function ajaxFunc(urlX, methodX, callback){
                $.ajax({
                    url: urlX,
                    method: methodX,
                    success: function(res){
                        let response = (typeof res === 'string') ? JSON.parse(res) : res;
                        callback(response);
                    }, 
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });            
    }
</script>