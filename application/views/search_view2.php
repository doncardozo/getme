<!DOCTYPE html>
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

        <div class="container">

            <header class="clearfix">

                <div class="navbar">  

                    <form class="form-inline" role="search" name="search" autocomplete="off">
                        <fieldset>
                            <legend>Search</legend>
                            <input type="text" id="d" name="d" class="form-control" placeholder="Insert a word or regular expression">
                            <button type="submit" id="search" class="btn btn-primary">Search</button>
                        </fieldset>
                    </form>

                </div>

            </header>

            <secion>                
                <fieldset>
                    <legend>Result</legend>
                    <pre><div id="resp"></div></pre>
                </fieldset>                
            </secion>
            
        </div>
        
    </body>
</html>
