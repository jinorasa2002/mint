var _my_channal = null;
var get_channel_list_callback = null;
channal_list();

function group_list_init() {
	if (typeof gGroupId == "undefined") {
		my_group_list();
		group_add_dlg_init("group_add_div");
		$("#button_new_group").show();
	} else {
		group_list(gGroupId, gList);
		team_add_dlg_init("sub_group_add_div");
		$("#member_list_shell").css("visibility", "visible");
		member_list(gGroupId);
		//初始化用户选择对话框
		user_select_dlg_init("user_select_div");
	}
}
function channal_list() {
	$.post("../channal/get.php", {}, function (data) {
		try {
			_my_channal = JSON.parse(data);
			if (get_channel_list_callback) {
				get_channel_list_callback();
			}
		} catch (e) {
			console.error(e);
		}
	});
}

function channal_getById(id) {
	for (const iterator of _my_channal) {
		if (iterator.id == id) {
			return iterator;
		}
	}
	return false;
}

function my_group_list() {
	$.get("../group/list.php", {}, function (data, status) {
		if (status == "success") {
			try {
				let html = "";
				let result = JSON.parse(data);
				let key = 1;
				if (result.length > 0) {
					for (const iterator of result) {
						html += '<div class="file_list_row" style="padding:5px;">';
						html += "<div style='flex:1;'>" + key++ + "</div>";
						html += "<div style='flex:2;'>" + iterator.group_name + "</div>";
						html += "<div style='flex:2;'>";
						switch (parseInt(iterator.power)) {
							case 0:
								html += gLocal.gui.owner;
								break;
							case 1:
								html += gLocal.gui.manager;
								break;
							case 2:
								html += gLocal.gui.member;
								break;
							default:
								break;
						}
						html += "</div>";
						html +=
							"<div style='flex:1;'><a href='../group/index.php?id=" +
							iterator.group_id +
							"'>" +
							gLocal.gui.enter +
							"</a></div>";
						html += "<div style='flex:1;'><div class='hover_button'>";
						if (parseInt(iterator.power) == 0) {
							//管理员可以删除group
							html +=
								"<button onclick=\"group_del('" +
								iterator.group_id +
								"')\">" +
								gLocal.gui.delete +
								"</button>";
						}

						html += "</div></div>";
						html += "</div>";
					}
				} else {
					html += "你没有加入任何工作组 现在 创建 你的工作组。";
				}
				$("#my_group_list").html(html);
			} catch (e) {
				console.error(e);
			}
		} else {
			console.error("ajex error");
		}
	});
}

function group_list(id, list) {
	$.get(
		"../group/get.php",
		{
			id: id,
			list: list,
		},
		function (data, status) {
			if (status == "success") {
				try {
					let html = "";
					let result = JSON.parse(data);
					let key = 1;
					html += "<div class='info_block'>";
					html += "<h2>" + gLocal.gui.introduction + "</h2>";
					html += result.info.description;
					html += "</div>";
					$("#curr_group").html("/" + result.info.name);

					if (result.parent) {
						//如果是project 显示 group名词
						$("#parent_group").html(
							" / <a href='../group/index.php?id=" +
								result.parent.id +
								"'>" +
								result.parent.name +
								"</a> "
						);
					} else {
						if (result.info.creator == getCookie("userid")) {
							$("#button_new_sub_group").show();
						}
						//子小组列表
						html += "<div class='info_block'>";
						html += "<h2>" + gLocal.gui.sub_group + "</h2>";
						if (result.children && result.children.length > 0) {
							for (const iterator of result.children) {
								html += '<div class="file_list_row" style="padding:5px;">';
								html += "<div style='flex:1;'>" + key++ + "</div>";
								html += "<div style='flex:2;'>" + iterator.name + "</div>";
								html += "<div style='flex:2;'>";
								if (iterator.creator == getCookie("userid")) {
									html += gLocal.gui.owner;
								}
								html += "</div>";
								html +=
									"<div style='flex:1;'><a href='../group/index.php?id=" +
									iterator.id +
									"&list=file'>" +
									gLocal.gui.enter +
									"</a></div>";
								html += "<div style='flex:1;'><div class='hover_button'>";
								if (iterator.creator == getCookie("userid")) {
									html +=
										"<button onclick=\"group_del('" +
										iterator.id +
										"')\">" +
										gLocal.gui.delete +
										"</button>";
								}
								html += "</div></div>";
								html += "</div>";
							}
						} else {
							html += "尚未设置小组";
						}
						html += "</div>";
					}

					//共享文件列表
					key = 1;
					html += "<div class='info_block'>";
					html += "<h2>" + gLocal.gui.collaborate + "</h2>";
					if (result.file && result.file.length > 0) {
						for (const iterator of result.file) {
							html += '<div class="file_list_row" style="padding:5px;">';
							html += "<div style='flex:1;'>" + key++ + "</div>";
							html += "<div style='flex:2;'>" + iterator.title + "</div>";
							html += "<div style='flex:2;'>";
							switch (iterator.power) {
								case 10:
									html += gLocal.gui.read_only;
									break;
								case 20:
									break;
								case 30:
									html += gLocal.gui.write;
									break;
								default:
									break;
							}
							html += "</div>";
							html +=
								"<div style='flex:1;'><a href='../studio/project.php?op=open&doc_id=" +
								iterator.doc_id +
								"'>打开</a></div>";
							html += "</div>";
						}
					} else {
						html += "没有共享文档 在译经楼中添加";
					}

					html += "</div>";

					$("#my_group_list").html(html);
				} catch (e) {
					console.error(e);
				}
			} else {
				console.error("ajex error");
			}
		}
	);
}

