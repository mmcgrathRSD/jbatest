

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peek-A-Booooomi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/5.30.0/jsoneditor.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>
    <!--<script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>-->
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
      
    </script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/5.30.0/jsoneditor.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <style type="text/css">
    #jsoneditor {
      width: 500px;
      height: 500px;
     }
     .tablecontrol div {
       padding: 5px !important;
       display: inline;
       border: 1px black;
     }
     .tablecontrol {
       margin-bottom: 20px;
     }
     .dangerClass {
       color: red;
     }
     .warningClass {
       color: orange;
     }
     .okClass {
       /*font-weight:bold;*/
     }
     #tab-content .tab {
        display: none;
      }
      
      #tab-content div.is-active {
        display: block;
      }

    </style>
  </head>
  <body>
 
 
    <div class="container">

      <div style="width:100%; overflow: auto;  display:flex; justify-content:space-evenly; align-items:flex-end">
        <img src='https://i.ebayimg.com/images/g/H24AAOSwmrlU1XW3/s-l300.jpg'>   <font size=+3>Peek-A-Booooomi</font>
      </div>
      

      <div class="tabs is-toggle is-fullwidth" id="tabs">
        <ul>
          <li class="is-active" data-tab="1">
            <a>
              <span>Orders</span>
            </a>
          </li>
          <li data-tab="2">
            <a>
              <span>Inventory</span>
            </a>
          </li>
        </ul>
      </div>

      <div id="tab-content">
        <div class="tab is-active" data-content="1">
          
          <div class='tablecontrol'>
            <!--
            <div> 
               Payment Method:<br/>
               <select id="payment_method">
                <option value="">ALL</option>
                <option value="1">Cash</option>
                <option value="2">Check</option>
                <option value="3">Discover</option>
                <option value="4">MasterCard</option>
                <option value="5">VISA</option>
                <option value="6">American Express</option>
                <option value="7">EFT</option>
                <option value="8">Physical GiftCard</option>
                <option value="9">PayPal</option>
                <option value="10">Affirm</option>
              </select>
            </div>-->
            <!--<div><input type='checkbox' id ='exclude_snfn' checked>Exclude SNFN</div>-->
            <div><button onClick='refreshTable(orderstable)'>50 Most Recent Orders</button></div>
            <div><button onClick='refreshTable(orderstable, "errors")'>Pending/Errored orders</button></div>
          </div>
          
          <table id="orderstable" width="100%" class='hover'>
            <thead>
              <tr>
              <th>externalid</th>
              <th>internalId</th>
              <th>tranid</th>
              <th>created</th>
              <th>orderstatus</th>
              <th>shipaddressee</th>
              <th>exSoAmount</th>
              <th>nsSoAmount</th>
              <th>email</th>
              
              <!--<th>syncStatusCode</th>-->
              </tr>
            </thead>
             <tbody>
             </tbody>
          </table>
           <b>Order Lookup</b><br/><select id='lookupWith'>
             <option value='externalid'>External Id</option>
             <option value='internalid'>Internal Id</option>
             <option value='tranid'>Transaction Id</option>
             </select>
           <input id='ordernumber' value=''> <button onClick="lookupOrder($('#ordernumber').val())">Lookup Order</button>
           <button onClick="resyncOrder($('#ordernumber').val())">Resync Order</button>
           <span id='spanResync'></span>
          <table width='100%'><tr>
            <td> <center><h2>Boomi</h2></center> <div id="mongo_order_editor1"></div></td>
            <td> <center><h2>Mongo</h2></center><div id="mongo_order_editor2"></div></td>
          </tr></table>
             
        </div>
        <div class='tab not-active' data-content="2">
          <div class='tablecontrol'>
            <div> 
               By Warehouse:<br/>
               <select id="warehouse">
                <option value="">ALL</option>
                <option value='UT-SALTLAKECITY-WHSE'>UT-SALTLAKECITY-WHSE</option>
                <option value='KY-LOUISVILLE-WHSE'>KY-LOUISVILLE-WHSE</option>
              </select>
            </div>
            <div><input type='checkbox' id='isDropshipOnly'/>isDropshipOnly </div>
            <div><button onClick='refreshTable(inventorytable)'>Go</button></div>
          </div>
             
          <table id="inventorytable" width="100%">
            <thead>
              <tr>
              <th>name</th>
              <th>quantityAvailable</th>
              <th>itemId</th>
              <th>itemInternalId</th>
              </tr>
            </thead>
             <tbody>
             </tbody>
          </table>
        </div>
      </div>
    </div>
 
  
  </body>
  
  
  <script>
      var container1 = document.getElementById('mongo_order_editor1');
      var container2 = document.getElementById('mongo_order_editor2');
      var options = {};
      var editor1 = new JSONEditor(container1, options);
      var editor2 = new JSONEditor(container2, options);
      
      function resyncOrder(orderid) {
        axios
            .post('/admin/Netsuite/resyncOrder/' + orderid + '?lookupWith=' + $("#lookupWith").val())
            .then(response => (alert(response.data)))
      }
      
      function lookupOrder(orderid) {
        axios
            .get('/admin/Netsuite/order/' + orderid + '?lookupWith=' + $("#lookupWith").val())
            .then(response => (this.orders = response))
            .then(response => (editor1.set(this.orders.data.boomi_order)))
            .then(response => (editor2.set(this.orders.data.mongo_order)));
            
      }
      function clickOrder(orderid) {
        axios
            .get('/admin/Netsuite/order/' + orderid + '?lookupWith=externalid')
            .then(response => (this.orders = response))
            .then(response => (editor1.set(this.orders.data.boomi_order)))
            .then(response => (editor2.set(this.orders.data.mongo_order)));
      }
      
      function refreshTable(tablename, type=null) {
        url = "/admin/Netsuite/orders";
        if (type=='errors') {
          url += "?type=errors";
        }

        tablename.clear().draw();
        tablename.ajax.url(url);
        tablename.ajax.reload();
      }
      
    
      var inventorytable = {};
       
      inventorytable = $('#inventorytable').DataTable({
        //"processing": true,
        //"serverSide": true,
        "deferLoading": 0,
        "ajax": {
          "url": "/admin/Netsuite/inventoryByWarehouse/",
          "data": function ( d ) {
              d.warehouse = $('#warehouse').val();
              d.isDropshipOnly = $('#isDropshipOnly').is(":checked");
          }
        }
      });
      
      
      orderstable = $('#orderstable').DataTable({
        //"processing": true,
        //"serverSide": true,
        "order": [[ 0, "desc" ]],
        "deferLoading": 0,
        "ajax": {
          "url": "/admin/Netsuite/orders/",
          "data": function ( d ) {
              //d.payment_method = $('#payment_method').val();
              //d.exclude_snfn = $('#exclude_snfn').is(":checked");
              //d.isDropshipOnly = $('#isDropshipOnly').is(":checked");
          }
        },
        "pageLength": 5,
       "language": {
        "lengthMenu": 'Display <select>'+
          '<option value="5" selected>5</option>'+
          '<option value="10">10</option>'+
          '<option value="25">25</option>'+
          '<option value="25">25</option>'+
          '<option value="-1">All</option>'+
          '</select> records'
      },
      "createdRow": function( row, data, dataIndex){
        //console.log([row, data, dataIndex]);
                if( data[9] ==  "2"){
                    $(row).addClass('dangerClass');
                }else if  ( data[9] ==  "1") {
                    $(row).addClass('okClass');
                }else if  ( data[9] ==  "0") {
                    $(row).addClass('warningClass');
                }
            }
      });
      
      $('#orderstable tbody').on('dblclick', 'tr', function () {
        var data = orderstable.row( this ).data();
        $("#ordernumber").val(data[0]);
        clickOrder(data[0]);
        $('html, body').animate({
      		scrollTop: $( "#ordernumber" ).offset().top
      	}, 500, 'linear');
      } );
      
      $(document).ready(function() {
        $('#tabs li').on('click', function() {
          var tab = $(this).data('tab');
      
          $('#tabs li').removeClass('is-active');
          $(this).addClass('is-active');
      
          $('#tab-content .tab').removeClass('is-active');
          $('div[data-content="' + tab + '"]').addClass('is-active');
        });
      });
  </script>
</html>
