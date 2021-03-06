<?php
require_once "../public/load_lang.php";
require_once "../path.php";
require_once "../pcdl/html_head.php";
if(isset($_GET["id"])){
	$_get_id=$_GET["id"];
}
if(isset($_GET["word"])){
	$_get_word=$_GET["word"];
}
if(isset($_GET["author"])){
	$_get_author=$_GET["author"];
}
else{
	$_get_author="";
}
?>

<body style="margin: 0;padding: 0;" class="reader_body" onload="<?php
if(isset($_get_id)){
echo "wiki_load_id('{$_get_id}')";
}
else if(isset($_get_word)){
echo "wiki_load_word('{$_get_word}')";
}
?>">
	<script src="../term/term.js"></script>
	<script src="../term/note.js"></script>
	<script src="wiki.js"></script>
	<script>
	<?php
	if(isset($_GET["word"])){
		echo "_word='".$_GET["word"]."';";
	}
	if(isset($_GET["channal"])){
		echo "_channal='".$_GET["channal"]."';";
	}
	if(isset($_GET["lang"])){
		echo "_lang='".$_GET["lang"]."';";
	}
	if(isset($_GET["author"])){
		echo "_author='".$_GET["author"]."';";
	}
	?>
	</script>
	<style>
	.term_link,.term_link_new{
		color: blue;
		padding-left: 2px;
		padding-right: 2px;
	}
	.term_link_new{
		color:red;
	}
	#search_result{
		position: absolute;
		background: wheat;
		max-width: 95%;
		width: 24em;
	}
	note:hover chapter{
		display:inline;
	} 
	.ref>chapter:first-child{
		display:inline;
	}
	chapter{
		display:none;
		color: var(--box-bg-color1);
		text-decoration: none;
		cursor: pointer;
	}
	chapter:hover{
		color: var(--link-color);
		text-decoration: underline;
	}
	para{
		background-color: var(--drop-bg-color);
		padding: 2px 8px;
		text-decoration: none;
		cursor: pointer;
		color: var(--btn-border-color);
		border-radius: 5px;
	}
	para:hover{
		text-decoration: underline;
	}
	.icon{
		width: 15px;
		height: 15px;
	}
	.submenu_title{
		font-size: 120%;
		font-weight: 700;		
	}
	.term_word_head_pali {
		text-transform: capitalize;
		font-size: 200%;
		margin: 0.5em 0;
	}
	.term_word_head{
		border-bottom: 1px solid #cecece;
		padding: 5px 0;
	}
	.term_block{
		border-bottom: 1px solid #cecece;
		padding: 5px 0;
	}
	.term_word_head_authors a{
		color: blue;
		margin: 0 3px;
	}
	.term_word_head_authors a:hover{
		text-decoration: underline;
		cursor: pointer;
	}

	note .ref{
		text-align: right;
		padding: 5px;
		font-size: 75%;
		margin-top: 8px;
	}
	note{
		background-color: #80808014;
		padding: 0.5em 0.8em;
		margin-bottom: 0.4em;
		border-radius: 5px;
		display:block;
	}
	note>.tran{
		color: #5c5c5c;
		padding-left: 1em;
	}
	note>.palitext{
		font-family: Noto serif;
		line-height: 1.5em;
		color: #9f3a01;
		font-weight: 500;
	}
	note>.palitext>note{
		display:inline;
		color:blue;
		background-color: unset;
		padding: unset;
		margin-bottom: unset;
		border-radius: unset;
	}
	.term_block_bar {
		display: flex;
		justify-content: space-between;
	}
	#head_bar{
		display: flex;
		justify-content: space-between;
		/*height: 5em;*/
		background-color: var(--bookx);
		border-bottom: 1px solid var(--tool-line-color);
		margin-top: 50px;
	}
	.term_block_bar_left{
		display: flex;
	}
	.term_block_bar_left_icon{
    display: inline-block;
    width: 1.5em;
    text-align: center;
    height: 1.5em;
    background-color: gray;
    font-size: 180%;
    color: white;
    border-radius: 99px;
	}
	.term_block_bar_left_info{
		    padding-left: 8px;
	}
	.term_meaning{
		font-weight: 700;
	}
	.term_author{
		font-size: 80%;
		color: gray;
	}
	.term_tag{
		font-size: 80%;
		font-weight: 500;
		margin: 0 8px;
	}
	.term_link {
    cursor: pointer;
	}
	#wiki_contents{
		padding: 0 1em;
		max-width: 1280px;
		margin-left: auto;
		margin-right: auto;
	}
	#term_list_right{
		width: 25em;
	}
	#term_list{
		width: 100%;
		padding: 0.5em;
	}
	#term_list_div{
		display: flex;
		justify-content: space-between;
	}
	.fun_frame {
		border-bottom: 1px solid gray;
		margin-right: 10px;
		margin-bottom: 10px;
	}
	.fun_frame>.title{
		padding:6px;
		font-weight: 700;
	}
	.fun_frame>.content{
		padding:6px;
		max-height:6em;
		overflow-y: scroll;
	}
	
	.fixed{
		position:fixed;
		right: 0;
    	top: 0;
	}
	.when_right_fixed{
		padding-right:20em;
	}
	.bg_color_1{
		background-color:#ebebeb66;
	}
	.bg_color_2{
		background:linear-gradient(to right, #6afdb033, #ebebeb66);
	}
	.bg_color_3{
		background:linear-gradient(to right, #6a95fd26, #ebebeb66);
	}
	.bg_color_4{
		background:linear-gradient(to right, #f9e7911c, #ebebeb66);
	}
	.bg_color_5{
		background:linear-gradient(to right, #fe99b91c, #ebebeb66);
	}

	pre {
		white-space: pre-line;
		font-family: auto;
		border-left: 3px solid var(--border-shadow);
		margin-left: 1em;
		padding-left: 0.5em;
	}
	</style>

<style media="screen and (max-width:800px)">
#term_list_right{
	display:none;
}
.when_right_fixed{
	padding-right:0;
}
</style>

<script>


term_word_link_fun("wiki_goto_word");
</script>
<style>
	.index_toolbar{
		position:unset;
	}
	#pali_pedia{
		font-size: 200%;
    margin-top: auto;
    margin-bottom: auto;
    padding-left: 0.5em;
	}

	
</style>

<?php
    require_once("../pcdl/head_bar.php");
?>
<div id="head_bar" >
	<div id="pali_pedia" style="display:flex;width: 100%;">
		<span style="margin: auto 1em auto 0;"><?php echo $_local->gui->wiki_term; ?></span>
		<span id="wiki_search" style="width:35vw;margin: auto 0em;font-size: large;">
			<span style="display:block;">
				<input id="wiki_search_input" type="input" placeholder=<?php echo $_local->gui->search; ?> style="width:30vw;background-color: var(--btn-color);border: 1px solid var(--btn-border-color);border-radius: 99px;padding: 3px 15px;"  onkeyup="wiki_search_keyup(event,this)"/>
			</span>
			<span id="search_result">
			</span>
		</span>	
		<span style="font-size: medium; margin: auto 1em auto auto;">
			<button class="icon_btn"><a href="#"><?php echo $_local->gui->setting; ?></a></button>
			<button class="icon_btn"><a href="#"><?php echo $_local->gui->new; ?></a></button>
			<button class="icon_btn"><a href="#"><?php echo $_local->gui->help; ?></a></button>
		</span>
	</div>
</div>

<div id="wiki_contents" style="padding: 0 1em;">
	<?php echo $_local->gui->loading; ?>...
</div>
<script>
	 window.addEventListener('scroll',winScroll);
	function winScroll(e){ 
		if(GetPageScroll().y>220){

		}
		else{

		}
		
	}
 //滚动条位置
function GetPageScroll() 
{ 
	var pos=new Object();
	var x, y; 
	if(window.pageYOffset) 
	{	// all except IE	
		y = window.pageYOffset;	
		x = window.pageXOffset; 
	} else if(document.documentElement && document.documentElement.scrollTop) 
	{	// IE 6 Strict	
		y = document.documentElement.scrollTop;	
		x = document.documentElement.scrollLeft; 
	} else if(document.body) {	// all other IE	
		y = document.body.scrollTop;	
		x = document.body.scrollLeft;   
	} 
	pos.x=x;
	pos.y=y;
	return(pos);
}
	</script>

</body>
</html>