function member_list(id) {
	$.get(
		"../group/list_member.php",
		{
			id: id,
		},
		function (data, status) {
			if (status == "success") {
				try {
					let html = "";
					let result = JSON.parse(data);
					$("#member_number").html("(" + result.length + ")");
					//人员
					html += "<div class='info_block'>";
					if (result && result.length > 0) {
						for (const iterator of result) {
							html += '<div class="file_list_row" style="padding:5px;">';
							html += "<div style='flex:2;'>" + iterator.user_info.nickname + "</div>";
							html += "<div style='flex:2;'>";
							if (iterator.power == 1) {
								html += "拥有者";
							}
							html += "</div>";
							html += "<div style='flex:1;'><div class='hover_button'>";
							//if (iterator.creator == getCookie("userid"))
							{
								html +=
									"<button onclick=\"member_del('" +
									id +
									"','" +
									iterator.user_id +
									"')\">" +
									gLocal.gui.delete +
									"</button>";
							}
							html += "</div></div>";
							html += "</div>";
						}
					} else {
						html += "这是一个安静的地方";
					}
					html += "</div>";

					$("#member_list").html(html);
				} catch (e) {
					console.error(e);
				}
			} else {
				console.error("ajex error");
			}
		}
	);
}

function user_selected(id) {
	$.post(
		"../group/member_put.php",
		{
			userid: id,
			groupid: gGroupId,
		},
		function (data) {
			let error = JSON.parse(data);
			if (error.status == 0) {
				user_select_cancel();
				alert("ok");
				location.reload();
			} else {
				user_select_cancel();
				alert(error.message);
			}
		}
	);
}

function user_select_new() {
	$.post(
		"../group/my_group_put.php",
		{
			name: $("#user_select_title").val(),
			parent: parentid,
		},
		function (data) {
			let error = JSON.parse(data);
			if (error.status == 0) {
				alert("ok");
				user_select_cancel();
				location.reload();
			} else {
				alert(error.message);
			}
		}
	);
}

function group_del(group_id) {
	if (
		confirm(
			"此操作将删除组/项目。\n以及切断分享到此组/项目文件的链接。\n但是不会删除该文件。\n此操作不能恢复。仍然要删除吗!"
		)
	) {
		$.post(
			"../group/group_del.php",
			{
				groupid: group_id,
			},
			function (data) {
				let error = JSON.parse(data);
				if (error.status == 0) {
					alert("ok");
					location.reload();
				} else {
					alert(error.message);
				}
			}
		);
	}
}

function member_del(group_id, user_id) {
	if (confirm("此操作将移除成员。\n此操作不能恢复。仍然要移除吗!")) {
		$.post(
			"../group/member_del.php",
			{
				groupid: group_id,
				userid: user_id,
			},
			function (data) {
				let error = JSON.parse(data);
				if (error.status == 0) {
					alert("ok");
					location.reload();
				} else {
					alert(error.message);
				}
			}
		);
	}
}
