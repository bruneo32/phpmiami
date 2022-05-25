var cardHTML = '<div style="position:relative;right:0;transition: 0.8s;"><div class="closebtn" title="Remove this card" onclick="RemoveCard(this)">x</div><div class="card"><table><tr><td>SQL</td><td><textarea></textarea><br><select class="button" title="Copy a snippet to clipboard" onchange="CopySnippet(this)"></select><input type="button" style="margin:0 12px;display:none;float:right;" class="button" value="Rerun query" data-cx="0" onclick="this.style.display=\'none\';ExecQuery(this.previousSibling.previousSibling.previousSibling.previousSibling.value,this.parentElement.parentElement.parentElement.lastChild.previousSibling.lastChild,this.parentElement.parentElement.parentElement.lastChild.lastChild, false);;"/><input type="button" style="float:right;" title="CTRL+ENTER" class="button" value="Run query" onclick="this.style.display=\'none\';RunQuery()"/></td></tr><tr class="result_query"><td>Result</td><td></td></tr><tr class="result_query raw"><td onclick="SelectNext(this,1)">Raw result</td><td></td></tr></table></div></div>';
var defaults = {
	lineNumbers: true,
	matchBrackets: true,
	extraKeys: {
		'Ctrl-Space': 'autocomplete',
		'Ctrl-Enter': RunQuery
	},
	hintOptions: { 'completeSingle': false, 'completeOnSingleClick': true, tables:[]},
	indentUnit: 4,
	mode: 'text/x-mysql',
	lineWrapping: true,
	hintOptions: {},
	theme:"default"
};
var snippets=[];
var snippetsHTML="";
var G_TEXTAREA=null;

var xmlhttp = new XMLHttpRequest();

function RemoveCard(btn_elem){
	var wrap_card = btn_elem.parentElement;
	wrap_card.style.position = "absolute";
	wrap_card.style.right = "calc(100% - 16px)";
	wrap_card.children[1].style.opacity = "0";

	btn_elem.innerHTML=">";
	btn_elem.title="Reopen this card";
	btn_elem.setAttribute("onclick","ReopenCard(this)");
}
function ReopenCard(btn_elem){
	var wrap_card = btn_elem.parentElement;
	wrap_card.style.position = "relative";
	wrap_card.style.right = "0";
	wrap_card.children[1].style.opacity = "1";

	btn_elem.innerHTML="x";
	btn_elem.title="Remove this card";
	btn_elem.setAttribute("onclick","RemoveCard(this)");
}

function CopyBtn(btn_elem){
	var textarea = btn_elem.parentElement.children[0];
	if(textarea.value !== undefined){
		copyTextToClipboard(textarea.value);
	}else{
		copyTextToClipboard(textarea.innerHTML);
	}
}
function CopySnippet(this_elm){
	if(this_elm.value == 0){
		location = "snippets";
		this_elm.selectedIndex=0;
		return;
	}

	copyTextToClipboard(snippets[this_elm.value][1]);
	//this_elm.selectedIndex=0;
}
function SelectNext(btn_elem,ind){
	var elm = btn_elem.parentElement.children[ind];
	selectText(elm);
}

var aloaderg = null;
function RunQuery(){
	var table = G_TEXTAREA.parentElement.parentElement.parentElement;
	var val = window.editor.getValue();

	G_TEXTAREA.setAttribute("readonly","true");
	table.parentElement.style.backgroundColor="var(--color2)";
	G_TEXTAREA.parentElement.lastChild.setAttribute("onclick","CopyBtn(this)");
	G_TEXTAREA.parentElement.lastChild.value="Copy";

	// Copy Snippets disabled
	G_TEXTAREA.parentElement.lastChild.previousSibling.previousSibling.previousSibling.setAttribute("disabled", true);

	ExecQuery(val,table.children[1].children[1],table.children[2].children[1]);

	// Set the textarea for the COPY
	G_TEXTAREA.value=val;
	G_TEXTAREA.innerHTML=val;
}

function InstantiateCard(){
	document.getElementById("main").innerHTML+=cardHTML;

	G_TEXTAREA = document.querySelector("#main>div:last-child textarea");
	window.editor = CodeMirror.fromTextArea(G_TEXTAREA, defaults);

	if(snippetsHTML==""){ // Never loaded snippets.txt
		G_TEXTAREA.parentElement.lastChild.previousSibling.previousSibling.style.display="none";
		GetSnippets();

		// Wait 333 milliseconds.
		setTimeout(function () {
			if(snippetsHTML!=""){
				G_TEXTAREA.parentElement.lastChild.previousSibling.previousSibling.innerHTML = snippetsHTML;
				G_TEXTAREA.parentElement.lastChild.previousSibling.previousSibling.style.display="inline";
			}
		}, 333);
	}else{
		G_TEXTAREA.parentElement.lastChild.previousSibling.previousSibling.innerHTML = snippetsHTML;
	}

	AutocompletChanged();
	GetTableHint();
	ScrollBottom();
}

