<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html> 
<head> 
<script> 
 
function toggleLineNos(name) {
 
    if (document.getElementById(name + "lines").style.display == "none") {
 
        document.getElementById(name + "lines").style.display = "";
        document.getElementById(name + "nolines").style.display = "none";
    } else {
        document.getElementById(name + "lines").style.display = "none";
        document.getElementById(name + "nolines").style.display = "";
 
    }
 
}
function toggleList() {
    if (document.getElementById("list").style.display == "none") {   
        document.getElementById("list").style.display = "";
        document.getElementById("showbtn").innerHTML = "Hide Index";
    } else {
        document.getElementById("list").style.display = "none";
        document.getElementById("showbtn").innerHTML = "Show Index";
    }
}
</script> 
<link href="http://comp1917.ripenetwork.com/sections.css" rel="stylesheet"  type="text/css"/>  
<title><?php echo $page_title; ?></title> 
</head> 
<body> 
 
<h1><?php echo $chapter_title; ?></h1> 
<?php 
echo $content_for_layout;
?>
 
<div id="header" style="position:absolute;left:10px;width:auto;top:0px;height:auto;text-align:left;font-family:monospace;font-size:20px;background-color:rgba(255, 255, 255, 0.95);padding:0px;margin:0px;margin-left:20px;padding-left:20px;padding-right:20px;padding-bottom:10px;"> 
<a href="../">back</a> 
</div> 
<div style="position:absolute;left:100px;width:auto;top:0px;height:auto;text-align:left;font-family:monospace;font-size:20px;background-color:rgba(255, 255, 255, 0.95);padding:0px;margin:0px;margin-left:20px;padding-left:20px;padding-right:20px;padding-bottom:10px;"> 
<a id="showbtn" href="javascript:toggleList();">Table of Contents</a> 
</div> 
 
<div id="list" style="position:absolute;left:100px;width:auto;top:40px;height:auto;text-align:left;font-family:monospace;font-size:20px;background-color:rgba(255, 255, 255, 0.95);padding:0px;margin:0px;margin-left:20px;padding-left:20px;padding-right:20px;padding-bottom:10px;display:none;"> 

<?php 
foreach ($table_of_contents as $id => $chapter){
    echo "<a href='/chapters/".$chapter['Chapter']['link']."'>".$chapter['Chapter']['name']."</a><br/>";
}
?>
</div> 
 
<div style="position:relative;height:40px;width:100%;font-family:monospace;text-align:center;"> 
Copyright &copy; 2010 Stephen Sherratt
</div> 
</body> 
</html> 
