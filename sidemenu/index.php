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

<script type="text/javascript" src="./js/data1.json"></script>

<script src="js/jquery.js"></script>
  
<script type="text/javascript" src="./dist/vis.js"></script>
<link href="./dist/vis.css" rel="stylesheet" type="text/css" />

<link title="timeline-styles" rel="stylesheet" href="./css/timeline.css">
<script src="./js/timelinejs.js"></script>


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
            <a class="pure-menu-heading" href="#">Tools</a>

            <ul class="pure-menu-list">
                <li class="pure-menu-item"><a href="#dependency" class="pure-menu-link" onclick="changeDiv('dependency')">Dependency<br>Graph</a></li>
                <li class="pure-menu-item"><a href="#version_skew" class="pure-menu-link" onclick="changeDiv('version_skew')">Version Skew</a></li>
                <li class="pure-menu-item"><a href="#time_line" class="pure-menu-link" onclick="changeDiv('time_line')">Time Line</a></li>
            </ul>
        </div>
    </div>

    <div id="main">
      <div class="singleElementDisplay" id="dependency" style="display:none">
        <div class="header">
          <h1>Dependency Graph</h1>
          <h2>Visualization via Dependency Graphs for various projects</h2>
        </div>

        <div class="content">
          <h2 class="content-subhead"></h2>
          <p>
            You will be able to choose the project and see it's dependencies or you can see the entire information as a whole in a single network
          </p>

<!--             <div id="config"></div>
-->            
          <div id="network-popUp">
                <span id="operation">node</span> <br>
                <table style="margin:auto;">
                  <tr>
                    <td>id</td>
                    <td><input id="node-id" value="new value" /></td>
                  </tr>
                  <tr>
                    <td>label</td><td><input id="node-label" value="new value" /></td>
                  </tr>
                </table>
                <input type="button" value="save" id="saveButton" />
                <input type="button" value="cancel" id="cancelButton" />
          </div>
          <div id="mynetwork" style="width: 100%"></div>
        
        </div>
      </div>

      <!-- Second Menu Item -->
      <div class="singleElementDisplay" id="version_skew" style="display:none">
        <div class="header">
          <h1>Version Skew</h1>
          <h2>Version Skew Present in the Lithium Project</h2>
        </div>

        <div class="content">
          <h2 class="content-subhead"></h2>
          <p>
            Find the Version Skew in the project here. You'll be able to find the projects which are dependent on different versions of the same module here
          </p>

<!--             <div id="config"></div>
-->            

          <table id="version_skew_table" class="pure-table-bordered" border="1" style="width:100%">
              <tbody>
                  <tr>
                      <td>#</td>
                      <td>Module Name</td>
                      <td>Project</td>
                      <td>Version</td>
                      <td>Path</td>
                  </tr>
              </tbody>
          </table>          
        
        </div>
      </div>

      <!-- Third Menu Item -->
      <div class="singleElementDisplay" id="time_line" style="display:none">
        <div class="header">
          <h1>Time Line</h1>
          <h2>Time Line of Changes in Various Versions</h2>
        </div>

        <div class="content">
          <h2 class="content-subhead"></h2>

<!--             <div id="config"></div>
-->            
        
        </div>
        
        <div id='timeline-embed' style="width: 100%; height: 600px"></div>
      </div>

    </div>

</div>

<script src="js/ui.js"></script>

<script type="text/javascript">