function ExecQuery(sqlstr,resulte,rawr,instantiateCard = true){
	if (sqlstr.length == 0) {
		resulte.parentElement.parentElement.parentElement.parentElement.parentElement.children[0].style.display="block";
		if (instantiateCard) {InstantiateCard();}
	} else {
		sqlstr=sqlstr.replace(new RegExp("\n","g")," ");

		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				resulte.parentElement.parentElement.parentElement.parentElement.parentElement.children[0].style.display="block";

				var res=this.responseText;

				// console.debug("RAW: "+res);

				rawr.innerHTML='<button class="button" onclick="this.parentElement.innerHTML=\''+escapeHtml(escapeHtml(res))+'\'">View</button>';

				resulte.parentElement.style.display="table-row";
				rawr.parentElement.style.display="table-row";

				// Show copy
				rawr.parentElement.parentElement.firstChild.lastChild.lastChild.style.display="inline-block";

				var rrbtn = rawr.parentElement.parentElement.firstChild.lastChild.lastChild.previousSibling;
				var CX = parseInt(rrbtn.getAttribute("data-cx"));
				rrbtn.style.display="inline-block";
				CX++;
				rrbtn.setAttribute("data-cx", CX);
				if (CX > 1) {rrbtn.value= "Rerun query ("+ CX +")";}

				try{
					var resarray = JSON.parse(res);
				} catch(err){
					resulte.innerHTML=res;
					if (instantiateCard) {InstantiateCard();}
					return;
				}
				// console.debug("Parsed: "+resarray);

				var pres="";
				for (var i = 0; i < resarray.length; i++) {
					if(typeof resarray[i]==="boolean" || resarray[i].length==0){
						pres+="<p><i>No result.</i></p>";
					}else if(typeof resarray[i] !== "object"){
						pres+="<p>"+resarray[i]+"</p>";
					}else {
						pres+="<table class=\"supertable\">";

						// Create header
						var table = resarray[i];
						var fr=table[0];
						pres+="<tr>";
						for (var key in fr) {
							pres+="<th>"+key+"</th>";
						}
						pres+="</tr>";

						// Create body
						for (var j = 0; j < table.length; j++) {
							pres+="<tr>";
							var row = table[j];
							for (var key in row) {
								pres+="<td>"+row[key].replace(/\n/g,"<br>")+"</td>";
							}
							pres+="</tr>";
						}

						pres+="</table>";
					}
				}

				resulte.innerHTML=pres;
				if (instantiateCard) {InstantiateCard();}
			}
		};
		xmlhttp.open("GET", "php/101.php?q="+sqlstr.toString(), true);
		xmlhttp.send();
	}
}

/* EDITOR */
function AutocompletChanged(){
	if(document.getElementById("totalCompletion").checked){
		window.editor.on("keyup", function (cm, event) {
			if(event.keyCode == 27){return;}

			var cr=cm.getCursor();
			var pr_char=cm.getLine(cr["line"]).charAt([cr["ch"]]-1); // Character before cursor.
			if(pr_char===undefined || (pr_char!="." && !(/^[a-zA-Z0-9]+$/).test(pr_char))){return false;} // Not autocomplete if its not alfanumeric.

			if (!cm.state.completionActive && event.keyCode != 13) {
				CodeMirror.commands.autocomplete(cm, null, {completeSingle: false});
			}
		});
	}else{
		window.editor.on("keyup", function(){});
	}
}
function GetTableHint(){
	if(document.getElementById("tableHint").checked){
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				// console.debug(this.responseText);

				if(this.responseText===""){return;}
				var hints = JSON.parse(this.responseText);
				defaults["hintOptions"]["tables"]=hints;
				window.editor.setOption("hintOptions",defaults["hintOptions"]);
			}
		};
		xmlhttp.open("GET", "php/103.php"+(document.getElementById("procHint").checked ? "?proc" : ""), true);
		xmlhttp.send();
	}else{
		if(defaults["hintOptions"]["tables"]!==[]){
			defaults["hintOptions"]["tables"]=[];
			window.editor.setOption("hintOptions",defaults["hintOptions"]);
		}
	}
}
function GetSnippets(){
	// Need it's own XMLHttpRequest
	var xmlhttp2 = new XMLHttpRequest();
	xmlhttp2.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var res=this.responseText;
			res=res.split("\n");

			for (var i = 0; i < res.length; i++) {
				if(res[i].toString().trim()==="---"){continue;}

				res[i]=res[i].split("::");
				if(res[i].length<2){
					res.splice(i, 1);
					continue;
				}

				res[i][1]=res[i][1].replace(/\\n/g,"\n").replace(/\\t/g,"\t");
			}

			snippets=res;

			snippetsHTML='<option selected disabled value="1000">Copy snippet to clipboard</option>';
			var groupclosing=false;
			for (var i = 0; i < snippets.length; i++) {
				if(snippets[i].toString().trim()==="---"){
					snippetsHTML+='<option class="separator" disabled="true"></option>';
					continue;
				}else if(snippets[i][0]===""){
					if(groupclosing){snippetsHTML+="<br/></optgroup>";}

					snippetsHTML+='<option class="separator" disabled="true"></option>';
					snippetsHTML+='<optgroup label="'+snippets[i][1]+'">'
					groupclosing=true;
					continue;
				}

				snippetsHTML+='<option value="'+i.toString()+'">'+snippets[i][0]+"</option>";
			}
			if(groupclosing){snippetsHTML+="<br/></optgroup><br/>";}
		}
	};
	xmlhttp2.open("GET", "snippets.txt", true);
	xmlhttp2.send();
}

