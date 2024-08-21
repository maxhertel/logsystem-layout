<!doctype html>
<html>
<head>
    <title>Laravel Routes</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/cupertino/jquery-ui.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <style type="text/css">
        body {
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div id="loadingdialog" style="text-align:center">Please wait...</div>
    <div id="tabroutes">
        <table id="troutes" class="cell-border compact"></table>
    </div>

    <script>
    function loadRoutes() {
        $("#loadingdialog").dialog('open');
        $.get("/routes", function (data) {
            $("#troutes").DataTable({
                data: data,
                iDisplayLength: 25,
                bLengthChange: false,
                columns: [
                    {title: "URI", data: "uri"},
                    {title: "Name", data: "name"},
                    {title: "Action", data: "action"},
                    {title: "Method", data: "method"}
                ],
            });
            $("#loadingdialog").dialog('close');
        });
    }

    $(document).ready(function(){
        $("#loadingdialog").dialog({ autoOpen: false, modal: true });
        loadRoutes();
    });
    </script>
</body>
</html>
