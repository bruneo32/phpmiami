const THEMES = [
	/* CLASSIC */
	{
		"_extSS":"",
		"_extCM":"default",
		"ffmono":"Roboto",
		"ffserif":"Martel",

		"color1":"#588c7e",
		"color2":"#f2e394",
		"color3":"#f2ae72",
		"color4":"#d96459",

		"color11":"#96ceb4",
		"color22":"#ffeead",
		"color33":"#ffcc5c",
		"color44":"#ff6f69",

		"aux1":"#FFFFFF",
		"aux2":"#000000",
		"aux11":"#EEEEEE",
		"aux22":"#666666"
	},
	/* FLOWER */
	{
		"_extSS":"",
		"_extCM":"default",
		"ffmono":"Roboto",
		"ffserif":"Martel",

		"color1":"#5b9aa0",
		"color2":"#d6d4e0",
		"color3":"#b8a9c9",
		"color4":"#622569",

		"color11":"#f9d5e5",
		"color22":"#eeac99",
		"color33":"#e06377",
		"color44":"#c83349",

		"aux1":"#FFFFFF",
		"aux2":"#000000",
		"aux11":"#EEEEEE",
		"aux22":"#666666"
	},
	/* Sand */
	{
		"_extSS":"",
		"_extCM":"default",
		"ffmono":"Roboto",
		"ffserif":"Martel",

		"color1":"#9f845b",
		"color2":"#d9ad7c",
		"color3":"#a2836e",
		"color4":"#674d3c",

		"color11":"#fbefcc",
		"color22":"#f9ccac",
		"color33":"#f4a688",
		"color44":"#e0876a",

		"aux1":"#FFFFFF",
		"aux2":"#000000",
		"aux11":"#EEEEEE",
		"aux22":"#666666"
	},
	/* Sky */
	{
		"_extSS":"",
		"_extCM":"default",
		"ffmono":"Roboto",
		"ffserif":"Martel",

		"color1":"#cfe0e8",
		"color2":"#b7d7e8",
		"color3":"#87bdd8",
		"color4":"#daebe8",

		"color11":"#bccad6",
		"color22":"#8d9db6",
		"color33":"#667292",
		"color44":"#f1e3dd",

		"aux1":"#666666",
		"aux2":"#000000",
		"aux11":"#EEEEEE",
		"aux22":"#666666"
	},
	/* Rustic */
	{
		"_extSS":"https://codemirror.net/theme/solarized.css",
		"_extCM":"solarized",
		"ffmono":"Roboto",
		"ffserif":"Martel",

		"color1":"#454140",
		"color2":"#bd5734",
		"color3":"#a79e84",
		"color4":"#7a3b2e",

		"color11":"#686256",
		"color22":"#c1502e",
		"color33":"#587e76",
		"color44":"#a96e5b",

		"aux1":"#FFFFFF",
		"aux2":"#000000",
		"aux11":"#EEEEEE",
		"aux22":"#666666"
	},
	/* Matrix */
	{
		"_extSS":"https://codemirror.net/theme/the-matrix.css",
		"_extCM":"the-matrix",
		"ffmono":"Roboto",
		"ffserif":"Martel",

		"color1":"#000000",
		"color2":"#004400",
		"color3":"#000000",
		"color4":"#000000",

		"color11":"#2D2D2D",
		"color22":"#161616",
		"color33":"#2D532D",
		"color44":"#008803",

		"aux1":"#00FF00",
		"aux2":"#FFFFFF",
		"aux11":"#000000",
		"aux22":"#1A2E1B"
	},
	/* Darcula*/
	{
		"_extSS":"https://codemirror.net/theme/darcula.css",
		"_extCM":"darcula",
		"ffmono":"Roboto",
		"ffserif":"Martel",

		"color1":"#606366",
		"color2":"#313335",
		"color3":"#000000",
		"color4":"#000000",

		"color11":"#000000",
		"color22":"#2B2B2B",
		"color33":"#000000",
		"color44":"#282a36",

		"aux1":"#222222",
		"aux2":"#EEEEEE",
		"aux11":"#424242",
		"aux22":"#828282"
	},
	/* dRACULA*/
	{
		"_extSS":"https://codemirror.net/theme/dracula.css",
		"_extCM":"dracula",
		"ffmono":"Roboto",
		"ffserif":"Martel",

		"color1":"#9876AA",
		"color2":"#323c57",
		"color3":"#000000",
		"color4":"#000000",

		"color11":"#000000",
		"color22":"#282a36",
		"color33":"#000000",
		"color44":"#6272a4",

		"aux1":"#222222",
		"aux2":"#EEEEEE",
		"aux11":"#424242",
		"aux22":"#828282"
	}
];