$( document ).ready(function() {

  // var timeline_json = make_the_json("timeline-embed", timeline_info); // you write this part
  // two arguments: the id of the Timeline container (no '#')
  // and the JSON object or an instance of VCO.TimelineConfig created from 
  // a suitable JSON object
  window.timeline = new VCO.Timeline('timeline-embed', "./js/timeline.js");

  function differentVersions(list) {
    var result = [];
    $.each(list, function(i, e) {
      if ($.inArray(e, result) == -1) result.push(e);
    });
    if(result.length>1){
      return 1;
    }
    else {
      return 0;
    }
  }

  var modulesVersionInfo = {}

  $.each(modulesMappedToProjects, function(module, moduleDependencyInfo) {

      var projectVersion = {}
      $.each(moduleDependencyInfo, function(projectName, versionInfo) {
          var Versions = []
          var Paths = []
          for (var i = versionInfo.length - 1; i >= 0; i--) {
            Versions.push(versionInfo[i][0]);
            Paths.push(versionInfo[i][1]);
          };
          var differentFlag = differentVersions( Versions );
          if( differentFlag===1 ){
            projectVersion[projectName] = {};
            projectVersion[projectName]['versions'] = Versions;
            projectVersion[projectName]['paths'] = Paths;
          }
      });

      // console.log(projectVersion);
      // console.log(Object.keys(projectVersion).length);

      if(Object.keys(projectVersion).length>0){
        modulesVersionInfo[module] = projectVersion;
      }

  });

  console.log(modulesVersionInfo);

  var version_skew_info = {
    "test" : {
      "project1" : [["ver1"], ["ver2"]],
      "project2" : [["ver1"], ["ver2"], ["ver3"], ["ver4"]],
      "project3" : [["ver1"], ["ver2"], ["ver5"], ["ver6"]],
      "project4" : [["ver1"], ["ver2"]],
    },

    "test2" : {
      "project1" : [["ver1"], ["ver2"]],
      "project2" : [["ver1"], ["ver2"], ["ver3"], ["ver4"]],
      "project3" : [["ver1"], ["ver2"], ["ver5"], ["ver6"]],
      "project4" : [["ver7"], ["ver8"]],
    },

    "test3" : {
      "project1" : [["ver1"], ["ver2"]],
      "project2" : [["ver1"], ["ver2"], ["ver3"], ["ver4"]],
      "project6" : [["ver1"], ["ver2"]],
      "project7" : [["ver1"], ["ver2"], ["ver3"], ["ver4"]],
    },
  };




  var tableString = "";

  var num = 1;

  $.each(modulesVersionInfo, function(module, moduleDependencyInfo) {

      var rowsToSpan = 0;

      $.each(moduleDependencyInfo, function(projectName, versionInfo) {
          rowsToSpan += versionInfo['versions'].length;
      });

      tableString = "<tr>";
      tableString += "<td rowspan=\"" + rowsToSpan + "\">" + num + "</td>";
      tableString += "<td rowspan=\"" + rowsToSpan + "\">" + module + "</td>";
      num++;

      var once = 1;
      $.each(moduleDependencyInfo, function(projectName, versionInfo) {

          if (once===1) {
            AllProjects = "<td rowspan=\""+ versionInfo['versions'].length +"\">" + projectName + "</td>";
          } else{
            AllProjects = "<tr><td rowspan=\""+ versionInfo['versions'].length +"\">" + projectName + "</td>";          
          }
          tableString += AllProjects;

          for (var i = versionInfo['versions'].length - 1; i >= 0; i--) {
            //detailed version info
  //          for (var j = versionInfo[i].length - 1; j >= 0; j--) {
              console.log(versionInfo['versions'][i]);
              if(i==versionInfo['versions'].length-1 && once===1){
                AllVersions = "<td>" + versionInfo['versions'][i] + "</td>";
                AllVersions += "<td>" + versionInfo['paths'][i] + "</td>";
                AllVersions += "</tr>";//ending the very first row
                once=0;
              } else if (i==versionInfo['versions'].length-1){
                AllVersions = "<td>" + versionInfo['versions'][i] + "</td>";              
                AllVersions += "<td>" + versionInfo['paths'][i] + "</td>";
                AllVersions += "</tr>";              
              } else {
                AllVersions = "<tr>";
                AllVersions += "<td>" + versionInfo['versions'][i] + "</td>"
                AllVersions += "<td>" + versionInfo['paths'][i] + "</td>"
                AllVersions += "</tr>";              
              }
              tableString += AllVersions;
  //          };
          };

      });


      // tableString += "</tr>";
      $('#version_skew_table > tbody:last-child').append(tableString);

  });

});


</script>


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

<script type="text/javascript">

  var curDivType = window.location.hash.substr(1);

  $(".singleElementDisplay").css({'display' : 'none'});

  if(curDivType==="" && curDivType){
    curDivType = "dependency"
  }

  $("#"+curDivType).css({'display' : 'block'});

  function changeDiv ( changeDivTo ){
    // alert(changeDivTo + " " + curDivType);
    $("#"+curDivType).css({'display': 'none'});
    $("#"+changeDivTo).css({'display': 'block'});
    curDivType = changeDivTo;
  }

</script>



</body>
</html>
