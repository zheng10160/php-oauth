var designWidth=750;
function resize(){
	var width=document.documentElement.clientWidth;//667px
	var fontSize=width / designWidth * 100;
	document.documentElement.style.fontSize=fontSize+"px";
}
resize();
window.addEventListener('resize',resize);

// 设计稿为1334x750
// 元素的宽度高度=设计稿上的宽高/100rem
// 字体大小为设计稿上高度/2rem


