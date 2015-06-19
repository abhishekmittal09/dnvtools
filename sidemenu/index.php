<?php

include('../pages.php');

$page="intro";
extract($_GET);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Dependency and Version tracking tools for OpenDaylight project">

<title>DnvTools</title>


<link rel="stylesheet" href="css/pure-min.css">

  
<!--[if lte IE 8]>
    <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
<![endif]-->
<!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="css/layouts/side-menu.css">
<!--<![endif]-->
  
<script type="text/javascript" src="./dist/vis.js"></script>
<link href="./dist/vis.css" rel="stylesheet" type="text/css" />

<style type="text/css">

    table.legend_table {
      font-size: 11px;
      border-width:1px;
      border-color:#d3d3d3;
      border-style:solid;
    }
    table.legend_table,td {
      border-width:1px;
      border-color:#d3d3d3;
      border-style:solid;
      padding: 2px;
    }
    div.table_content {
      width:80px;
      text-align:center;
    }
    div.table_description {
      width:100px;
    }

    #operation {
      font-size:28px;
    }
    #network-popUp {
      display:none;
      position:absolute;
      top:350px;
      left:170px;
      z-index:299;
      width:250px;
      height:120px;
      background-color: #f9f9f9;
      border-style:solid;
      border-width:3px;
      border-color: #5394ed;
      padding:10px;
      text-align: center;
    }


#mynetwork {
    width: 600px;
    height: 600px;
    border: 1px solid lightgray;
}
p {
    max-width:600px;
}

h4 {
    margin-bottom:3px;
}
</style>

</head>
<body>

<div id="layout">
    <!-- Menu toggle -->
    <a href="#menu" id="menuLink" class="menu-link">
        <!-- Hamburger icon -->
        <span></span>
    </a>

    <div id="menu">
        <div class="pure-menu">
            <a class="pure-menu-heading" href="<?php echo $pages["intro"]; ?>">Tools</a>

            <ul class="pure-menu-list">
                <li class="pure-menu-item"><a href="#dependency" class="pure-menu-link">Dependency<br>Graph</a></li>
                <li class="pure-menu-item"><a href="<?php echo $pages["version_skew_report"]; ?>" class="pure-menu-link">Version Skew</a></li>
                <li class="pure-menu-item"><a href="<?php echo $pages["database"]; ?>" class="pure-menu-link">Current<br>Database</a></li>
            </ul>
        </div>
    </div>

    <div id="main">
        <div id="dependency" class="header">
            <h1>Dependency Graph</h1>
            <h2>Visualization via Dependency Graphs for various projects</h2>
        </div>

        <div class="content">
            <h2 class="content-subhead">Page Under Construction</h2>
            <p>
            You will be able to choose the project and see it's dependencies or you can see the entire information as a whole in a single network
            </p>

<!--             <div id="config"></div>
 -->            
                <div id="network-popUp">
                  <span id="operation">node</span> <br>
                  <table style="margin:auto;"><tr>
                    <td>id</td><td><input id="node-id" value="new value" /></td>
                  </tr>
                    <tr>
                      <td>label</td><td><input id="node-label" value="new value" /></td>
                    </tr></table>
                  <input type="button" value="save" id="saveButton" />
                  <input type="button" value="cancel" id="cancelButton" />
                </div>
                <div id="mynetwork" style="width: 100%"></div>
            
        </div>

<!--         <?php
            if($page==="version_skew_report" || $page==="database"){
                include($page_locs[$page]);
            } else {
                include($page_locs["intro"]);
            }
        ?>
 -->    </div>
</div>

<script type="text/javascript" src="./js/data.json"></script>

<script src="js/ui.js"></script>
<script src="js/jquery.js"></script>
<script src="js/process.php"></script>

<script type="text/javascript">

$( document ).ready(function() {
  // create an array with nodes
  // var nodes = [
  //   {id: 1,  label: 'Node 11', color:'orange'},
  //   {id: 2,  label: 'Node 2', color:'DarkViolet', font:{color:'white'}},
  //   {id: 3,  label: 'Node 3', color:'orange'},
  //   {id: 4,  label: 'Node 4', color:'DarkViolet', font:{color:'white'}},
  //   {id: 5,  label: 'Node 5', color:'orange'},
  //   {id: 6,  label: 'cid = 1', cid:1, color:'orange'},
  //   {id: 7,  label: 'cid = 1', cid:1, color:'DarkViolet', font:{color:'white'}},
  //   {id: 8,  label: 'cid = 1', cid:1, color:'lime'},
  //   {id: 9,  label: 'cid = 1', cid:1, color:'orange'},
  //   {id: 10, label: 'cid = 1', cid:1, color:'lime'},
  // ];

  // // create an array with edges
  // var edges = [
  //   {"to": 1, from: 1},
  //   {from: 1, to: 3},
  //   {from: 10, to: 4},
  //   {from: 2, to: 5},
  //   {from: 6, to: 2},
  //   {from: 7, to: 5},
  //   {from: 8, to: 6},
  //   {from: 9, to: 7},
  //   {from: 10, to: 9},
  // ];

  // create a network
  var container = document.getElementById('mynetwork');
  var data = {
    nodes: nodes,
    edges: edges
  };
  var options = {
    layout:{
        randomSeed: 8,
    },
    manipulation: {

      addNode: function (data, callback) {
        alert("hainji");
        // filling in the popup DOM elements
        document.getElementById('operation').innerHTML = "Add Node";
        document.getElementById('node-id').value = data.id;
        document.getElementById('node-label').value = data.label;
        document.getElementById('saveButton').onclick = saveData.bind(this, data, callback);
        document.getElementById('cancelButton').onclick = clearPopUp.bind();
        document.getElementById('network-popUp').style.display = 'block';
      },

      editNode: function (data, callback) {
        // filling in the popup DOM elements
        document.getElementById('operation').innerHTML = "Edit Node";
        document.getElementById('node-id').value = data.id;
        document.getElementById('node-label').value = data.label;
        document.getElementById('saveButton').onclick = saveData.bind(this, data, callback);
        document.getElementById('cancelButton').onclick = cancelEdit.bind(this,callback);
        document.getElementById('network-popUp').style.display = 'block';
      },

      addEdge: function (data, callback) {
        if (data.from == data.to) {
          var r = confirm("Do you want to connect the node to itself?");
          if (r == true) {
            callback(data);
          }
        }
        else {
          callback(data);
        }
      }
    },
    // configure: {
    //   filter:function (option, path) {
    //     if (path.indexOf('physics') !== -1) {
    //       return true;
    //     }
    //     if (path.indexOf('smooth') !== -1 || option === 'smooth') {
    //       return true;
    //     }
    //     return false;
    //   },
    //   // container: document.getElementById('config')
    // },
    physics: {
        "forceAtlas2Based": {
          "springLength": 100
        },
        "solver": "forceAtlas2Based"
    }
  };
  var network = new vis.Network(container, data, options);

    function clearPopUp() {
      document.getElementById('saveButton').onclick = null;
      document.getElementById('cancelButton').onclick = null;
      document.getElementById('network-popUp').style.display = 'none';
    }

    function cancelEdit(callback) {
      clearPopUp();
      callback(null);
    }

    function saveData(data,callback) {
      data.id = document.getElementById('node-id').value;
      data.label = document.getElementById('node-label').value;
      clearPopUp();
      callback(data);
    }


});

</script>


</body>
</html>
