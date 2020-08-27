<?PHP
include "../pcdl/html_head.php";
?>
<body>

<?php
    require_once("../pcdl/head_bar.php");
?>

<style>
    #main_video_win iframe{
        width:100%;
        height:100%;
    }
    #main_tag span{
        margin: 2px;
    padding: 2px 12px;
    font-weight: 500;
    transition-duration: 0.2s;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    flex-wrap: nowrap;
    justify-content: center;
        font-size:110%;
        border: unset;
        border-radius: 0;
        border-bottom: 2px solid var(--nocolor);
    }
    #main_tag span:hover{
        background-color:unset;
        color:unset;
        border-color: var(--link-hover-color);
    }
    #main_tag .select{
        border-bottom: 2px solid var(--link-color);
    }
</style>
<?php
//

require_once "../path.php";
require_once "../public/_pdo.php";
require_once '../media/function.php';
require_once '../public/function.php';



echo "<div id='course_head_bar' style='background-color:var(--tool-bg-color1);padding:1em 10px 10px 10px;'>";
echo "<div class='index_inner '>";
echo "<div style='font-size:140%'>";
echo "</div>";
echo '<div id="main_tag"  style="">';
echo '<span tag="sutta">Sutta</span><span tag="vinaya">Vinaya</span><span tag="abhidhamma">Abhidhamma</span>';
echo '<span tag="mula">Mula</span><span tag="atthakatha">Aṭṭhakathā</span><span tag="tika">ṭīkā</span><span tag="anna">anna</span>';
echo '</div>';
echo '<div id="tag_selected" class="summary"  style="padding-bottom:5px;"></div>';
echo '<div id="tag_others" class="summary"  style="padding-bottom:5px;"></div>';
echo "</div>";
echo '</div>';
?>
<div id ="book_list" class='index_inner' style='display: flex;flex-wrap: wrap;'>

</div>

<script>
    var main_tag="";
    var list_tag=new Array();
    

    $("span[tag]").click(function(){
        $(this).siblings().removeClass("select");
        $(this).addClass("select");
        main_tag = $(this).attr("tag");
        list_tag=new Array();
        tag_changed();
        render_tag_list();
        /*
        const tagSelected = document.getElementsByClassName("select");
        if(tagSelected){
            let tagList = new Array();
            for (const iterator of tagSelected) {
                tagList.push(iterator.getAttributeNode("tag").value);
            }
            main_tag = tagList[0];

            tag_changed();
        }
        */
  });

  function tag_changed(){
    let strTags = "";
      if(list_tag.length>0){
        strTags = main_tag + "," + list_tag.join();
      }
      else{
        strTags = main_tag;
      }
    console.log(strTags);
    $.get("book_tag.php",
            {
                tag:strTags
            },
            function(data,status){
                let arrBookList = JSON.parse(data);
                let html="";
                let allTags = new Array();
                for (const iterator of arrBookList) {
                    html += "<div style='width:25%;padding:0.5em;'>";
                    html += "<div class='card' style='padding:10px;'>";
                    html += "<div style='font-weight:700'>"+iterator[0].title+"</div>";
                    html += "</div>";
                    html += "</div>";
                    let tags = iterator[0].tag.split("::");
                    let currTag = new Array();
                    currTag[main_tag] = 1;
                    for (const scondTag of list_tag) {
                        currTag[scondTag]  = 1;
                    }
                    for (let tag of tags) {
                        if(tag.slice(0,1)==":"){
                            tag = tag.slice(1);
                        }
                        if(tag.slice(-1)==":"){
                            tag = tag.slice(0,-1);
                        }

                        if(!currTag.hasOwnProperty(tag)){
                            if(allTags.hasOwnProperty(tag) ){
                                allTags[tag] += 1;
                            }
                            else{
                                allTags[tag] = 1;
                            } 
                        }
                    }
                }
                $("#book_list").html(html);
                let strOthersTag = "";
                for (const key in allTags) {
                    if (allTags.hasOwnProperty(key)) {
                        strOthersTag += "<button onclick =\"tag_click('"+key+"')\" >"+key+"</button>";
                    }
                }
                $("#tag_others").html(strOthersTag);
            });
  }
  function tag_click(tag){
    list_tag.push(tag);
    render_tag_list();
    let strTag = main_tag + "," + list_tag.join();
    tag_changed();
  }

  function render_tag_list(){
    let strListTag = "已经选择：";
      for (const iterator of list_tag) {
        strListTag +="<button >"+iterator+"<span onclick =\"tag_remove('"+iterator+"')\">X</span></button>";
      }
      $("#tag_selected").html(strListTag);
  }

  function tag_remove(tag){
      for(let i=0; i<list_tag.length;i++){
          if(list_tag[i]==tag){
              list_tag.splice(i,1);
          }
      }
      render_tag_list();
      tag_changed();
  }
</script>
<?php
include "../pcdl/html_foot.php";
?>