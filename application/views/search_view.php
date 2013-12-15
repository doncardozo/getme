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
                
                var resp = $("#resp");
                var d = $("#d");                
                d.focus();
                
                $(document).on("click", "#search", function() {
                    
                    $.ajax({
                        type: 'post',
                        url: 'http://getme/index.php/main/s',
                        data: $("form").serialize(),   
                        dataType: 'json',
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
                           if(data.total !== '' && data.total_cur !== ''){
                               resp.html("Total: "+data.total+" | Per Page: "+data.total_cur+"<br />");
                           }
                           else {
                               resp.html("Total: "+data.total+"<br />");
                           }   
                           inner(resp, $.parseJSON(data.resp));    
                           d.focus(); //Poner foco en "search text"
                           return false;                            
                       }                    
                    });
                    return false;
                });
                
                function inner(o, d){
                    $.each(d, function(i, val){
                        o.append(val+"<br/>");
                    }); 
                }
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
