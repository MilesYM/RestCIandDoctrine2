<?php include('template/header.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#logout').click(function(e) {
        e.preventDefault();
        var key = getCookie("key");
        alert(key);

        $.ajax({
            type : 'POST',           
            url : server_url + 'application/logout', // Servlet URL           
            data:{
                'key': key,
            },
            success : function(data) {
                if( !data.success ) {
                    alert('server error occurred');

                    //THIS IS ONLY FOR BROWSERS, use local storage
                    setCookie('key', '', 0);

                    //Redirect
                    window.location.href="index.php";
                }
                else
                {
                    alert("See you Soon");

                    //THIS IS ONLY FOR BROWSERS, use local storage
                    setCookie('key', '', 0);

                    //Redirect
                    window.location.href="index.php";
                }
            },
            error : function( xhr, type )
            {
                alert('server error occurred');
            } 
        });
    });
});
</script>
    <a href="#" id="logout" class="nav-item text-nav">LOG OUT<em class="icon-page"></a></span>
<?php include('template/footer.php'); ?>