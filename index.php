<?php
$remove_biggests = isset($_GET['rb']);
?>
<!DOCTYPE html>
<meta charset="utf-8">
<title>Turkey Internet Map - Turkey top sites</title>
<style>
*{font-family : Calibri,Arial }
g{font-size:10px;}
#headline{font-size:12px;color:#232323;}
.tipsy { font-size: 18px!important; position: absolute; padding: 5px; z-index: 100000; }
.tipsy-inner { background-color: #000; color: #FFF; max-width: 200px; padding: 5px 8px 4px 8px; text-align: center; 
  border-radius: 3px; -moz-border-radius: 3px; -webkit-border-radius: 3px; }
.tipsy-arrow { position: absolute; width: 0; height: 0; line-height: 0; border: 5px dashed #000; }
.tipsy-arrow-n { border-bottom-color: #000; }
.tipsy-n .tipsy-arrow { top: 0px; left: 50%; margin-left: -5px; border-bottom-style: solid; border-top: none; border-left-color: transparent; border-right-color: transparent; }
</style>
<body>
<div id="headline">Turkey Internet Map <a href="https://github.com/hasantayyar/turkey-internet">Source on Github</a></div>
<br><small><a href="/?rb=1">Remove giants</a> : "google.com", "blogger.com" , "facebook.com" ,"twitter.com"
</small>
<div id="chart"></div>
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://rawgit.com/jaz303/tipsy/master/src/javascripts/jquery.tipsy.js"></script>
<script src="http://d3js.org/d3.v3.min.js"></script>

<script> 

function getRandomColor() { 
    color = "rgb("+ Math.floor(Math.random() * 200)+","+ Math.floor(Math.random() * 200)+","+ Math.floor(Math.random() * 255)+")";
    console.log(color);
    return color;
}

var diameter = 960,
    format = d3.format(",d"),
    color = d3.scale.category20c();

var bubble = d3.layout.pack()
    .sort(null)
    .size([diameter, diameter])
    .padding(1.5);

var svg = d3.select("#chart").append("svg")
    .attr("width", diameter)
    .attr("height", diameter)
    .attr("class", "bubble");

var show_details =  function(data, element) {
    var content;
    d3.select(element).attr("stroke", "black");
    content = "<span class=\"name\">Website :</span><span class=\"value\"> "+data+" </span><br/>";
      return tooltip.showTooltip(content, d3.event);
  }; 
var hide_details =  function(data, element) {
    d3.select(element).attr("stroke", function(d) {
      return d3.rgb(_this.fill_color(d.group)).darker();
    });
    return tooltip.hideTooltip();
  };  
d3.json("site_turkey<?php echo $remove_biggests?"_alt":"";?>.json", function(error, root) {
  var node = svg.selectAll(".node")
      .data(bubble.nodes(classes(root))
      .filter(function(d) { return !d.children; }))
    .enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

  node.append("circle")
      .attr("style","cursor:pointer")
      .attr("r", function(d) { return d.r; })
      .style("fill", function(d) { return getRandomColor(); });

  node.append("text")
      .attr("dy", ".3em")
      .attr("title",function(d) { return d.className ; })
      .attr("style","cursor:pointer")
      .style("text-anchor", "middle")
      .text(function(d) { return d.className.substring(0, d.r / 3); });

  node.on("mouseover",function(){  }); 
  node.on("click",function(d){
	// $("#desc").slideDown(); $("#desc").html(""+d.className);
	});
  //$('circle').tipsy( );
  $('text').tipsy( );
});


// Returns a flattened hierarchy containing all leaf nodes under the root.
function classes(root) {
  var classes = [];

  function recurse(name, node) {
    if (node.children) node.children.forEach(function(child) { recurse(node.name, child); });
    else classes.push({packageName: name, className: node.name, value: 100000/node.size});
  }

  recurse(null, root);
  return {children: classes};
}

d3.select(self.frameElement).style("height", diameter + "px");


</script>