function LoadStyles(){
	var theme_styles = localStorage.getItem("phpmiami/theme");

	if(theme_styles == null){
		localStorage.setItem("phpmiami/theme",JSON.stringify(THEMES[0]));
	}

	theme_styles = JSON.parse(theme_styles);

	SetStyles(theme_styles);

	if(document.getElementById("__cs_color_1") == null){return;}

	document.getElementById("__cs_extSS").value = theme_styles["_extSS"];
	document.getElementById("__cs_extCM").value = theme_styles["_extCM"];

	document.getElementById("__cs_ffmono").value = theme_styles["ffmono"];
	document.getElementById("__cs_ffserif").value = theme_styles["ffserif"];

	document.getElementById("__cs_color_1").value = theme_styles["color1"];
	document.getElementById("__cs_color_2").value = theme_styles["color2"];
	document.getElementById("__cs_color_3").value = theme_styles["color3"];
	document.getElementById("__cs_color_4").value = theme_styles["color4"];

	document.getElementById("__cs_color_11").value = theme_styles["color11"];
	document.getElementById("__cs_color_22").value = theme_styles["color22"];
	document.getElementById("__cs_color_33").value = theme_styles["color33"];
	document.getElementById("__cs_color_44").value = theme_styles["color44"];

	document.getElementById("__cs_color_aux1").value = theme_styles["aux1"];
	document.getElementById("__cs_color_aux2").value = theme_styles["aux2"];
	document.getElementById("__cs_color_aux11").value = theme_styles["aux11"];
	document.getElementById("__cs_color_aux22").value = theme_styles["aux22"];
}
function SaveStyles(){
	var theme_styles = {};

	theme_styles["_extCM"]= defaults.theme;
	theme_styles["_extSS"]= document.getElementById("externalStylesheet").href;

	theme_styles["ffmono"]=document.documentElement.style.getPropertyValue("--ffmono");
	theme_styles["ffserif"]=document.documentElement.style.getPropertyValue("--ffserif");

	theme_styles["color1"]=document.documentElement.style.getPropertyValue("--color1");
	theme_styles["color2"]=document.documentElement.style.getPropertyValue("--color2");
	theme_styles["color3"]=document.documentElement.style.getPropertyValue("--color3");
	theme_styles["color4"]=document.documentElement.style.getPropertyValue("--color4");

	theme_styles["color11"]=document.documentElement.style.getPropertyValue("--color11");
	theme_styles["color22"]=document.documentElement.style.getPropertyValue("--color22");
	theme_styles["color33"]=document.documentElement.style.getPropertyValue("--color33");
	theme_styles["color44"]=document.documentElement.style.getPropertyValue("--color44");

	theme_styles["aux1"]=document.documentElement.style.getPropertyValue("--aux1");
	theme_styles["aux2"]=document.documentElement.style.getPropertyValue("--aux2");
	theme_styles["aux11"]=document.documentElement.style.getPropertyValue("--aux11");
	theme_styles["aux22"]=document.documentElement.style.getPropertyValue("--aux22");

	localStorage.setItem("phpmiami/theme", JSON.stringify(theme_styles));
}
function SetTheme(idx){
	SetStyles(THEMES[idx]);
}
function SetStyles(theme_styles){
	for (const x in theme_styles) {
		document.documentElement.style.setProperty("--"+x, theme_styles[x]);
	}

	document.getElementById("externalStylesheet").href = theme_styles["_extSS"];

	ChangeCMTheme(theme_styles["_extCM"]);
	if (typeof defaults !== 'undefined') {defaults.theme = theme_styles["_extCM"];}

	if(document.getElementById("__cs_color_1") == null){return;}
	document.getElementById("__cs_extSS").value = theme_styles["_extSS"];
	document.getElementById("__cs_extCM").value = theme_styles["_extCM"];

	document.getElementById("__cs_ffmono").value = theme_styles["ffmono"];
	document.getElementById("__cs_ffserif").value = theme_styles["ffserif"];

	document.getElementById("__cs_color_1").value = theme_styles["color1"];
	document.getElementById("__cs_color_2").value = theme_styles["color2"];
	document.getElementById("__cs_color_3").value = theme_styles["color3"];
	document.getElementById("__cs_color_4").value = theme_styles["color4"];

	document.getElementById("__cs_color_11").value = theme_styles["color11"];
	document.getElementById("__cs_color_22").value = theme_styles["color22"];
	document.getElementById("__cs_color_33").value = theme_styles["color33"];
	document.getElementById("__cs_color_44").value = theme_styles["color44"];

	document.getElementById("__cs_color_aux1").value = theme_styles["aux1"];
	document.getElementById("__cs_color_aux2").value = theme_styles["aux2"];
	document.getElementById("__cs_color_aux11").value = theme_styles["aux11"];
	document.getElementById("__cs_color_aux22").value = theme_styles["aux22"];

}
function SetStylesBtn(){
	document.getElementById("externalStylesheet").href = document.getElementById("__cs_extSS").value;

	ChangeCMTheme(document.getElementById("__cs_extCM").value);
	if (typeof defaults !== 'undefined') {defaults.theme=document.getElementById("__cs_extCM").value;}

	document.documentElement.style.setProperty("--ffmono",document.getElementById("__cs_ffmono").value);
	document.documentElement.style.setProperty("--ffserif",document.getElementById("__cs_ffserif").value);

	document.documentElement.style.setProperty("--color1",document.getElementById("__cs_color_1").value);
	document.documentElement.style.setProperty("--color2",document.getElementById("__cs_color_2").value);
	document.documentElement.style.setProperty("--color3",document.getElementById("__cs_color_3").value);
	document.documentElement.style.setProperty("--color4",document.getElementById("__cs_color_4").value);

	document.documentElement.style.setProperty("--color11",document.getElementById("__cs_color_11").value);
	document.documentElement.style.setProperty("--color22",document.getElementById("__cs_color_22").value);
	document.documentElement.style.setProperty("--color33",document.getElementById("__cs_color_33").value);
	document.documentElement.style.setProperty("--color44",document.getElementById("__cs_color_44").value);

	document.documentElement.style.setProperty("--aux1",document.getElementById("__cs_color_aux1").value);
	document.documentElement.style.setProperty("--aux2",document.getElementById("__cs_color_aux2").value);
	document.documentElement.style.setProperty("--aux11",document.getElementById("__cs_color_aux11").value);
	document.documentElement.style.setProperty("--aux22",document.getElementById("__cs_color_aux22").value);
}

function ChangeCMTheme(__theme){
	var cms = document.getElementsByClassName("CodeMirror");
	if(cms.length==0){return;}

	for (var i=0; i<cms.length; i++) {
		cms[i].classList.remove("cm-s-"+defaults.theme);
		cms[i].classList.add("cm-s-"+__theme);
	}
}
