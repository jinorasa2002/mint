var _articel_id = "";
var _channal = "";
var _lang = "";
var _author = "";
var _display = "";
var _collect_id = "";

function article_onload() {
	historay_init();
}
function articel_load(id) {
	if (id == "") {
		return;
	}
	$.get(
		"../article/get.php",
		{
			id: id,
			setting: "",
		},
		function (data, status) {
			if (status == "success") {
				try {
					let result = JSON.parse(data);
					if (result) {
						$("#article_title").html(result.title);
						$("#page_title").text(result.title);
						$("#article_subtitle").html(result.subtitle);
						$("#article_author").html(result.username.nickname + "@" + result.username.username);
						$("#contents").html(note_init(result.content));
						note_refresh_new();
					}
				} catch (e) {
					console.error(e);
				}
			} else {
				console.error("ajex error");
			}
		}
	);
}

function collect_load(id) {
	if (id == "") {
		return;
	}
	$.get(
		"../article/collect_get.php",
		{
			id: id,
			setting: "",
		},
		function (data, status) {
			if (status == "success") {
				try {
					let result = JSON.parse(data);
					if (result) {
						$("#article_title").html(result.title);
						$("#page_title").text(result.title);
						if (result.subtitle) {
							$("#article_subtitle").html(result.subtitle);
						}
						$("#article_author").html(result.username.nickname + "@" + result.username.username);
						$("#contents").html(marked(result.summary));

						let article_list = JSON.parse(result.article_list);
						render_article_list(article_list);
					}
				} catch (e) {
					console.error(e);
				}
			} else {
				console.error("ajex error");
			}
		}
	);
}

function articel_load_collect(article_id) {
	$.get(
		"../article/collect_get.php",
		{
			article: article_id,
			setting: "",
		},
		function (data, status) {
			if (status == "success") {
				try {
					let result = JSON.parse(data);
					if (result && result.length > 0) {
						//$("#collect_title").html(result[0].title);
						let strTitle = "<a href='../article/?collect=" + result[0].id + "'>" + result[0].title + "</a>";
						$("#pali_pedia").html(strTitle);

						let article_list = JSON.parse(result[0].article_list);
						render_article_list(article_list);
					}
				} catch (e) {
					console.error(e);
				}
			} else {
				console.error("ajex error");
			}
		}
	);
}

function render_article_list(article_list) {
	let html = "";
	html += "<ul>";
	let display = "";
	if (_display == "para") {
		display = "&display=para";
	}
	let prevArticle = "无";
	let nextArticle = "无";
	for (let index = 0; index < article_list.length; index++) {
		const element = article_list[index];
		if (element.article == _articel_id) {
			if (index > 0) {
				const prev = article_list[index - 1];
				prevArticle = "<a href='../article/index.php?id=" + prev.article + display + "'>" + prev.title + "</a>";
			}
			if (index < article_list.length - 1) {
				const next = article_list[index + 1];
				nextArticle = "<a href='../article/index.php?id=" + next.article + display + "'>" + next.title + "</a>";
			}
			$("#contents_nav_left").html(prevArticle);
			$("#contents_nav_right").html(nextArticle);
		}
		html +=
			"<li class='level_" +
			element.level +
			"'>" +
			"<a href='../article/index.php?id=" +
			element.article +
			display +
			"'>" +
			element.title +
			"</a></li>";
	}

	html += "</ul>";

	$("#toc_content").html(html);
}

function set_channal(channalid) {
	let url = "../article/index.php?id=" + _articel_id;
	if (channalid != "") {
		url += "&channal=" + channalid;
	}
	if (_display != "") {
		url += "&display=" + _display;
	}
	location.assign(url);
}
