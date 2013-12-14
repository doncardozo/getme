<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Get me!</title>        
        
        <?php echo $include_view; ?>
        
        <script type="text/javascript">
            $(function() {
                
                $("#d").focus();
                
                $(document).on("click", "#search", function() {
                    $.ajax({
                        type: 'post',
                        url: 'http://getme/index.php/main/s',
                        data: $("form").serialize(),                        
                        beforeSend:function(){
                            $.blockUI({
                               message:"<input type='image' src='../../media/img/loader.gif' />",
                               css:{
                                   border:'none',
                                   width:'124px',
                                   height:'128px',
                                   left:'45%'
                               }
                            });   
                        },
                        complete:function(){
                            $.unblockUI(); 
                        },
                        success: function(data) {                                  
                            $("#resp").html(data);                                       
                            return false;                            
                        }                    
                    });
                    return false;
                });
            });
        </script>
        <style type="text/css">      
            #u, #p { width:200px; }
            #d { width: 500px;}
            pre #resp { 
                font-size: 10px; 
            }
            .exit {
                width:50px;
                float:right;
            }
        </style>
    </head>
    <body>                
        <form action="" name="search" class="well" method="post" autocomplete="off">           
            
            <label>Search</label>
            <fieldset class="form-inline">                                
                <input type="text" id="d" name="d" placeholder="Insert a word" class="form-control" />
                <button id="search" class="btn btn-primary" >Search</button>
            </fieldset><br />
            <label>Result</label>
            <fieldset>                                
                <pre><div id="resp"></div></pre>
            </fieldset>
        </form>
    </body>
</html>