function adbchange(dbname){
	dbname=dbname.toString();

	var aloader = document.createElement("div");
	aloader.className="aloader";
	document.getElementById("_dbname").parentNode.insertBefore(aloader, document.getElementById("_dbname"));

	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var pre = this.responseText.toString();
			aloader.remove();

			if(pre == "1"){
				GetTableHint();
			}else{
				alert("Database "+dbname+" does not exists.\n\nUse: SHOW DATABASES");
				document.getElementById("_dbname").value = pre;
			}
		}
	};
	xmlhttp.open("GET", "php/106.php?db="+dbname, true);
	xmlhttp.send();
}

function localset(ind,val){
	localStorage.setItem("phpmiami/"+ind.toString(),val.toString());
}

/* EXTRAS */
// Some from stackoverflow
function ScrollBottom(){
	document.getElementById("main").scrollTo(0,document.getElementById("main").scrollHeight);
}
function selectText(node) {
	if (document.body.createTextRange) {
		const range = document.body.createTextRange();
		range.moveToElementText(node);
		range.select();
	} else if (window.getSelection) {
		const selection = window.getSelection();
		const range = document.createRange();
		range.selectNodeContents(node);
		selection.removeAllRanges();
		selection.addRange(range);
	}
}
function escapeHtml(text) {
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;',
		"`": '&#768;'
	};

	return text.toString().replace(/[&<>"'`]/g, function(m) { return map[m]; });
}
function copyTextToClipboard(text) {
	if (!navigator.clipboard) {
		var textArea = document.createElement("textarea");
		textArea.value = text;

		// Avoid scrolling to bottom
		textArea.style.position = "fixed";
		textArea.style.top = "0";
		textArea.style.left = "0";

		document.body.appendChild(textArea);
		textArea.focus();
		textArea.select();

		try {
			document.execCommand('copy');
		} catch (err) {
			console.error('Fallback: Oops, unable to copy', err);
		}

		document.body.removeChild(textArea);
		return;
	}
	navigator.clipboard.writeText(text).then(function() {}, function(err) {
		console.error('Async: Could not copy text. ', err);
	});
}

function DownloadText(filename, textarea, charset){
	var text= document.getElementById(textarea.toString()).value;
	if(text === undefined || text===null){
		text= document.getElementById(textarea.toString()).value;
		if(text === undefined || text===null){return;}
	}

	var element = document.createElement('a');
	element.setAttribute('href', 'data:text/plain;charset='+charset+',' + encodeURIComponent(text));
	element.setAttribute('download', filename);

	element.style.display = 'none';
	document.body.appendChild(element);

	element.click();

	document.body.removeChild(element);
}

function ShowHideStylesPanel(visible){
	if(visible===undefined){
		document.getElementById("styles_panel").style.width = (document.getElementById("styles_panel").style.width=="0vw" ? "max(50vw,512px)":"0vw");
		document.getElementById("styles_panel").style.opacity = (document.getElementById("styles_panel").style.opacity==0 ? 1:0);
	}else{
		document.getElementById("styles_panel").style.width = (visible ? "max(50vw,512px)":"0vw");
		document.getElementById("styles_panel").style.opacity = (visible ? 1:0);
	}
}
