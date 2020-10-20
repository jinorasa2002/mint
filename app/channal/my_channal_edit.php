<?php
require_once '../studio/index_head.php';
?>
<body id="file_list_body" >

	<script language="javascript" src="../channal/channal.js"></script>
	<script language="javascript" src="../public/js/marked.js"></script>
	<script src="../public/js/jquery-ui-1.12.1/jquery-ui.js"></script>
	<link type="text/css" rel="stylesheet" href="../public/js/jquery-ui-1.12.1/jquery-ui.css"/>
	<script language="javascript" src="../lang/tran_lang_select.js"></script>

	<script >
	var gCurrPage="channal";
	</script>

	<style>
	#channal {
		background-color: var(--btn-border-color);
		
	}
	#channal:hover{
		background-color: var(--btn-border-color);
		color: var(--btn-color);
		cursor:auto;
	}
	.file_list_block{
		width:unset;
	}
	.case_dropdown-content>a {
		display:block;
	}
	.case_dropdown-content>.active{
		background-color:gray;
	}
	.file_list_block {
    max-width: 100%;
    margin-right: 1em;
	}
	.index_inner {
    margin-left: 16em;
    margin-top: 5em;
}
#preview_div {
    flex: 6;
    overflow-y: scroll;
    height: 550px;
}

	</style>

	<?php
	require_once '../studio/index_tool_bar.php';
	?>
		
	<div class="index_inner " >
	<form id="channal_edit" action="##" onsubmit="return false"  method="POST" >
	<div class="file_list_block">
		<div class="tool_bar">
			<div style="display:flex;">
				<a href="../channal/my_channal_index.php">返回</a>
				<span id="channal_title"></span>
			</div>
			<div style="display:flex;">
				<div id="aritcle_status"></div>
				<span class="icon_btn_div">
					<span class="icon_btn_tip">保存</span>
					<button id="edit_save" type="button" class="icon_btn" title=" " onclick="my_channal_save()">
						<svg class="icon">
							<use xlink:href="../studio/svg/icon.svg#ic_save"></use>
						</svg>
					</button>
				</span>
				
				<span class="icon_btn_div">				
					<span class="icon_btn_tip">回收站</span>
					<button id="to_recycle" type="button" class="icon_btn" onclick="channal_del()" title=" ">
						<svg class="icon">
							<use xlink:href="../studio/svg/icon.svg#ic_delete"></use>
						</svg>
					</button>
				</span>	
			</div>
		</div>

		<div id="channal_info"  class="file_list_block" style="">

		</div>

	</div>
</form>
	</div>
	
<script>
my_channal_edit("<?php echo $_GET["id"] ?>");
</script>
<?php
require_once '../studio/index_foot.php';
?>
