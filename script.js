var cardHTML = '<div class="card"><table><tr><td>SQL</td><td><textarea></textarea><br><input type="button" style="float:right;" title="CTRL+ENTER" class="button" value="Run query" onclick="RunQuery()"/></td></tr><tr class="result_query"><td>Result</td><td></td></tr><tr class="result_query"><td onclick="SelectNext(this,1)">Raw result</td><td></td></tr></table></div>';
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
  hintOptions: {}
};
var G_TEXTAREA=null;

function CopyBtn(btn_elem){
  var textarea = btn_elem.parentElement.children[0];
  navigator.clipboard.writeText(textarea.value);
}
function SelectNext(btn_elem,ind){
  var elm = btn_elem.parentElement.children[ind];
  selectText(elm);
}

function RunQuery(){
  var table = G_TEXTAREA.parentElement.parentElement.parentElement;
  var val = window.editor.getValue();

  G_TEXTAREA.setAttribute("readonly","true");
  table.parentElement.style.backgroundColor="var(--color2)";
  G_TEXTAREA.parentElement.lastChild.setAttribute("onclick","CopyBtn(this)");
  G_TEXTAREA.parentElement.lastChild.value="Copy";

  ExecQuery(val,table.children[1].children[1],table.children[2].children[1]);

  // Set the textarea for the COPY
  G_TEXTAREA.value=val;
  G_TEXTAREA.innerHTML=val;
}

function InstantiateCard(){
  document.getElementById("main").innerHTML+=cardHTML;

  G_TEXTAREA = document.querySelector(".card:last-child textarea");
  window.editor = CodeMirror.fromTextArea(G_TEXTAREA, defaults);
  ScrollBottom();

  AutocompletChanged();
  GetTableHint();
}

function ExecQuery(sqlstr,resulte,rawr){
  console.log(sqlstr);
  if (sqlstr.length == 0) {
    InstantiateCard();
  } else {
    sqlstr=sqlstr.replace(new RegExp("\n","g")," ");

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

        var res=this.responseText;
        console.log(res);
        rawr.innerHTML=escapeHtml(res);

        resulte.parentElement.style.display="table-row";
        rawr.parentElement.style.display="table-row";

        try{
          var resarray = JSON.parse(res);
        } catch(err){
          resulte.innerHTML=res;
          InstantiateCard();
          return;
        }
        console.log(resarray);

        var pres="";
        for (var i = 0; i < resarray.length; i++) {
          console.log(typeof resarray[i]);
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
                pres+="<td>"+row[key]+"</td>";
              }
              pres+="</tr>";
            }

            pres+="</table><br>";
          }
        }

        resulte.innerHTML=pres;

        InstantiateCard();
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
      var cr=cm.getCursor();
      var pr_char=cm.getLine(cr["line"]).charCodeAt([cr["ch"]]-1); // Character before cursor.

      if(pr_char===undefined || pr_char<48 || pr_char===59){return false;} // Not autocomplete if its <"0", OR ; " '

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
    var xmlhttp2 = new XMLHttpRequest();
    xmlhttp2.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(this.responseText===""){return;}
        var hints = JSON.parse(this.responseText);
        defaults["hintOptions"]["tables"]=hints;
        window.editor.setOption("hintOptions",defaults["hintOptions"]);
      }
    };
    xmlhttp2.open("GET", "php/103.php", true);
    xmlhttp2.send();
  }else{
    if(defaults["hintOptions"]["tables"]!==[]){
      defaults["hintOptions"]["tables"]=[];
      window.editor.setOption("hintOptions",defaults["hintOptions"]);
    }
  }
}


/* EXTRAS */
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
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
