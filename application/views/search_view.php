<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Get me!</title>        

        <?php echo $include_view; ?>

        <script type="text/javascript">
            $(function() {

                var b_url = '<?php echo base_url() ?>';
                var resp = $("#resp");
                var d = $("#d");
                d.focus();

                $(document).on("click", "#search", function() {

                    $.ajax({
                        type: 'post',
                        url: 'http://' + b_url + 'main/s',
                        data: $("form").serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            show_preload();
                        },
                        complete: function() {
                            $.unblockUI();
                        },
                        success: function(data) {
                            insertData(data)
                        }
                    });
                    return false;
                });

                $(document).on("click", "div.pagination a", function() {
                    $.ajax({
                        type: 'post',
                        url: $(this).attr("href"),
                        data: 'page=' + ($(this).text()),
                        dataType: 'json',
                        beforeSend: function() {
                            show_preload();
                        },
                        complete: function() {
                            $.unblockUI();
                        },
                        success: function(data) {
                            insertData(data)
                        }
                    });
                    return false;
                });

                function insertData(data) {
                    resp.html("<span id='totmsg'>" + data.total_msg + "</span>");
                    resp.append(data.pag + "<br />");
                    inner(resp, $.parseJSON(data.resp));
                    resp.append("<br />" + data.pag);
                    d.focus();
                    return false;
                }

                function show_preload() {
                    $.blockUI({
                        //message:"<input type='image' src='../../media/img/loader.gif' />",
                        message: "<strong>Searching...</strong>",
                        css: {
                            border: 'none',
                            background: '#2871B0',
                            color: '#fff',
                            width: '124px',
                            //height:'128px',
                            left: '45%'
                        }
                    });
                }

                function inner(o, d) {
                    $.each(d, function(i, val) {
                        o.append(val + "<br/>");
                    });
                }
            });
        </script>
        <style type="text/css">      
            #u, #p { width:200px; }
            #totmsg { font-size: 13px; font-weight: bold; }
            #d { width: 800px;}
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

        <div class="col col-lg-12" style="margin-top: 8px;">
            <form action="" name="search" class="well" method="post" autocomplete="off">           
                <fieldset class="form-inline">               
                    <input type="text" id="d" name="d" placeholder="Insert a word" class="form-control" />
                    <button id="search" class="btn btn-primary" >Search</button>
                </fieldset>
            </form>
        </div>

        <div class="col col-lg-12">
            <fieldset>                                
                <legend>Result</legend>
                <pre><div id="resp"></div></pre>
            </fieldset>
        </div>        
            
    </body>
</html>
